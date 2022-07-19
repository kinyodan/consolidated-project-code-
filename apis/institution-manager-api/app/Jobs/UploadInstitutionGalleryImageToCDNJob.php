<?php
namespace App\Jobs;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Institution;
use App\Models\InstitutionGallery;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UploadInstitutionGalleryImageToCDNJob extends Job
{
    use CanLog;

    /**
     * @var $asset_code
    */
    protected $asset_code;

    /**
     * @var $stage_image_path
    */
    protected $stage_image_path;

    /**
     * Create a new job instance.
     *
     * @param string|null $asset_code
     * @param string|null $stage_image_path
     */
    public function __construct(?string $asset_code, ?string $stage_image_path)
    {
        $this->asset_code = $asset_code;
        $this->stage_image_path = $stage_image_path;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        try{
            if(empty($this->asset_code)){
                throw new Exception("Missing asset code while trying to upload the asset image to the CDN");
            }

            if(!empty($this->stage_image_path)){
                InstitutionController::processGalleryImage(
                    $this->asset_code,
                    $this->stage_image_path
                );
            }
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
