<?php
namespace App\Http\Controllers\Administration\Queries\Alumni;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\InstitutionAlumnus;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetAlumnusDetailsQueryController
{
    /**
     * @var InstitutionController
     */
    protected InstitutionController $institutionController;

    /**
     * Constructor
     * @param InstitutionController $institutionController
     */
    public function __construct(InstitutionController $institutionController)
    {
        $this->institutionController = $institutionController;
    }

    public function getAlumnus(Request $request): JsonResponse
    {
        try {
            $slug = $request->get('slug');

            if(CraydelHelperFunctions::isNull($slug)){
                throw new Exception("Missing alumnus name slug");
            }

            $alumnus = InstitutionAlumnus::with(['review'])
                ->where('alumni_name_slug', $slug)
                ->first();

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.alumni_shown'),
                $alumnus
            ));
        } catch (Exception $exception) {
            $this->institutionController->logException($exception);

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
