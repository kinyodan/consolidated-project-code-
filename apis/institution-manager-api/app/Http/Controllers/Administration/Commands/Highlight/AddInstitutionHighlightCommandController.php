<?php
namespace App\Http\Controllers\Administration\Commands\Highlight;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\InstitutionHighlight;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddInstitutionHighlightCommandController
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
     * Add highlight
     *
     * @param Request $request
     * @param string|null $institution_code
     * @return JsonResponse
     */
    public function add(Request $request, ?string $institution_code): JsonResponse
    {
        try{
            $validated = ValidateInstitutionHighlightCommandController::validate(
                $request,
                $institution_code,
                ValidateInstitutionHighlightCommandController::VALIDATE_NEW
            );

            if(!$validated->status){
                throw new Exception($validated->message);
            }

            $saved = DB::table((new InstitutionHighlight())->getTable())
                ->insertOrIgnore($validated->data);

            if($saved){
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.highlight_saved')
                ));
            }else{
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.highlight.error_while_saving_highlight')
                ));
            }
        }catch (Exception $exception){
            $this->institutionController->logException($exception);

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
