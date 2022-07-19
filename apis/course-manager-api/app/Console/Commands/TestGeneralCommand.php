<?php
namespace App\Console\Commands;

use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Leads\Queries\GetLeadDetailsQueryController;
use App\Http\Controllers\PublicView\CoursesPublicViewController;
use App\Http\Controllers\PublicView\Queries\GetMarketplaceSitemapQueryController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Jobs\PushLeadAndOpportunitiesDataToDateLakeJob;
use App\Models\AcademicDiscipline;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestGeneralCommand extends Command
{
    use CanUploadImage, CanLog, CanCache;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:general';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test general command';

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
            /*$data = DB::table((new AcademicDiscipline())->getTable())->select('discipline_name')->inRandomOrder()->value('discipline_name');
            dd($data);*/

            $code = AcademicDisciplineHelper::getDisciplineCodeByID(1);

            dd(AcademicDisciplineHelper::getDisciplineIDByCode($code));
        }catch (Exception $exception){
           $this->error($exception->getMessage());
        }
    }
}
