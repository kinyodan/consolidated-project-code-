<?php
namespace App\Console\Commands;

use App\Http\Controllers\CraydelTypes\CraydelInstitutionType;
use App\Http\Controllers\Administration\Commands\PushInstitutionDataToSearchEngineCommand;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\PublicView\Queries\SingleInstitutionQueryController;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\Country;
use App\Models\Institution;
use App\Models\InstitutionGallery;
use App\Models\QuestionCategory;
use App\Models\Questions;
use App\Models\QuestionsResponse;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Random;

class TestGeneralCommand extends Command
{
    use CanUploadImage;

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
            /*$date = date_parse('July');
            dd(Carbon::create(2000, $date['month'], 01)->format('M'));*/

            dd(Carbon::now()->format('y'));
        }catch (Exception $exception){
            $this->error($exception->getMessage());
        } catch (GuzzleException $e) {
            $this->error($e->getMessage());
        }
    }
}
