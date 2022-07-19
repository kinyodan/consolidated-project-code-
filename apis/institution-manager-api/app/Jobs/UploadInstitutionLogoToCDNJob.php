<?php
namespace App\Jobs;

use App\Http\Controllers\Administration\InstitutionController;
use App\Models\Institution;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UploadInstitutionLogoToCDNJob extends Job
{
    /**
     * @var $institution_code
    */
    protected $institution_code;

    /**
     * @var $stageLogoFilePath
    */
    protected $stageLogoFilePath;

    /**
     * Create a new job instance.
     *
     * @param string $institution_code
     *
     */
    public function __construct(string $institution_code)
    {
        $this->institution_code = $institution_code;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        if(!empty($this->institution_code)){
            $institution = DB::table((new Institution())->getTable())
                ->where('institution_code', trim($this->institution_code))
                ->first([
                    'temp_logo_path'
                ]);

            if(isset($institution->temp_logo_path) && !empty($institution->temp_logo_path)){
                InstitutionController::processLogoImage(
                    $this->institution_code,
                    $institution->temp_logo_path
                );
            }
        }
    }
}
