<?php
namespace App\Http\Controllers\Traits;

use App\Jobs\SendEmailJob;

trait CanSendEmail
{
    use CanLog;

    /**
     * Send email
     * @param $recipientName
     * @param $recipientEmailAddress
     * @param $subject
     * @param $message
     * @param null $sender_name
     * @param null $sender_email
     */
    public function sendEmail($recipientName, $recipientEmailAddress, $subject, $message, $sender_name = null, $sender_email = null){
        $this->logMessage("Dispatching::sendEmail");

        dispatch((new SendEmailJob(
            $recipientName,
            $recipientEmailAddress,
            $subject,
            $message,
            $sender_name,
            $sender_email
        )))->onQueue('course_application_workflow_email');
    }
}
