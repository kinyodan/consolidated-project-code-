<?php
namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\Administration\Commands\BulkUploadInstitutionsCommandController;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Jobs\UploadInstitutionLogoToCDNJob;
use App\Models\Institution;
use App\Models\InstitutionUpload;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RetryFailedInstitutionImageProcessingCommand extends Command
{
    use CanUploadImage;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:retry:institution-image-processing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry institution image processing.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        try{
            $institutions = DB::table((new Institution())->getTable())
                ->whereNotNull('temp_logo_path')
                ->get([
                    'institution_code'
                ]);

            foreach ($institutions as $institution){
                if(isset($institution->institution_code)){
                    dispatch((new UploadInstitutionLogoToCDNJob($institution->institution_code)))
                        ->onQueue('upload_images_to_cdn');
                }
            }
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
