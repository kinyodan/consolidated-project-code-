<?php
namespace App\Http\Controllers\Administration\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\InstitutionTypeHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\MySQLFunctionsHelper;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\ProcessUploadedInstitutionListJob;
use App\Jobs\UploadInstitutionLogoToCDNJob;
use App\Models\Institution;
use App\Models\InstitutionUpload;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;
use Respect\Validation\Validator as v;

class BulkUploadInstitutionsCommandController
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
    public function __construct(InstitutionController $institutionController){
        $this->institutionController = $institutionController;
    }

    /**
     * Create a new institution
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $user = GetLoggedIUserHelper::getUser($request);
        $user_email = isset($user->email) && !empty($user->email) ? $user->email: "admin@craydel.com";
        $request->request->set('user_email', $user_email);

        $validate = $this->validate($request);

        if(!$validate->status){
            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                $validate->status,
                $validate->message
            )));
        }

        try{
            $fileToProcess = $request->file('institution_list')->getRealPath();
            $data = (new FastExcel())->import($fileToProcess);

            if(!empty($data)){
                DB::beginTransaction();

                $chunks = $data->chunk(config('craydel.max_data_chunck_size', 100));
                $totalChunks = $chunks->count();

                $insertCounter =  0;
                foreach ($chunks->all() as $item) {
                    $payload = [
                        'user_email' => $user_email,
                        'file_data' => json_encode($item),
                        'total_records' => count($item),
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ];

                    $inserted = DB::table((new InstitutionUpload())->getTable())->insert($payload);
                    if($inserted){
                        $insertCounter++;
                    }
                }

                if ($insertCounter == $totalChunks){
                    DB::commit();

                    dispatch(new ProcessUploadedInstitutionListJob())->onQueue('process_institution_list_upload');

                    return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                        true,
                        LanguageTranslationHelper::translate('institutions.success.uploaded')
                    )));
                }else{
                    DB::rollBack();
                    return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                        false,
                        LanguageTranslationHelper::translate('institutions.errors.error_uploading_institution_list')
                    )));
                }

            }else{
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.error_uploading_institution_list')
                )));
            }

        }catch (Exception $exception){
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('institutions.errors.error_uploading_institution_list')
            )));
        }
    }

    /**
     * Validate institution list file
     * @param Request $request
     *
     * @return CraydelInternalResponseHelper
     */
    protected function validate(Request $request): CraydelInternalResponseHelper
    {
        $user_email = $request->get('user_email');

        if(!v::email()->notEmpty()->validate($user_email)){
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('institutions.errors.missing_user_email')
            ));
        }

        if ($request->hasFile('institution_list')) {
            $fileToProcess = $request->file('institution_list');
            $file_mime_type = $fileToProcess->getClientMimeType();
            $file_mime_type = CraydelHelperFunctions::toCleanString(strtolower($file_mime_type));
            if (!in_array($file_mime_type, $this->institutionController->allowedExcelMimeTypes)) {
                return (new CraydelInternalResponseHelper(
                    false,
                    sprintf(
                        LanguageTranslationHelper::translate('institutions.errors.invalid_institution_list_file_type'),
                        implode('', $this->institutionController->allowedExcelMimeTypes)
                    )
                ));
            }

            $institution_list_file_size = $fileToProcess->getSize();
            $file_size_in_mbs = CraydelHelperFunctions::convertBytesToMBs($institution_list_file_size);

            if(isset($file_size_in_mbs)){
                $maximum_allowed = config('craydle.security.maximum_uploaded_file_size', 10);

                if(floatval($file_size_in_mbs) > $maximum_allowed){
                    return (new CraydelInternalResponseHelper(
                        false,
                        sprintf(
                            LanguageTranslationHelper::translate('institutions.errors.institution_list_file_size_too_big'),
                            $file_size_in_mbs.'MBs'
                        )
                    ));
                }
            }else{
                return (new CraydelInternalResponseHelper(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.unable_to_get_the_institution_list_file_size')
                ));
            }
        }else{
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('institutions.errors.missing_institution_list_file')
            ));
        }


        return (new CraydelInternalResponseHelper(
            true,
            LanguageTranslationHelper::translate('institutions.success.validated')
        ));
    }

    /**
     * Save a record while processing
     *
     * @param int $institution_upload_chunk_id
     * @param array $institutions_list
     * @return CraydelInternalResponseHelper
     */
    public function process(int $institution_upload_chunk_id, array $institutions_list): CraydelInternalResponseHelper
    {
        try{
            if(empty($institution_upload_chunk_id)){
                throw new Exception('Invalid institutions upload chunk ID.');
            }

            if(is_null($institutions_list) || !is_array($institutions_list)){
                throw new Exception('Missing or Invalid institution data to process.');
            }

            $institutions_list = collect($institutions_list)->map(function ($item){
                $country_code = CountryHelper::getISOCodeFromCountryName($item->{'Country'});
                $institution_type = InstitutionTypeHelper::getInstitutionTypeIdFromName($item->{'Institution Type'});
                $institution_name = isset($item->{'Institition Name'}) ? trim($item->{'Institition Name'}) : Str::random(10);

                return [
                    'country_code' => $country_code,
                    'institution_name_slug' => CraydelHelperFunctions::slugifyString($institution_name),
                    'institution_type' => $institution_type,
                    'ownership_type' => isset($item->{'Ownership Type'}) ? trim($item->{'Ownership Type'}) : null,
                    'institution_code' => CraydelHelperFunctions::makeRandomString(10, 'INT', false),
                    'institution_name' => CraydelHelperFunctions::toCleanString($institution_name),
                    'description' => isset($item->{'Description'}) ? CraydelHelperFunctions::toCleanString($item->{'Description'}) : Str::random(100),
                    'profile_details' => $institution_name,
                    'is_deleted' => !empty($is_deleted) ? 1 : 0,
                    'is_active' => !empty($is_active) ? 1 : 0,
                    'city' => isset($item->{'City'}) ? trim($item->{'City'}) : null,
                    'email_address' => isset($item->{'Email Address'}) ? trim($item->{'Email Address'}) : null,
                    'academic_office_phone_number' => isset($item->{'Phone Admissions Office Phone Number'}) ? trim($item->{'Phone Admissions Office Phone Number'}) : null,
                    'academic_office_email_address' => isset($item->{'Admissions Email Address'}) ? trim($item->{'Admissions Email Address'}) : null,
                    'academic_office_postal_address' => isset($item->{'Admissions Postal Address'}) ? trim($item->{'Admissions Postal Address'}) : null,
                    'university_postal_address' => isset($item->{'Postal Address'}) ? nl2br(trim($item->{'Postal Address'})) : null,
                    'phone_number' => isset($item->{'Phone Number'}) ? trim($item->{'Phone Number'}) : null,
                    'country_ranking' => isset($item->{'Country Ranking'}) ? trim($item->{'Country Ranking'}) : null,
                    'regional_ranking' => isset($item->{'Regional Ranking'}) ? trim($item->{'Regional Ranking'}) : null,
                    'continental_ranking' => isset($item->{'Continental Ranking'}) ? trim($item->{'Continental Ranking'}) : null,
                    'global_ranking' => isset($item->{'Global Ranking'}) ? trim($item->{'Global Ranking'}) : null,
                    'date_registered' => isset($item->{'Registration Date'}) ? trim($item->{'Registration Date'}) : null,
                    'accredited_by_acronym' => isset($item->{'Accredited By'}) ? CraydelHelperFunctions::makeAcronym($item->{'Accredited By'}) : null,
                    'accredited_by' => isset($item->{'Accredited By'}) ? trim($item->{'Accredited By'}) : null,
                    'accreditation_body_url' => isset($item->{'Accreditation body website'}) ? trim($item->{'Accreditation body website'}) : null,
                    'website_url' => isset($item->{'Website'}) ? trim($item->{'Website'}) : null,
                    'temp_logo_path' => isset($item->{'Logo'}) ? trim($item->{'Logo'}) : null,
                    'inquiry_form_url' => isset($item->{'Enquiry Form Link'}) ? trim($item->{'Enquiry Form Link'}) : null,
                    'finance_office_phone_number' => isset($item->{'Finance Phone Number'}) ? trim($item->{'Finance Phone Number'}) : null,
                    'finance_office_email_address' => isset($item->{'Finance Email Address'}) ? trim($item->{'Finance Email Address'}) : null,
                    'main_campus_physical_location' => isset($item->{'Main Campus Physical Location'}) ? nl2br(trim($item->{'Main Campus Physical Location'})) : null,
                    'main_campus_latitude' => isset($item->{'Main Campus Latitude'}) ? trim($item->{'Main Campus Latitude'}) : null,
                    'main_campus_longtitude' => isset($item->{'Main Campus Longitude'}) ? trim($item->{'Main Campus Longitude'}) : null,
                    'logo_url' => null,
                    'logo_url_small' => null,
                    'has_updates' => 1,
                    'is_picked_for_indexing' => 0,
                    'created_at' => Carbon::now()->toDateTimeString()
                ];
            });

            $institutions_codes = $institutions_list->map(function ($institution){
                return [
                    'institution_code' => isset($institution['institution_code']) && !empty($institution['institution_code']) ? $institution['institution_code'] : null
                ];
            })->reject(function ($institution){
                return !isset($institution['institution_code']) || is_null($institution['institution_code']);
            })->unique('institution_code')->values()->toArray();

            MySQLFunctionsHelper::insertOrIgnore(
                (new Institution())->getTable(),
                $institutions_list->values()->toArray()
            );

            if($institutions_codes){
                foreach ($institutions_codes as $institution_code){
                    if(isset($institution_code['institution_code']) && !empty($institution_code['institution_code'])){
                        dispatch((new UploadInstitutionLogoToCDNJob($institution_code['institution_code'])))
                            ->onQueue('upload_images_to_cdn');
                    }
                }

                DB::table((new InstitutionUpload())->getTable())
                    ->where('id', $institution_upload_chunk_id)
                    ->update([
                        'is_processed' => 1,
                        'successful_records' => count($institutions_codes),
                        'failed_records' => count($institutions_list) - count($institutions_codes),
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);

                return $this->institutionController->internalResponse(
                    new CraydelInternalResponseHelper(true, 'Saved')
                );
            }else{
                throw new Exception('Error while processing the staged institution upload file.');
            }
        }catch (\Exception $exception){
            $this->logException($exception);

            DB::table((new InstitutionUpload())->getTable())
                ->where('id', $institution_upload_chunk_id)
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
