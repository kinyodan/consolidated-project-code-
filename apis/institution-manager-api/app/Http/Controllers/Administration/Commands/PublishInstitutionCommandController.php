<?php
namespace App\Http\Controllers\Administration\Commands;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Jobs\PushInstitutionDataToSearchEngineJob;
use App\Models\Institution;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PHPUnit\Util\Exception;

class PublishInstitutionCommandController
{
    protected $institution_controller;

    public function __construct(InstitutionController $institution_controller)
    {
        $this->institution_controller = $institution_controller;
    }

    public function publish(Request $request, $institution_code): JsonResponse
    {
        try {
            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);
            $user = GetLoggedIUserHelper::getUser($request);
            $username = $user->username ?? 'Course Admin';
            if (empty($institution_code)) {
                throw new Exception(LanguageTranslationHelper::translate('institutions.errors.missing_institution_code'));
            }
            $current_status = null;
            $current_status = DB::table((new Institution())->getTable())->where('institution_code', trim($institution_code))->value('is_published');
            if ($current_status == 1) {
                return $this->institution_controller->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.published')));
            } else {
                DB::table((new Institution())->getTable())->where('institution_code', trim($institution_code))->update([
                    'is_published' => 1,
                    'is_active' => 1,
                    'has_updates' => 1,
                    'updated_by' => $username,
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ]);
                dispatch((new PushInstitutionDataToSearchEngineJob($institution_code)))->onQueue('push_course_to_search_engine');
                $data = [
                    'institution_code' => $institution_code,
                ];
                $response = Http::withHeaders([
                ])->post(
                    config('services.craydel_services.courses_manager.endpoints.publish-courses-related-to-this-institution'),
                    $data
                );
                $response = json_decode($response->body());
                if (!empty($response)) {
                    if ($response->status == true) {
                        return $this->institution_controller->respondInJSON(new CraydelJSONResponseType(
                            true,
                            LanguageTranslationHelper::translate('institutions.success.published')));
                    } else {
                        return $this->institution_controller->respondInJSON(new CraydelJSONResponseType(
                            false,
                            LanguageTranslationHelper::translate('institutions.error.published')));
                    }
                }
            }
        } catch (\Exception $exception) {
            $this->institution_controller->logException($exception);
            return $this->institution_controller->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
