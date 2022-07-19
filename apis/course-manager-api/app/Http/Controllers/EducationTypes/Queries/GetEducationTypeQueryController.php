<?php
namespace App\Http\Controllers\EducationTypes\Queries;

use App\Http\Controllers\EducationTypes\EducationTypesController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\EducationType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class GetEducationTypeQueryController
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
     * Get the education type details
     *
     * @param string|null $education_type_id
     * @return JsonResponse
     */
    public function get(?string $education_type_id): JsonResponse
    {
        try {
            $education_type=EducationType::findOrFail($education_type_id);
            return $this->educationTypesController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('educations.success.is_selected'),
                $education_type
            ));

        } catch (Exception $exception) {
            $this->educationTypesController->logException($exception);
            return $this->educationTypesController->respondInJSON((new CraydelJSONResponseType(
                false,
                'Failed to get the education type details'
            )));
        }
    }

    /**
     * Get the education type in a country based on the country code
    */
    public function getEducationTypesInCountryByCountryCode(string $country_code): JsonResponse
    {
        try{
            $base_education_system_ids = [12, 13];

            if(empty($country_code)){
                throw new Exception("Missing code while getting education system in a country");
            }

            $education_systems = self::cache("country_education_systems_from_country_code_" . $country_code);

            if(is_null($education_systems) || count($education_systems) <= 0){
                $country_id = CountryHelper::getIDBasedOnCode($country_code);

                $education_systems = DB::table((new EducationType())->getTable())
                    ->where('country_id', $country_id)
                    ->whereNotIn('id', $base_education_system_ids)
                    ->get([
                        'id',
                        'education_type_name'
                    ])->toArray();

                $education_systems = array_merge($education_systems,[[
                    'id' => 12,
                    'education_type_name' => 'IGCSE / A-level',
                ]]);

                $education_systems = self::cache("country_education_systems_from_country_code_" . $country_code, $education_systems);
            }

            return $this->educationTypesController->respondInJSON((new CraydelJSONResponseType(
                true,
                'Listed',
                $education_systems
            )));
        }catch (Exception $exception){
            $this->educationTypesController->logException($exception);
            return $this->educationTypesController->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage() ?? 'Failed to get the countries education type'
            )));
        }
    }
}
