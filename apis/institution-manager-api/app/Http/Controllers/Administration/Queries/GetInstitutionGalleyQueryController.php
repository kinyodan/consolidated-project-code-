<?php
namespace App\Http\Controllers\Administration\Queries;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Institution;
use App\Models\InstitutionGallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Exception;

class GetInstitutionGalleyQueryController
{
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
     * List gallery
     *
     * @param string|null $institution_code
     * @return JsonResponse
     */
    public function getInstitutionGallery(?string $institution_code): JsonResponse
    {
        try{
            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);

            if(empty($institution_code)){
                throw new Exception(LanguageTranslationHelper::translate(
                    'institutions.errors.missing_institution_code'
                ));
            }

            if(!DB::table((new Institution())->getTable())->where('institution_code', $institution_code)->exists()){
                throw new Exception(LanguageTranslationHelper::translate(
                    'institutions.errors.invalid_institution_code'
                ));
            }

            $gallery = InstitutionGallery::where('institution_code', $institution_code)->get();

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.gallery_listed'),
                $gallery
            ));
        }catch (\Exception $exception){
            $this->institutionController->logException($exception);

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
