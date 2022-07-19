<?php
namespace App\Jobs;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\Traits\CanLog;
use Exception;

class UploadInstitutionAlumnusImageToCDNJob extends Job
{
    use CanLog;

    /**
     * @var $alumnus_id
    */
    public $alumnus_id;

    /**
     * @var $temp_image_path
    */
    public $temp_image_path;

    /**
     * Create a new job instance.
     *
     * @param int|null $alumnus_id
     * @param string|null $temp_image_path
     */
    public function __construct(?int $alumnus_id, ?string $temp_image_path)
    {
        $this->alumnus_id = $alumnus_id;
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
            if(empty($this->alumnus_id)){
                throw new Exception("Missing institution alumnus ID while trying to upload the institution alumnus image to the CDN");
            }

            if(!empty($this->temp_image_path)){
                InstitutionController::processAlumnusImage(
                    $this->alumnus_id,
                    $this->temp_image_path
                );
            }
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
