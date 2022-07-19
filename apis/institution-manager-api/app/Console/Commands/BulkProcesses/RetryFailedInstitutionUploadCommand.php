<?php
namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\Administration\Commands\BulkUploadInstitutionsCommandController;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\InstitutionUpload;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RetryFailedInstitutionUploadCommand extends Command
{
    use CanUploadImage;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:retry:institution-upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry institution bulk upload.';

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
            $delayed = InstitutionUpload::where('is_processed', 0)
                ->where('created_at', '<', Carbon::now()->addMinutes(10))
                ->get();

            foreach ($delayed as $item){
                (new BulkUploadInstitutionsCommandController(
                    new InstitutionController()
                ))->process($item->id, json_decode($item->file_data));
            }
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
