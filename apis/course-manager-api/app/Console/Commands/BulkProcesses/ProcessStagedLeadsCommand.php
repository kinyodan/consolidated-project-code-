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

class ProcessStagedLeadsCommand extends Command
{
    use CanUploadImage;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:leads:push-to-lms-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push leads to the LMS provider.';

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
            $pending_leads = DB::table((new Leads())->getTable())
                ->where('is_processed', 0)
                ->where('is_picked_for_processing', 0)
                ->get();

            if(count($pending_leads) > 0){
                $leads = new LeadTypeCollection();

                foreach ($pending_leads as $lead){
                    $updated = DB::table((new Leads())->getTable())
                        ->where('id', $lead->id)
                        ->update([
                            'is_picked_for_processing' => 1
                        ]);

                    if($updated){
                        $leads->push(new LeadType((array)$lead));
                    }
                }

                $lead_provider = App::make(ILeadProvider::class);

                $lead_provider->submit($leads);
            }else{
                $this->info('No leads to publish');
            }
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
