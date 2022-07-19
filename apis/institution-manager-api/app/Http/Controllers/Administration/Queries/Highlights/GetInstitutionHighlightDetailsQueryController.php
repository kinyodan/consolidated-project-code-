<?php
namespace App\Http\Controllers\Administration\Queries\Highlights;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Institution;
use App\Models\InstitutionHighlight;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetInstitutionHighlightDetailsQueryController
{
    /**
     * @var InstitutionController
     */
    protected $institutionController;

    /**
     * Constructor
     * @param InstitutionController $institutionController
     */
    public function __construct(InstitutionController $institutionController)
    {
        $this->institutionController = $institutionController;
    }

    /**
     * Get the highlight details
     */
    public function get(Request $request, ?string $institution_code, ?int $highlight_id): JsonResponse
    {
        try {
            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);
            if (empty($institution_code)) {
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.missing_institution_code')
                );
            }
            if (!DB::table((new Institution())->getTable())->where('institution_code', $institution_code)->exists()) {
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.missing_institution_code')
                );
            }
            if (empty($highlight_id)) {
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.highlight.missing_highlight_id')
                );
            }
            if (!DB::table((new InstitutionHighlight())->getTable())->where('id', $highlight_id)->where('institution_code', $institution_code)->exists()) {
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.highlight.invalid_highlight_id')
                );
            }
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.highlight_shown'),
                (object)[
                    'highlight' => InstitutionHighlight::where('id', $highlight_id)
                        ->where('institution_code', $institution_code)
                        ->first()
                ]
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
