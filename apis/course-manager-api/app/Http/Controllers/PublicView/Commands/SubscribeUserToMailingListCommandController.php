<?php
namespace App\Http\Controllers\PublicView\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\CraydelTypes\MailingListSubscriberType;
use App\Http\Controllers\Providers\MailList\IMailingListProvider;
use App\Http\Controllers\Traits\CanRespond;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Respect\Validation\Validator as v;

class SubscribeUserToMailingListCommandController
{
    use CanRespond;

    /**
     * Subscribe the user to mailing list
    */
    public function subscribe(Request $request, IMailingListProvider $provider): JsonResponse
    {
        try{
            $name = $request->input('subscriber_name');
            $email = $request->input('subscriber_email');
            $mobile_number = $request->input('subscriber_mobile_number');

            if(!v::optional(v::stringVal())->validate($name)){
                throw new Exception('Invalid subscriber name.');
            }

            if(!v::email()->notEmpty()->validate($email)){
                throw new Exception('Missing or Invalid subscriber email address');
            }

            if(!v::optional(v::stringVal())->validate($mobile_number)){
                throw new Exception('Invalid subscriber mobile number.');
            }

            $new_subscriber = (new MailingListSubscriberType())
                ->setSubscriberName($name)
                ->setSubscriberEmail($email)
                ->setSubscriberMobileNumber($mobile_number);

            $response = $provider->addSubscriber($new_subscriber);

            if(!$response->status){
                throw new Exception(!empty($response->message) ? $response->message : 'Subscription failed');
            }

            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                !empty($response->message) ? $response->message : 'Subscribed'
            ));
        }catch (Exception $exception){
            return $this->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
