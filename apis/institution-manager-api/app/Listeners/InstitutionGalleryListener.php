<?php
namespace App\Listeners;

use App\Events\InstitutionGalleryAssetCreatedEvent;
use App\Events\InstitutionGalleryAssetDeletedEvent;
use App\Events\InstitutionGalleryImageUploadedEvent;
use App\Events\InstitutionUpdatedEvent;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\PublicView\Queries\SingleInstitutionQueryController;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\DeleteAssetFromCDNJob;
use App\Jobs\UploadInstitutionGalleryImageToCDNJob;
use App\Models\InstitutionGallery;
use Exception;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\DB;

class InstitutionGalleryListener
{
    use CanLog;

    /**
     * Institution gallery asset has been created
     *
     * @param InstitutionGalleryAssetCreatedEvent $event
     *
     * @return void
     */
    public function onInstitutionGalleryAssetCreated(InstitutionGalleryAssetCreatedEvent $event){
        try{
            $asset_code = $event->asset_code;
            $asset_image_temp_path = $event->asset_image_temp_path;
            $asset_type = $event->type;

            if(!isset($asset_code) || empty($asset_code)){
                throw new Exception('Invalid institution gallery asset code.');
            }

            if(!isset($asset_type) || empty($asset_type)){
                throw new Exception('Invalid institution asset type.');
            }

            if($asset_type == InstitutionController::GALLERY_IMAGE_TYPE){
                dispatch(new UploadInstitutionGalleryImageToCDNJob(
                    $asset_code,
                    $asset_image_temp_path
                ))->onQueue('upload_images_to_cdn');
            }elseif ($asset_type == InstitutionController::Gallery_VIDEO_LINK_TYPE){
                SingleInstitutionQueryController::cache($event->institution_code);
            }
        }catch (Exception $exception){
            $this->logException($exception);
        }
    }

    /**
     * Institution gallery asset has been created
     *
     * @param InstitutionGalleryImageUploadedEvent $event
     *
     * @return void
     */
    public function onInstitutionGalleryImageUploaded(InstitutionGalleryImageUploadedEvent $event){
        try{
            $asset_code = $event->asset_code;

            if(!isset($asset_code) || empty($asset_code)){
                throw new Exception('Invalid institution gallery asset code.');
            }

            $institution_code = DB::table((new InstitutionGallery())->getTable())
                ->where('asset_code', $asset_code)
                ->value('institution_code');

            $this->logMessage("Asset uploaded for institution code: ".$institution_code);

            SingleInstitutionQueryController::cache(
                $institution_code,
                SingleInstitutionQueryController::UPDATE_SINGLE_INSTITUTION_CACHE
            );

            event(new InstitutionUpdatedEvent($institution_code));
        }catch (Exception $exception){
            $this->logException($exception);
        }
    }

    /**
     * Institution gallery asset has been deleted
     *
     * @param InstitutionGalleryAssetDeletedEvent $event
     *
     * @return void
     */
    public function onInstitutionGalleryAssetDeleted(InstitutionGalleryAssetDeletedEvent $event){
        try{
            $asset = $event->asset;

            if(!isset($asset) || !is_object($asset)){
                throw new Exception('Invalid institution gallery asset object.');
            }

            $institution_code = isset($asset->institution_code) && !empty($asset->institution_code) ? trim($asset->institution_code) : null;
            $type = isset($asset->type) && !empty($asset->type) ? trim($asset->type) : null;

            if(!empty($type) && $type == 'Image'){
                $small_image_url = isset($asset->small_image_url) && !empty($asset->small_image_url) ? trim($asset->small_image_url) : null;
                $medium_image_url = isset($asset->medium_image_url) && !empty($asset->medium_image_url) ? trim($asset->medium_image_url) : null;
                $big_image_url = isset($asset->big_image_url) && !empty($asset->big_image_url) ? trim($asset->big_image_url) : null;

                dispatch((new DeleteAssetFromCDNJob($small_image_url)))->onQueue('upload_images_to_cdn');
                dispatch((new DeleteAssetFromCDNJob($medium_image_url)))->onQueue('upload_images_to_cdn');
                dispatch((new DeleteAssetFromCDNJob($big_image_url)))->onQueue('upload_images_to_cdn');
            }

            SingleInstitutionQueryController::cache(
                $institution_code,
                SingleInstitutionQueryController::UPDATE_SINGLE_INSTITUTION_CACHE
            );

            event(new InstitutionUpdatedEvent($institution_code));
        }catch (Exception $exception){
            $this->logException($exception);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            InstitutionGalleryAssetCreatedEvent::class,
            [InstitutionGalleryListener::class, 'onInstitutionGalleryAssetCreated']
        );

        $events->listen(
            InstitutionGalleryImageUploadedEvent::class,
            [InstitutionGalleryListener::class, 'onInstitutionGalleryImageUploaded']
        );

        $events->listen(
            InstitutionGalleryAssetDeletedEvent::class,
            [InstitutionGalleryListener::class, 'onInstitutionGalleryAssetDeleted']
        );
    }
}
