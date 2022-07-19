<?php
namespace App\Console\Commands\Search;

use App\Http\Controllers\Traits\CanLog;
use App\Jobs\PushInstitutionDataToSearchEngineJob;
use App\Models\Institution;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BuildInstitutionSearchCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:institution:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build institution search index';

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
                ->where('has_updates', 1)
                ->where('is_active', 1)
                ->where('is_published', 1)
                ->where('is_picked_for_indexing', 0)
                ->get([
                    'institution_code',
                    'logo_url',
                    'logo_url_small'
                ]);

            foreach ($institutions as $institution){
                if(isset($institution->logo_url) && !empty($institution->logo_url) && isset($institution->logo_url_small) && !empty($institution->logo_url_small)){
                    DB::table((new Institution())->getTable())
                        ->where('institution_code', trim($institution->institution_code))
                        ->update([
                            'is_picked_for_indexing' => 1,
                            'time_picked_for_indexing' => Carbon::now()->toDateTimeString()
                        ]);

                    dispatch((new PushInstitutionDataToSearchEngineJob(
                        $institution->institution_code
                    )))->onQueue('push_institution_to_search_engine');
                }
            }
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        } catch (GuzzleException $e) {
            $this->error($e->getMessage());
        }
    }
}
