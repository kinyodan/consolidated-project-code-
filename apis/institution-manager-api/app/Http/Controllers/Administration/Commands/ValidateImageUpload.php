<?php
namespace App\Http\Controllers\Administration\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use Exception;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;

class ValidateImageUpload
{
    /**
     * Validate image upload
     *
     * @param Request $request
     * @param string $image_parameter_field
     * @param array $allowedImageMimeTypes
     * @return string|null
     *
     * @throws Exception
     */
    public static function validate(Request $request, string $image_parameter_field, array $allowedImageMimeTypes): ?string
    {
        if(!$request->file($image_parameter_field)) {
            throw new Exception(LanguageTranslationHelper::translate('general.errors.invalid_image_parameter_field'));
        }

        $image_path = $request->file($image_parameter_field);
        $file_mime_type = $image_path->getClientMimeType();
        $file_mime_type = CraydelHelperFunctions::toCleanString(strtolower($file_mime_type));

        if (!in_array($file_mime_type, $allowedImageMimeTypes)) {
            throw new Exception(
                sprintf(
                    LanguageTranslationHelper::translate('general.errors.invalid_logo_file_type'),
                    implode('', $allowedImageMimeTypes)
                )
            );
        }

        $file_size_in_mbs = CraydelHelperFunctions::convertBytesToMBs($image_path->getSize());

        if(isset($file_size_in_mbs)){
            $maximum_allowed = config('craydle.security.maximum_uploaded_file_size', 10);

            if(floatval($file_size_in_mbs) > $maximum_allowed){
                throw new Exception(
                    sprintf(
                        LanguageTranslationHelper::translate('general.errors.unsupported_file_type'),
                        $file_size_in_mbs.'MBs'
                    )
                );
            }
        }else{
            throw new Exception(LanguageTranslationHelper::translate('general.errors.unable_to_get_the_image_size'));
        }

        $staged_files_path = storage_path('app').DIRECTORY_SEPARATOR;
        $save_file_path = $image_path->store('staged-images');
        $file_upload_path = $staged_files_path.$save_file_path;

        if(file_exists($file_upload_path)){
            $manager = new ImageManager();
            $image = $manager->make($file_upload_path)->orientate()->save();
            $image_width = $image->getWidth();
            $image_height = $image->getHeight();
            $minimum_width = config('craydle.minimum_width');
            $minimum_height = config('craydle.minimum_height');

            if($image_width < $minimum_width){
                @unlink($file_upload_path);

                throw new Exception(
                    sprintf(
                        LanguageTranslationHelper::translate('general.errors.image_should_below_minimum_width'),
                        $minimum_width
                    )
                );
            }else{
                if($image_height < $minimum_height){
                    @unlink($file_upload_path);

                    throw new Exception(
                        sprintf(
                            LanguageTranslationHelper::translate('general.errors.image_should_below_minimum_width'),
                            $minimum_height
                        )
                    );
                }
            }

            $minimum_aspect_ration_multiplier = config('craydle.logos.allowed_aspect_ration_minimum_multiplier');
            $maximum_aspect_ration_multiplier = config('craydle.logos.allowed_aspect_ration_maximum_multiplier');
            $aspect_ration_multiplier = CraydelHelperFunctions::imageAspectRationMultiplier($image_width,$image_height);

            if(!(($aspect_ration_multiplier >= $minimum_aspect_ration_multiplier) && ($aspect_ration_multiplier <= $maximum_aspect_ration_multiplier))){
                @unlink($file_upload_path);

                throw new Exception(LanguageTranslationHelper::translate('general.errors.image_has_an_invalid_aspect_ration'));
            }
        }

        return $file_upload_path;
    }
}
