<?php
namespace App\Jobs;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\Traits\CanLog;
use Exception;

class UploadInstitutionAccreditationImageToCDNJob extends Job
{
    use CanLog;

    /**
     * @var $accreditation_id
    */
    public $accreditation_id;

    /**
     * @var $temp_image_path
    */
    public $temp_image_path;

    /**
     * Create a new job instance.
     *
     * @param int|null $accreditation_id
     * @param string|null $temp_image_path
     */
    public function __construct(?int $accreditation_id, ?string $temp_image_path)
    {
        $this->accreditation_id = $accreditation_id;
        $this->temp_image_path = $temp_image_path;
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
            if(empty($this->accreditation_id)){
                throw new Exception("Missing institution accreditation ID while trying to upload the institution accreditation image to the CDN");
            }

            if(!empty($this->temp_image_path)){
                InstitutionController::processAccreditationImage(
                    $this->accreditation_id,
                    $this->temp_image_path
                );
            }
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
