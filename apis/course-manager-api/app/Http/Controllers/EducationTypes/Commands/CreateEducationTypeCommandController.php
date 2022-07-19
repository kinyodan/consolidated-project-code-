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
use App\Models\Subject;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class CreateEducationTypeCommandController
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

    /**
     * List Courses
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
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
            if (DB::table((new EducationType())->getTable())->where('education_type_name', $request->input('education_type_name'))->exists()) {
                return $this->educationTypesController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('educations.errors.duplicate_subject_name')
                ));
            }

            $affected = DB::table('education_types')->insert([
                ['education_type_name' => $education_type_name, 'country_id' => $country_id,
                    'created_at' => Carbon::now()->toDateTimeString()]
            ]);

            if ($affected) {
                return $this->educationTypesController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('educations.success.created')
                ));
            }

            $country_code = CountryHelper::getCountryCodeFromID($country_id);

            if(!empty($country_code)){
                self::clearCache('country_education_systems_from_country_code_'.$country_code);
            }

            throw new Exception("Unable to save the education system details.");
        } catch (\Exception $exception) {
            $this->educationTypesController->logException($exception);
            return $this->educationTypesController->respondInJSON((new CraydelJSONResponseType(
                false,
                "Error creating the education system"
            )));
        }
    }
}
