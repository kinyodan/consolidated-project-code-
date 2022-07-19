<?php
namespace App\Http\Controllers\Administration\Queries\Alumni;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\InstitutionAlumnus;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetInstitutionAlumnusDetailsQueryController
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
     * Get alumnus details
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $alumnus_id
     * @return JsonResponse
     */
    public function alumnus(Request $request, ?string $institution_code, ?int $alumnus_id): JsonResponse
    {
        try{
            $alumnus = InstitutionAlumnus::where('institution_code', $institution_code)
                ->where('id', $alumnus_id)
                ->first();

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.alumni_shown'),
                (object)[
                    'alumnus' => $alumnus
                ]
            ));
        }catch (Exception $exception){
           $this->institutionController->logException($exception);

           return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
               false,
               $exception->getMessage()
           ));
        }
    }
}
