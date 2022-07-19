<?php
namespace App\Http\Controllers\Providers\MailList\MailJet;

use App\Http\Controllers\CraydelTypes\MailingListSubscriberType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Providers\MailList\IMailingListProvider;
use App\Http\Controllers\Traits\CanRespond;
use Exception;
use Illuminate\Support\Facades\Http;

class MailJetMailingListProvider implements IMailingListProvider
{
    use CanRespond;

    /**
     * @param MailingListSubscriberType $subscriber
     * @return CraydelInternalResponseHelper
     * @throws Exception
     */
    public function addSubscriber(MailingListSubscriberType $subscriber): CraydelInternalResponseHelper
    {
        $response = Http::asJson()
            ->withHeaders([
                'Content-Type' => 'application/json'
            ])
            ->withBasicAuth(
                config('services.mailjet.api_key'),
                config('services.mailjet.api_secret')
            )->post(config('services.mailjet.api_url.add_contact'), [
                'Name' => $subscriber->getSubscriberName(),
                'Email' => $subscriber->getSubscriberEmail(),
                'IsExcludedFromCampaigns' => 'false'
            ]);

        $response = $response->body();

        if(is_null($response)){
            throw new Exception("Unable to create the subscriber contact");
        }

        $response = json_decode($response);

        if(isset($response->ErrorMessage) && str_contains($response->ErrorMessage, 'Email already exists')){
            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Already subscribed'
            ));
        }

        $contact_id = $response->Data[0]->ID ?? null;

        if(is_null($contact_id)){
            throw new Exception("Unable to get the subscriber contact ID");
        }

        $subscribed = Http::asJson()
            ->withHeaders([
                'Content-Type' => 'application/json'
            ])
            ->withBasicAuth(
                config('services.mailjet.api_key'),
                config('services.mailjet.api_secret')
            )->post(config('services.mailjet.api_url.subscribe_contact_to_list'), [
                'IsUnsubscribed' => 'false',
                'ContactID' => $contact_id,
                'ContactAlt' => $subscriber->getSubscriberEmail(),
                'ListID' => config('services.mailjet.contact_list_id'),
                'ListAlt' => config('services.mailjet.contact_list_address')
            ]);

        $subscribed = json_decode($subscribed->body());

        if(is_null($subscribed)){
            throw new Exception("Unable to subscribe the contact to the list");
        }

        $is_subscribed = isset($subscribed->Count) && CraydelHelperFunctions::toNumbers($subscribed->Count) > 0;

        return $this->internalResponse(new CraydelInternalResponseHelper(
            $is_subscribed,
            $is_subscribed === true ? 'Subscribed' : 'Subscription failed'
        ));
    }
}
