<?php
namespace App\Http\Controllers\Administration\Queries\Accreditation;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\InstitutionAccreditation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetInstitutionAccreditationDetailsQueryController
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
     * Get accreditation details
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $accreditation_id
     * @return JsonResponse
     */
    public function accreditation(Request $request, ?string $institution_code, ?int $accreditation_id): JsonResponse
    {
        try{
            $accreditation = InstitutionAccreditation::all()
                ->where('id', $accreditation_id)
                ->where('institution_code', $institution_code)
                ->first();

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.accreditation_shown'),
                (object)[
                    'accreditation' => $accreditation
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
