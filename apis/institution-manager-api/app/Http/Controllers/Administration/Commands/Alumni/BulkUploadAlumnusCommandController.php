<?php

namespace App\Http\Controllers\Administration\Commands\Alumni;

use App\Http\Controllers\Administration\Commands\ValidateImageUpload;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\MySQLFunctionsHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\InstitutionTypeHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\ProcessUploadedAlumniListJob;
use App\Model\AlumniUpload;
use App\Model\InstitutionAlumnus;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use Respect\Validation\Validator;
use Respect\Validation\Validator as v;

class BulkUploadAlumnusCommandController
{
    use CanLog;

    /**
     * @var InstitutionController
     */
    protected $institutionController;

    /**
     * Constructor
     * @param InstitutionController $institutionController
     */
    public function __construct(InstitutionController $institutionController)
    {
        $this->institutionController = $institutionController;
    }

    /**
     * Add accreditation to the institution
     *
     * @param Request $request
     * @param string|null $institution_code
     *
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $user = GetLoggedIUserHelper::getUser($request);
        $user_email = isset($user->email) && !empty($user->email) ? $user->email : "admin@craydel.com";
        $request->request->set('user_email', $user_email);

        $validate = $this->validate($request);

        if (!$validate->status) {
            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                $validate->status,
                $validate->message
            )));
        }

        try {
            $fileToProcess = $request->file('alumni_list')->getRealPath();
            $data = (new FastExcel())->import($fileToProcess);

            if (!empty($data)) {
                DB::beginTransaction();

                $chunks = $data->chunk(config('craydel.max_data_chunck_size', 100));
                $totalChunks = $chunks->count();

                $insertCounter = 0;
                foreach ($chunks->all() as $item) {
                    $payload = [
                        'user_email' => $user_email,
                        'file_data' => json_encode($item),
                        'total_records' => count($item),
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ];

                    $inserted = DB::table('alumni_upload')->insert($payload);
                    if ($inserted) {
                        $insertCounter++;
                    }
                }

                if ($insertCounter == $totalChunks) {
                    DB::commit();

                    dispatch(new ProcessUploadedAlumniListJob())->onQueue('process_institution_list_upload');

                    return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                        true,
                        LanguageTranslationHelper::translate('alumni.success.uploaded')
                    )));
                } else {
                    DB::rollBack();
                    return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                        false,
                        LanguageTranslationHelper::translate('alumni.errors.error_uploading_institution_list')
                    )));
                }

            } else {
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('alumni.errors.error_uploading_institution_list')
                )));
            }

        } catch (Exception $exception) {
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('alumni.errors.error_uploading_institution_list')
            )));
        }
    }

    protected function validate(Request $request): CraydelInternalResponseHelper
    {
        $user_email = $request->get('user_email');

        if (!v::email()->notEmpty()->validate($user_email)) {
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('alumni.errors.missing_user_email')
            ));
        }

        if ($request->hasFile('alumni_list')) {
            $fileToProcess = $request->file('alumni_list');
            $file_mime_type = $fileToProcess->getClientMimeType();
            $file_mime_type = CraydelHelperFunctions::toCleanString(strtolower($file_mime_type));
            if (!in_array($file_mime_type, $this->institutionController->allowedExcelMimeTypes)) {
                return (new CraydelInternalResponseHelper(
                    false,
                    sprintf(
                        LanguageTranslationHelper::translate('alumni.errors.invalid_institution_list_file_type'),
                        implode('', $this->institutionController->allowedExcelMimeTypes)
                    )
                ));
            }

            $institution_list_file_size = $fileToProcess->getSize();
            $file_size_in_mbs = CraydelHelperFunctions::convertBytesToMBs($institution_list_file_size);

            if (isset($file_size_in_mbs)) {
                $maximum_allowed = config('craydle.security.maximum_uploaded_file_size', 10);

                if (floatval($file_size_in_mbs) > $maximum_allowed) {
                    return (new CraydelInternalResponseHelper(
                        false,
                        sprintf(
                            LanguageTranslationHelper::translate('alumni.errors.institution_list_file_size_too_big'),
                            $file_size_in_mbs . 'MBs'
                        )
                    ));
                }
            } else {
                return (new CraydelInternalResponseHelper(
                    false,
                    LanguageTranslationHelper::translate('alumni.errors.unable_to_get_the_institution_list_file_size')
                ));
            }
        } else {
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('alumni.errors.missing_institution_list_file')
            ));
        }


        return (new CraydelInternalResponseHelper(
            true,
            LanguageTranslationHelper::translate('alumni.success.validated')
        ));
    }

    public function process(int $alumni_upload_chunk_id, array $alumni_list): CraydelInternalResponseHelper
    {
        try {
            if (empty($alumni_upload_chunk_id)) {
                throw new Exception('Invalid alumni upload chunk ID.');
            }

            if (is_null($alumni_list) || !is_array($alumni_list)) {
                throw new Exception('Missing or Invalid alumni data to process.');
            }

            $alumni_list = collect($alumni_list)->map(function ($item) {
                $institution_code = InstitutionTypeHelper::getInstitutionCodeFromName($item->{'Institution'});
                $alumni_name = isset($item->{'Name'}) ? trim($item->{'Name'}) : null;
                $slug = CraydelHelperFunctions::slugifyString($alumni_name);
                $domain = config('craydle.alumni_ratings_domain');
                $unique_url = $domain . "?slug=" . $slug;

                return [
                    'institution_code' => $institution_code,
                    'alumni_name_slug' => $slug,
                    'alumni_name' => isset($item->{'Name'}) ? trim($item->{'Name'}) : null,
                    'graduation_year' => isset($item->{'Year of graduation'}) ? trim($item->{'Year of graduation'}) : null,
                    'course_taken' => isset($item->{'Course'}) ? trim($item->{'Course'}) : null,
                    'current_employer' => isset($item->{'Current Company'}) ? trim($item->{'Current Company'}) : null,
                    'current_position' => isset($item->{'Current Position'}) ? trim($item->{'Current Position'}) : null,
                    'personal_profile_url' => isset($item->{'LinkedIn link'}) ? trim($item->{'LinkedIn link'}) : null,
                    'current_location' => isset($item->{'Curent Location'}) ? trim($item->{'Curent Location'}) : null,
                    'university_name' => isset($item->{'Institution'}) ? trim($item->{'Institution'}) : null,
                    'unique_url' => $unique_url,
                    'course_type' => isset($item->{'Institution'}) ? trim($item->{'Institution'}) : null,
                    'is_finished' => 0,
                    'status' => 0,
                    'question_step' => 0,
                    'created_at' => Carbon::now()->toDateTimeString()
                ];
            });

            MySQLFunctionsHelper::insertOrIgnore(
                ('institution_alumni'),
                $alumni_list->values()->toArray()
            );


            DB::table('alumni_upload')
                ->where('id', $alumni_upload_chunk_id)
                ->update([
                    'is_processed' => 1,
                    'updated_at' => Carbon::now()->toDateTimeString()
                ]);

            return $this->institutionController->internalResponse(
                new CraydelInternalResponseHelper(true, 'Saved')
            );

        } catch (\Exception $exception) {
            $this->logException($exception);

            DB::table('alumni_upload')
                ->where('id', $alumni_upload_chunk_id)
                ->update([
                    'is_processed' => 0,
                    'failure_reasons' => $exception->getMessage(),
                    'updated_at' => Carbon::now()->toDateTimeString()
                ]);

            return $this->institutionController->internalResponse(
                new CraydelInternalResponseHelper(false, $exception->getMessage())
            );
        }
    }
}
