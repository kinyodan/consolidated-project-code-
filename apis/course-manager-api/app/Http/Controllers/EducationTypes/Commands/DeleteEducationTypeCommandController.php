<?php

namespace App\Http\Controllers\EducationTypes\Commands;

use App\Http\Controllers\EducationTypes\EducationTypesController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\ClusterSubject;
use App\Models\EducationType;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class DeleteEducationTypeCommandController
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

    public function delete(?string $education_type_id): JsonResponse {
        try {

            $count = ClusterSubject::where('education_type_id','=',$education_type_id)->count();

            if (!empty($count)) {
                return $this->educationTypesController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('clusters.errors.cant_deleted')
                )));
            }

            $education_type = EducationType::find($education_type_id);

            $country_code = CountryHelper::getCountryCodeFromID($education_type->country_id);

            if(!empty($country_code)){
                self::clearCache('country_education_systems_from_country_code_'.$country_code);
            }

            $education_type->delete();

            return $this->educationTypesController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('educations.success.is_deleted')
            ));

        } catch (Exception $exception) {
            $this->educationTypesController->logException($exception);
            return $this->educationTypesController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('educations.errors.is_deleted')
            )));
        }
    }
}
