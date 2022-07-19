<?php
namespace App\Console\Commands;

use App\Http\Controllers\Helpers\HelperFunctions;
use App\Http\Controllers\SchoolAdminsController;
use App\Http\Controllers\SchoolsController;
use Illuminate\Http\Request;
use Illuminate\Console\Command;

class CreateSchoolAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:school-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Create a school admin';

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
     * @return void
     */
    public function handle() {
        try{
            $this->info('Enter the school admin details');
            $schools = (new SchoolsController())->index()->getData();
            $schoolsArr = ['-'=>'Select School'];

            if(!empty($schools->data)){
                foreach ($schools->data as $school){
                    $schoolsArr[$school->id] = "{$school->school_name} ({$school->school_email})";
                }
            }

            $schoolId = $this->choice('School ', $schoolsArr);
            $adminName = $this->ask('Admin Name');
            $adminEmail = $this->ask('Admin Email');
            $adminPhone = $this->ask('Admin Phone');
            $adminAddress = $this->ask('Admin Address');

            $request = new Request([
                'school_id' => HelperFunctions::toCleanString($schoolId),
                'admin_name' => HelperFunctions::toCleanString($adminName),
                'admin_email' => HelperFunctions::toEmailAddress($adminEmail),
                'admin_phone' => HelperFunctions::toCleanString($adminPhone),
                'admin_address' => HelperFunctions::toCleanString($adminAddress),
            ]);

            $response  = (new SchoolAdminsController())->store($request);

            $this->info(print_r($response->getData(),true));

        }catch (\Exception $exception){
            $this->error($exception->getMessage().' '.$exception->getFile().' '.$exception->getLine());
        }
    }
}
