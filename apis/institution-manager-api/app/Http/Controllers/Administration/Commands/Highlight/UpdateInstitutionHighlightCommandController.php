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

class UpdateInstitutionHighlightCommandController
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
     * @param int|null $highlight_id
     * @return JsonResponse
     */
    public function update(Request $request, ?string $institution_code, ?int $highlight_id): JsonResponse
    {
        try{
            $validated = ValidateInstitutionHighlightCommandController::validate(
                $request,
                $institution_code,
                ValidateInstitutionHighlightCommandController::VALIDATE_UPDATE,
                $highlight_id
            );

            if(!$validated->status){
                throw new Exception($validated->message);
            }

            $updated = DB::table((new InstitutionHighlight())->getTable())
                ->where('id', $highlight_id)
                ->where('institution_code', $institution_code)
                ->update($validated->data);

            if($updated){
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.highlight_updated')
                ));
            }else{
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.highlight.error_while_updating_highlight')
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
