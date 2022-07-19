<?php
namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\CraydelTypes\LeadType;
use App\Http\Controllers\CraydelTypes\LeadTypeCollection;
use App\Http\Controllers\Providers\LeadManagement\ILeadProvider;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\Leads;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ProcessStagedLeadsOneByOneCommand extends Command
{
    use CanUploadImage;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:leads:push-to-lms-provider-one-by-one';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push leads to the LMS provider one by one.';

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
            $unprocessed_lead = DB::table((new Leads())->getTable())
                ->where('is_picked_for_processing', 1)
                ->whereNull('lms_provider_lead_id')
                ->orderBy('id', 'DEsc')
                ->first();

            if(!empty($unprocessed_lead->id)){
                $leads = new LeadTypeCollection();
                $leads->push(new LeadType((array)$unprocessed_lead));

                $lead_provider = App::make(ILeadProvider::class);
                $lead_provider->submit($leads);

                $this->info("Response: ".print_r($lead_provider, true));
            }else{
                $this->info("No leads to push to provider.");
            }
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
