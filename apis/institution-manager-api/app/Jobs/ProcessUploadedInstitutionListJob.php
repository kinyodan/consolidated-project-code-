<?php
namespace App\Jobs;

use App\Http\Controllers\Administration\Commands\BulkUploadInstitutionsCommandController;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\Traits\CanLog;
use App\Models\InstitutionUpload;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessUploadedInstitutionListJob extends Job
{
    use CanLog;

    /**
     * Constructor
    */
    public function __construct()
    {

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
            $upload_batches = DB::table((new InstitutionUpload())->getTable())
                ->where('is_processed', 0)
                ->get([
                    'id', 'file_data'
                ]);

            if (!empty($upload_batches)) {
                foreach ($upload_batches as $batch) {
                    (new BulkUploadInstitutionsCommandController((new InstitutionController())))
                        ->process(
                            $batch->id,
                            json_decode($batch->file_data)
                        );
                }
            } else {
                throw new Exception('No new batch to processes!');
            }
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
