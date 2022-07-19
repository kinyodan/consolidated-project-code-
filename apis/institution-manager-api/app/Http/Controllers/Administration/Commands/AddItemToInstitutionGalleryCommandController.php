<?php
namespace App\Http\Controllers\Administration\Commands;

use App\Events\InstitutionGalleryAssetCreatedEvent;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Institution;
use App\Models\InstitutionGallery;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Respect\Validation\Validator as v;

class AddItemToInstitutionGalleryCommandController
{
    use CanLog;

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
     * Validate the asset details
     *
     * @param $request
     * @param string|null $institution_code
     *
     * @return CraydelInternalResponseHelper
     */
    protected function validate(Request $request, ?string $institution_code): CraydelInternalResponseHelper
    {
        try{
            $user = GetLoggedIUserHelper::getUser($request);
            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);

            if(empty($institution_code)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.invalid_institution_code')
                );
            }

            if(!DB::table((new Institution())->getTable())->where('institution_code', $institution_code)->exists()){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.invalid_institution_code')
                );
            }

            $asset_name = $request->input('asset_name');
            $asset_position = $request->input('asset_position');
            $asset_caption = $request->input('asset_caption');
            $is_featured = $request->input('is_featured', 0);
            $type = $request->input('type');
            $asset_url = $request->input('asset_url');

            if(!v::stringVal()->notEmpty()->validate($asset_name)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.gallery.missing_asset_name')
                );
            }

            $asset_name_duplicate = DB::table((new InstitutionGallery())->getTable())
                ->where('institution_code', $institution_code)
                ->where('asset_name_slug', CraydelHelperFunctions::slugifyString($asset_name))
                ->exists();

            if($asset_name_duplicate){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.gallery.duplicate_asset_name')
                );
            }

            if(!v::optional(v::intVal())->validate($asset_position)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.gallery.invalid_asset_position')
                );
            }

            if(!v::stringVal()->notEmpty()->validate($asset_caption)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.gallery.invalid_asset_caption')
                );
            }

            if(!v::intVal()->min(0)->max(1)->validate($is_featured)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.gallery.invalid_is_featured_value')
                );
            }

            if(!v::stringVal()->notEmpty()->validate($type)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.gallery.missing_asset_type')
                );
            }

            if(!in_array($type, InstitutionController::$institution_asset_types)){
                throw new Exception(
                    sprintf(
                        LanguageTranslationHelper::translate('institutions.errors.gallery.invalid_asset_type'),
                        implode(',', InstitutionController::$institution_asset_types)
                    )
                );
            }

            if($type == InstitutionController::GALLERY_IMAGE_TYPE && !$request->file('uploaded_asset_image')){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.gallery.asset_image_not_uploaded')
                );
            }else{
                if($type == InstitutionController::Gallery_VIDEO_LINK_TYPE && !v::stringVal()->notEmpty()->validate($asset_url)){
                    throw new Exception(
                        LanguageTranslationHelper::translate('institutions.errors.gallery.missing_asset_url')
                    );
                }elseif ($type == InstitutionController::Gallery_VIDEO_LINK_TYPE && !v::url()->notEmpty()->validate($asset_url)){
                    throw new Exception(
                        LanguageTranslationHelper::translate('institutions.errors.gallery.invalid_asset_url')
                    );
                }
            }

            $file_upload_path = null;

            if($request->file('uploaded_asset_image')) {
                $image_path = $request->file('uploaded_asset_image');
                $file_mime_type = $image_path->getClientMimeType();
                $file_mime_type = CraydelHelperFunctions::toCleanString(strtolower($file_mime_type));

                if (!in_array($file_mime_type, $this->institutionController->allowedImageMimeTypes)) {
                    return (new CraydelInternalResponseHelper(
                        false,
                        sprintf(
                            LanguageTranslationHelper::translate('institutions.errors.invalid_logo_file_type'),
                            implode('', $this->institutionController->allowedImageMimeTypes)
                        )
                    ));
                }

                $institution_logo_size = $image_path->getSize();
                $file_size_in_mbs = CraydelHelperFunctions::convertBytesToMBs($institution_logo_size);

                if(isset($file_size_in_mbs)){
                    $maximum_allowed = config('craydle.security.maximum_uploaded_file_size', 10);

                    if(floatval($file_size_in_mbs) > $maximum_allowed){
                        return (new CraydelInternalResponseHelper(
                            false,
                            sprintf(
                                LanguageTranslationHelper::translate('institutions.errors.gallery.unsupported_file_type'),
                                $file_size_in_mbs.'MBs'
                            )
                        ));
                    }
                }else{
                    return (new CraydelInternalResponseHelper(
                        false,
                        LanguageTranslationHelper::translate('institutions.errors.gallery.unable_to_get_the_image_size')
                    ));
                }

                $staged_files_path = storage_path().DIRECTORY_SEPARATOR.'staged-images'.DIRECTORY_SEPARATOR.'institutions-gallery'.DIRECTORY_SEPARATOR;
                $gallery_final_image = md5(CraydelHelperFunctions::makeRandomString(20)).'.'.$image_path->getClientOriginalExtension();
                $image_path->move($staged_files_path, $gallery_final_image);
                $file_upload_path = $staged_files_path.$gallery_final_image;

                if(file_exists($file_upload_path)){
                    $manager = new ImageManager();
                    $image = $manager->make($file_upload_path)->orientate()->save($file_upload_path);

                    $image_width = $image->getWidth();
                    $image_height = $image->getHeight();
                    $minimum_width = config('craydle.minimum_width');
                    $minimum_height = config('craydle.minimum_height');

                    if($image_width < $minimum_width){
                        @unlink($file_upload_path);
                        return (new CraydelInternalResponseHelper(
                            false,
                            sprintf(
                                LanguageTranslationHelper::translate('institutions.errors.gallery.image_should_below_minimum_width'),
                                $minimum_width
                            )
                        ));
                    }else{
                        if($image_height < $minimum_height){
                            @unlink($file_upload_path);
                            return (new CraydelInternalResponseHelper(
                                false,
                                sprintf(
                                    LanguageTranslationHelper::translate('institutions.errors.gallery.image_should_below_minimum_height'),
                                    $minimum_height
                                )
                            ));
                        }
                    }

                    $minimum_aspect_ration_multiplier = config('craydle.logos.allowed_aspect_ration_minimum_multiplier');
                    $maximum_aspect_ration_multiplier = config('craydle.logos.allowed_aspect_ration_maximum_multiplier');
                    $aspect_ration_multiplier = CraydelHelperFunctions::imageAspectRationMultiplier($image_width,$image_height);

                    if(!(($aspect_ration_multiplier >= $minimum_aspect_ration_multiplier) && ($aspect_ration_multiplier <= $maximum_aspect_ration_multiplier))){
                        @unlink($file_upload_path);

                        return (new CraydelInternalResponseHelper(
                            false,
                            LanguageTranslationHelper::translate('institutions.errors.gallery.image_has_an_invalid_aspect_ration')
                        ));
                    }
                }

                $this->institutionController->stagedFilePath = $file_upload_path;
            }

            $asset_code = CraydelHelperFunctions::makeRandomString(10, 'AA');

            return $this->institutionController->internalResponse(new CraydelInternalResponseHelper(
                true,
                "Validated.",
                (object)[
                    'staged_file_path' => $file_upload_path,
                    'payload' => [
                        'institution_code' => $institution_code,
                        'asset_name_slug' => CraydelHelperFunctions::slugifyString($asset_name),
                        'asset_name' => CraydelHelperFunctions::toCleanString($asset_name),
                        'asset_description' => CraydelHelperFunctions::toCleanString($asset_caption),
                        'asset_position' => CraydelHelperFunctions::toNumbers($asset_position),
                        'is_featured' => intval($is_featured) == 1 ? 1 : 0,
                        'asset_code' => $asset_code,
                        'type' => $type,
                        'temp_image_path' => $file_upload_path,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'update_at' => Carbon::now()->toDateTimeString(),
                        'created_by' => isset($user->email) && !empty($user->email) ? $user->email : null,
                        'video_url' => $asset_url
                    ],
                    'asset_code' => $asset_code
                ]
            ));
        }catch (Exception $exception){
            $this->institutionController->logException($exception);

            return $this->institutionController->internalResponse(new CraydelInternalResponseHelper(false, $exception->getMessage()));
        }
    }

    /**
     * Add items to the institution gallery
     *
     * @param Request $request
     * @param string|null $institution_code
     *
     * @return JsonResponse
     */
    public function add(Request $request, ?string $institution_code): JsonResponse
    {
        try{
            $validation = $this->validate($request, $institution_code);

            if(!$validation->status){
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(false, $validation->message));
            }

            if(!isset($validation->data->payload) || !is_array($validation->data->payload)){
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.gallery.error_saving_gallery_asset')
                ));
            }

            $asset = DB::table((new InstitutionGallery())->getTable())
                ->insertOrIgnore($validation->data->payload);

            if($asset){
                event(new InstitutionGalleryAssetCreatedEvent(
                    isset($validation->data->asset_code) && !empty($validation->data->asset_code) ? $validation->data->asset_code : null,
                    isset($validation->data->payload['temp_image_path']) && !empty($validation->data->payload['temp_image_path']) ? $validation->data->payload['temp_image_path'] : null,
                    isset($validation->data->payload['type']) && !empty($validation->data->payload['type']) ? $validation->data->payload['type'] : null,
                    isset($validation->data->payload['institution_code']) && !empty($validation->data->payload['institution_code']) ? $validation->data->payload['institution_code'] : null,
                ));

                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.gallery_item_created')
                ));
            }else{
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.gallery.error_saving_gallery_asset')
                ));
            }
        }catch (Exception $exception){
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(false, $exception->getMessage()));
        }
    }
}
