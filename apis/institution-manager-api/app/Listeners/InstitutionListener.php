<?php
namespace App\Listeners;

use App\Events\InstitutionCreatedEvent;
use App\Events\InstitutionLogoImageUploadedEvent;
use App\Events\InstitutionPublishedEvent;
use App\Events\InstitutionUpdatedEvent;
use App\Http\Controllers\PublicView\Queries\SingleInstitutionQueryController;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\PushInstitutionDataToSearchEngineJob;
use App\Jobs\UploadInstitutionLogoToCDNJob;
use Exception;
use Illuminate\Events\Dispatcher;

class InstitutionListener
{
    use CanLog;

    /**
     * Institution has been created
     *
     * @param InstitutionCreatedEvent $event
     *
     * @return void
     */
    public function onInstitutionCreated(InstitutionCreatedEvent $event){
        try{
            $institution_code = $event->institution_code;

            if(!isset($institution_code) || empty($institution_code)){
                throw new Exception('Invalid institution code.');
            }

            dispatch(new UploadInstitutionLogoToCDNJob(
                $institution_code
            ))->onQueue('upload_images_to_cdn');
        }catch (Exception $exception){
            $this->logException($exception);
        }
    }

    /**
     * Course has been updated
     *
     * @param InstitutionUpdatedEvent $event
     *
     * @return void
     */
    public function onInstitutionUpdated(InstitutionUpdatedEvent $event){
        try{
            $institution_code = $event->institution_code;

            if(!isset($institution_code) || empty($institution_code)){
                throw new Exception('Invalid institution code.');
            }

            dispatch(new PushInstitutionDataToSearchEngineJob(
                $institution_code
            ))->onQueue('push_institution_to_search_engine');

            dispatch(new UploadInstitutionLogoToCDNJob(
                $institution_code
            ))->onQueue('upload_images_to_cdn');
        }catch (Exception $exception){
            $this->logException($exception);
        }
    }

    /**
     * Course has been updated
     *
     * @param InstitutionLogoImageUploadedEvent $event
     *
     * @return void
     */
    public function onInstitutionLogoImageUploaded(InstitutionLogoImageUploadedEvent $event){
        try{
            $institution_code = $event->institution_code;

            if(!isset($institution_code) || empty($institution_code)){
                throw new Exception('Invalid institution code.');
            }

            dispatch(new PushInstitutionDataToSearchEngineJob(
                $institution_code
            ))->onQueue('push_institution_to_search_engine');
        }catch (Exception $exception){
            $this->logException($exception);
        }
    }

    /**
     * Institution published event
     * @param InstitutionPublishedEvent $event
     */
    public function onInstitutionPublishedEvent(InstitutionPublishedEvent $event){
        try{
            $institution_code = $event->institution_code;

            if(!isset($institution_code) || empty($institution_code)){
                throw new Exception('Invalid institution code.');
            }

            $this->logMessage("Institution has been published to Algolia: ".$institution_code);

            SingleInstitutionQueryController::cache(
                $institution_code,
                SingleInstitutionQueryController::UPDATE_SINGLE_INSTITUTION_CACHE
            );
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
            InstitutionCreatedEvent::class,
            [InstitutionListener::class, 'onInstitutionCreated']
        );

        $events->listen(
            InstitutionUpdatedEvent::class,
            [InstitutionListener::class, 'onInstitutionUpdated']
        );

        $events->listen(
            InstitutionLogoImageUploadedEvent::class,
            [InstitutionListener::class, 'onInstitutionLogoImageUploaded']
        );

        $events->listen(
            InstitutionPublishedEvent::class,
            [InstitutionListener::class, 'onInstitutionPublishedEvent']
        );
    }
}
