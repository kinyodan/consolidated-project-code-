<?php
namespace App\Jobs;

use App\Http\Controllers\Administration\InstitutionController;
use Exception;

class DeleteImagesFromTheCDN extends Job
{
    /**
     * @var $images
    */
    public $images;

    /**
     * Create a new job instance.
     *
     * @param array|null $images
     */
    public function __construct(?array $images)
    {
        $this->images = $images;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        if(!empty($this->images)){
            InstitutionController::deleteFromCDN(
                $this->images
            );
        }
    }
}
