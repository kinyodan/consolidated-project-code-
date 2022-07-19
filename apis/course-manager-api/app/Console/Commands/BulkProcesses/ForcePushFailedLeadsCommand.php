<?php
namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\CraydelTypes\LeadType;
use App\Http\Controllers\CraydelTypes\LeadTypeCollection;
use App\Http\Controllers\Providers\LeadManagement\ILeadProvider;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\Leads;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ForcePushFailedLeadsCommand extends Command
{
    use CanUploadImage;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:leads:push-failed-lead-to-lms-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push failed leads to the LMS provider.';

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
            DB::table((new Leads())->getTable())
                ->where('created_at', '<', Carbon::now('UTC')->subMinutes(45)->toDateTimeString())
                ->whereNull('lms_provider_lead_id')
                ->orderBy('id')
                ->chunk(10, function ($pending_leads){
                    if($this->confirm('Are you sure you want to force push '.count($pending_leads).' ?')){
                        if(count($pending_leads) > 0){
                            $leads = new LeadTypeCollection();

                            foreach ($pending_leads as $lead){
                                $leads->push(new LeadType((array)$lead));
                            }

                            $lead_provider = App::make(ILeadProvider::class);
                            $lead_provider->forcePush($leads);
                        }else{
                            $this->info('No leads to force push');
                        }
                    }
                });
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
