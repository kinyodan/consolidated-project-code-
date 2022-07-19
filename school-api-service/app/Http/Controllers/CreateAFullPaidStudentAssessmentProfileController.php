<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\HelperFunctions;
use App\Http\Controllers\Traits\CanLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CreateAFullPaidStudentAssessmentProfileController
{
    use CanLog;

    /**
     * @param Request $request
     * @throws Exception
     */
    public function create(Request $request){
        $this->createUserAccount($request);
    }

    /**
     * Create user account
     * @param Request $request
     * @throws Exception
     */
    protected function createUserAccount(Request $request){
        $data = [
            'user_provider' => 'custom',
            'service' => 'SRVBC11P55U0Y',
            'group' => 'CAGUL2HFGRD4W',
            'role' => 'CART5TKH9CYQA',
            'email' => $request->input('email_address'),
            'password' => HelperFunctions::makeStrongPassword(10),
            'name' => $request->input('full_names'),
            'country' => 'KE',
            'locale' => 'en',
            'redirect' => 'https://workspace.craydel.com/',
            'auto_activate' => 1
        ];

        $response = Http::withHeaders([
            'country' => 'KE',
            'auto_activate' => 1
        ])->post(
            config('services.accounts_service.create_user'),
            $data
        );

        $response = json_decode($response->body());

        if($response->status){
            self::logMessage("Student user account created");

            $this->automaticallyPayForTheAssessment(
                $request,
                $response->data->user_code
            );
        }else{
            throw new Exception(isset($response->message) && !empty($response->message) ? $response->message : "Error creating the admin account");
        }
    }

    /**
     * Automatically pay for the assessment
     * @param Request $request
     * @param string $user_code
     * @throws Exception
     */
    protected function automaticallyPayForTheAssessment(Request $request, string $user_code){
        $response = Http::post(
            config('services.billing_service.make_automated_payment'),[
                'user_code' => $user_code,
                'product_code' => '8195726434',
                'country_code' => 'KE',
                'user_full_names' => $request->input('full_names'),
                'user_email_address' => $request->input('email_address'),
                'product_quantity' => 1
            ]
        );

        self::logMessage("Automated service URL: ".config('services.billing_service.make_automated_payment'));

        $response = json_decode($response->body());
        self::logMessage("Automated payment response: ".print_r($response, true));

        if(!$response->status){
            throw new Exception("Error while making the automatic assessment payment.");
        }
    }
}
