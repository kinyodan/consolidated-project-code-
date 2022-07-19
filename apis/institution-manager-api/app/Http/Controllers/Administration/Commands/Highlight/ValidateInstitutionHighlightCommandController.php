<?php
namespace App\Http\Controllers\Administration\Commands\Highlight;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Institution;
use App\Models\InstitutionHighlight;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator as v;

class ValidateInstitutionHighlightCommandController
{
    const VALIDATE_NEW = 'NEW';
    const VALIDATE_UPDATE = 'UPDATE';
    const VALIDATE_DELETE = 'DELETE';

    /**
     * Validate the institution highlight
     * @throws Exception
     */
    public static function validate(Request $request, string $institution_code, string $validation_mode, ?int $highlight_id = null): CraydelInternalResponseHelper
    {
        $institution_code = CraydelHelperFunctions::toCleanString($institution_code);
        $highlight_id = CraydelHelperFunctions::toNumbers($highlight_id);

        if(!v::stringVal()->notEmpty()->validate($institution_code)){
            throw new Exception(
                LanguageTranslationHelper::translate('institutions.errors.missing_institution_code')
            );
        }

        if(!DB::table((new Institution())->getTable())->where('institution_code', $institution_code)->exists()){
            throw new Exception(
                LanguageTranslationHelper::translate('institutions.errors.invalid_institution_code')
            );
        }

        if(!v::stringVal()->notEmpty()->validate($validation_mode)){
            throw new Exception(
                LanguageTranslationHelper::translate('institutions.errors.highlight.invalidate_highlight_validation_mode')
            );
        }

        if($validation_mode == self::VALIDATE_UPDATE){
            if(!v::intVal()->notEmpty()->validate($highlight_id)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.highlight.missing_key_highlight_id')
                );
            }
        }

        if($validation_mode == self::VALIDATE_NEW){
            return self::_new($request, $institution_code);
        }elseif ($validation_mode == self::VALIDATE_UPDATE){
            return self::_update($request, $institution_code, $highlight_id);
        }elseif ($validation_mode == self::VALIDATE_DELETE){
            if(!DB::table((new InstitutionHighlight())->getTable())->where('id', $highlight_id)->where('institution_code', $institution_code)->exists()){
                throw new Exception(LanguageTranslationHelper::translate('institutions.errors.highlight.invalid_key_highlight_id'));
            }

            return new CraydelInternalResponseHelper(
                true,
                'Validated'
            );
        }

        throw new Exception(
            LanguageTranslationHelper::translate('institutions.errors.highlight.invalidate_highlight_validation_mode')
        );
    }

    /**
     * Validate new highlight
     * @throws Exception
     */
    protected static function _new(Request $request, string $institution_code): CraydelInternalResponseHelper
    {
        $key_highlight = CraydelHelperFunctions::toCleanString($request->input('key_highlight'));
        $key_highlight_description = CraydelHelperFunctions::toCleanString($request->input('key_highlight_description'));
        $display_order = CraydelHelperFunctions::toNumbers($request->input('display_order'));
        $user = GetLoggedIUserHelper::getUser($request);

        if(!v::stringVal()->notEmpty()->validate($key_highlight)){
            throw new Exception(LanguageTranslationHelper::translate('institutions.errors.highlight.invalid_key_highlight'));
        }

        if(!v::stringVal()->notEmpty()->validate($key_highlight_description)){
            throw new Exception(LanguageTranslationHelper::translate('institutions.errors.highlight.invalid_key_highlight_description'));
        }

        if(!v::intVal()->notEmpty()->validate($display_order)){
            throw new Exception(LanguageTranslationHelper::translate('institutions.errors.highlight.invalid_display_order'));
        }

        if(DB::table((new InstitutionHighlight())->getTable())->where('institution_code', $institution_code)->where('key_highlight', $key_highlight)->exists()){
            throw new Exception(LanguageTranslationHelper::translate('institutions.errors.highlight.duplicate_key_highlight'));
        }

        return new CraydelInternalResponseHelper(
            true,
            'Validated', [
                'institution_code' => $institution_code,
                'key_highlight' => $key_highlight,
                'key_highlight_description' => $key_highlight_description,
                'display_order' => !empty($display_order) ? $display_order : 0,
                'created_by' => isset($user->email) && !empty($user->email) ? $user->email : null,
                'created_at' => Carbon::now()->toDateTime()
            ]
        );
    }

    /**
     * Validate highlight update
     * @throws Exception
     */
    protected static function _update(Request $request, string $institution_code, int $highlight_id): CraydelInternalResponseHelper
    {
        $key_highlight = CraydelHelperFunctions::toCleanString($request->input('key_highlight'));
        $key_highlight_description = CraydelHelperFunctions::toCleanString($request->input('key_highlight_description'));
        $display_order = CraydelHelperFunctions::toNumbers($request->input('display_order'));
        $user = GetLoggedIUserHelper::getUser($request);

        if(!v::stringVal()->notEmpty()->validate($key_highlight)){
            throw new Exception(LanguageTranslationHelper::translate('institutions.errors.highlight.invalid_key_highlight'));
        }

        if(!v::stringVal()->notEmpty()->validate($key_highlight_description)){
            throw new Exception(LanguageTranslationHelper::translate('institutions.errors.highlight.invalid_key_highlight_description'));
        }

        if(!v::intVal()->notEmpty()->validate($display_order)){
            throw new Exception(LanguageTranslationHelper::translate('institutions.errors.highlight.invalid_display_order'));
        }

        if(!DB::table((new InstitutionHighlight())->getTable())->where('id', $highlight_id)->where('institution_code', $institution_code)->exists()){
            throw new Exception(LanguageTranslationHelper::translate('institutions.errors.highlight.invalid_key_highlight_id'));
        }

        if(DB::table((new InstitutionHighlight())->getTable())->where('id', '!=', $highlight_id)->where('institution_code', $institution_code)->where('key_highlight', $key_highlight)->exists()){
            throw new Exception(LanguageTranslationHelper::translate('institutions.errors.highlight.duplicate_key_highlight'));
        }

        return new CraydelInternalResponseHelper(
            true,
            'Validated', [
                'institution_code' => $institution_code,
                'key_highlight' => $key_highlight,
                'key_highlight_description' => $key_highlight_description,
                'display_order' => !empty($display_order) ? $display_order : 0,
                'updated_by' => isset($user->email) && !empty($user->email) ? $user->email : null,
                'updated_at' => Carbon::now()->toDateTime()
            ]
        );
    }
}
