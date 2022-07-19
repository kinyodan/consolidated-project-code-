<?php
namespace App\Jobs;

use App\Http\Controllers\Administration\InstitutionController;
use Exception;

class DeleteInstitutionLogoFromCDNJob extends Job
{
    /**
     * @var $institution_code
    */
    protected $institution_code;

    /**
     * Create a new job instance.
     *
     * @param ?string $institution_code
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
            InstitutionController::deleteInstitutionLogoImages(
                $this->institution_code
            );
        }
    }
}
