<?php
namespace App\Http\Controllers\EducationTypes\Commands;

use App\Http\Controllers\EducationTypes\EducationTypesController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\EducationType;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class UpdateEducationTypeCommandController
{
    use CanCache;

    /**
     * @var EducationTypesController
     */
    protected EducationTypesController $educationTypesController;

    /**
     * Constructor
     * @param EducationTypesController $educationTypesController
     */
    public function __construct(EducationTypesController $educationTypesController)
    {
        $this->educationTypesController = $educationTypesController;
    }

    public function update(Request $request, ?string $education_type_id): JsonResponse
    {
        try {
            $education_type_name = CraydelHelperFunctions::toCleanString($request->input('education_type_name'));
            $country_id = CraydelHelperFunctions::toCleanString($request->input('country_id'));

            if (empty($education_type_name)) {
                return $this->educationTypesController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('educations.errors.missing_subject_name')
                ));
            }

            if (empty($country_id)) {
                return $this->educationTypesController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('educations.errors.missing_country_id')
                ));
            }

            $education_type_id = CraydelHelperFunctions::toNumbers($education_type_id);

            $previous_education_system_details = DB::table((new EducationType())->getTable())
                ->where('id', $education_type_id)
                ->first();

            DB::transaction(function () use ($education_type_id, $education_type_name,$country_id) {
                DB::table((new EducationType())->getTable())->where('id', $education_type_id)->update([
                    'education_type_name' => $education_type_name,
                    'country_id' => $country_id,
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ]);
            });

            $previous_country_code = CountryHelper::getCountryCodeFromID($previous_education_system_details->country_id);
            $country_code = CountryHelper::getCountryCodeFromID($country_id);

            if(!empty($country_code)){
                self::clearCache('country_education_systems_from_country_code_'.$country_code);
            }

            if(!empty($previous_country_code)){
                self::clearCache('country_education_systems_from_country_code_'.$previous_country_code);
            }

            return $this->educationTypesController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('educations.success.is_updated')
            ));

        } catch (Exception $exception) {
            $this->educationTypesController->logException($exception);
            return $this->educationTypesController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('educations.errors.is_updated')
            )));
        }
    }
}
