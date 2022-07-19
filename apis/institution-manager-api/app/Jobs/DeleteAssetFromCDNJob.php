<?php
namespace App\Jobs;

use App\Http\Controllers\Administration\InstitutionController;
use Exception;

class DeleteAssetFromCDNJob extends Job
{
    /**
     * @var $asset_url
    */
    protected $asset_url;

    /**
     * Create a new job instance.
     *
     * @param string|null $asset_url
     */
    public function __construct(?string $asset_url)
    {
        $this->asset_url = $asset_url;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        if(!empty($this->asset_url)){
            InstitutionController::deleteInstitutionLogoImages(
                $this->asset_url
            );
        }
    }
}
