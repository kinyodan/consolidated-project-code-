<?php
namespace App\Http\Controllers\Administration\Commands\Highlight;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\InstitutionHighlight;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteInstitutionHighlightCommandController
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
     * Delete the highlight
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $highlight_id
     * @return JsonResponse
     */
    public function delete(Request $request, ?string $institution_code, ?int $highlight_id): JsonResponse
    {
        try {
            $validated = ValidateInstitutionHighlightCommandController::validate(
                $request,
                $institution_code,
                ValidateInstitutionHighlightCommandController::VALIDATE_DELETE,
                $highlight_id
            );

            if(!$validated->status){
                throw new Exception($validated->message);
            }

            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);
            $highlight_id = CraydelHelperFunctions::toNumbers($highlight_id);

            $delete = DB::table((new InstitutionHighlight())->getTable())
                ->where('id', $highlight_id)
                ->where('institution_code', $institution_code)
                ->delete();

            if($delete){
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.highlight_deleted')
                ));
            }else{
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.highlight.error_while_deleting_the_highlight')
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
