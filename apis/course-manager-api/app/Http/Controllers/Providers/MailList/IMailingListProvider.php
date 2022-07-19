<?php
namespace App\Http\Controllers\Providers\MailList;

use App\Http\Controllers\CraydelTypes\MailingListSubscriberType;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;

interface IMailingListProvider
{
    /**
     * Add subscriber to mailing list
     *
     * @param MailingListSubscriberType $subscriber
    */
    public function addSubscriber(MailingListSubscriberType $subscriber): CraydelInternalResponseHelper;
}
