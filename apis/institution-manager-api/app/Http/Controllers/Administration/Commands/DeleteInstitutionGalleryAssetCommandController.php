<?php
namespace App\Http\Controllers\Administration\Commands;

use App\Events\InstitutionGalleryAssetDeletedEvent;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Institution;
use App\Models\InstitutionGallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Exception;

class DeleteInstitutionGalleryAssetCommandController
{
    /**
     * @var InstitutionController
     */
    protected $institution_controller;

    /**
     * Constructor
     * @param InstitutionController $institution_controller
     */
    public function __construct(InstitutionController $institution_controller){
        $this->institution_controller = $institution_controller;
    }

    /**
     * Delete the institution asset
    */
    public function delete(Request $request, ?string $institution_code, ?string $asset_code): JsonResponse
    {
        try{
            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);
            $asset_code = CraydelHelperFunctions::toCleanString($asset_code);

            if(empty($institution_code)){
                throw new Exception(LanguageTranslationHelper::translate('institutions.errors.missing_institution_code'));
            }

            if(!DB::table((new Institution())->getTable())->where('institution_code', $institution_code)->exists()){
                throw new Exception(LanguageTranslationHelper::translate('institutions.errors.invalid_institution_code'));
            }

            if(empty($asset_code)){
                throw new Exception(LanguageTranslationHelper::translate('institutions.errors.gallery.missing_asset_code'));
            }

            if(!DB::table((new InstitutionGallery())->getTable())->where('asset_code', $asset_code)->exists()){
                throw new Exception(LanguageTranslationHelper::translate('institutions.errors.gallery.invalid_asset_code'));
            }

            $asset = DB::table((new InstitutionGallery())->getTable())
                ->where('institution_code', $institution_code)
                ->where('asset_code', $asset_code)
                ->first([
                    'institution_code',
                    'type',
                    'small_image_url',
                    'medium_image_url',
                    'big_image_url'
                ]);

            $delete = null;

            DB::transaction(function () use($institution_code, $asset_code, &$delete){
                $delete = DB::table((new InstitutionGallery())->getTable())
                    ->where('institution_code', $institution_code)
                    ->where('asset_code', $asset_code)
                    ->delete();
            });

            if($delete == true){
                event(new InstitutionGalleryAssetDeletedEvent((object)$asset));

                return $this->institution_controller->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.gallery_item_deleted')
                ));
            }else{
                throw new \Exception( LanguageTranslationHelper::translate('institutions.errors.gallery.error_deleting_gallery_asset'));
            }
        }catch (\Exception $exception){
            $this->institution_controller->logException($exception);

            return $this->institution_controller->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
