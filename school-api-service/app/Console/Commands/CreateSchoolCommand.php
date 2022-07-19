<?php
namespace App\Console\Commands;

use App\Http\Controllers\Helpers\HelperFunctions;
use App\Http\Controllers\SchoolsController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Console\Command;

class CreateSchoolCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:school';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Create a school';

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
            $this->info('Enter the school details');
            $schoolName = $this->ask('School Name');
            $schoolEmail = $this->ask('School Email');
            $schoolPhone = $this->ask('School Phone');
            $schoolAddress = $this->ask('School Address');
            $schoolPhysicalAddress = $this->ask('School Physical Address');
            $schoolLogoURL = $this->ask("School logo URL");
            $schoolInverseURL = $this->ask("School inverse logo URL");
            $discountType = $this->choice('School discount type',['percentage','flat_amount']);
            $discountAmount = $this->ask("School discount amount");
            $allowed_license_count = $this->ask("Number of licenses");

            $request = new Request([
                'school_name' => HelperFunctions::toCleanString($schoolName),
                'school_email' => HelperFunctions::toEmailAddress($schoolEmail),
                'school_phone' => HelperFunctions::toCleanString($schoolPhone),
                'school_address' => HelperFunctions::toCleanString($schoolAddress),
                'school_physical_address' => HelperFunctions::toCleanString($schoolPhysicalAddress),
                'school_logo_url' => HelperFunctions::toCleanString($schoolLogoURL),
                'school_inverse_logo_url' => HelperFunctions::toCleanString($schoolInverseURL),
                'discount_type' => HelperFunctions::toCleanString($discountType),
                'discount_value' => HelperFunctions::toNumbers($discountAmount),
                'allowed_license_count' => call_user_func(function () use($allowed_license_count){
                    $allowed_license_count = intval($allowed_license_count);

                    if(HelperFunctions::isNull($allowed_license_count)){
                        return 0;
                    }

                    return $allowed_license_count;
                }),
                'school_has_to_collect_full' => call_user_func(function () use($allowed_license_count){
                    $allowed_license_count = intval($allowed_license_count);

                    if(HelperFunctions::isNull($allowed_license_count)){
                        return 0;
                    }

                    return 1;
                }),
                'is_verified' => 1,
                'status' => 1,
            ]);

            $response = json_decode((new SchoolsController())->store($request)->getContent());

            $this->info($response->message);
        }catch (Exception $exception){
            $this->error($exception->getMessage().' '.$exception->getFile().' '.$exception->getLine());
        }
    }
}
