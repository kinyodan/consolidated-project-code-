<?php
namespace App\Http\Controllers\Traits;

use App\Jobs\SendEmailJob;

trait CanSendEmail
{
    /**
     * Send email
     * @param $recipientName
     * @param $recipientEmailAddress
     * @param $subject
     * @param $message
     */
    public function sendEmail($recipientName, $recipientEmailAddress, $subject, $message){
        dispatch((new SendEmailJob(
            $recipientName,
            $recipientEmailAddress,
            $subject,
            $message
        )))->onQueue('send_email');
    }
}
