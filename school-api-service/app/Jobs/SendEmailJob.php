<?php
namespace App\Jobs;

use Unirest\Request;

class SendEmailJob extends Job
{
    /**
     * @var string $recipientName
     */
    protected $recipientName;

    /**
     * @var string $recipientEmail
     */
    protected $recipientEmail;

    /**
     * @var string $subject
     */
    protected $subject;

    /**
     * @var string $body
     */
    protected $body;

    /**
     * Create a new job instance.
     *
     * @param string $recipientName
     * @param string $recipientEmail
     * @param string $subject
     * @param string $body
     */
    public function __construct(string $recipientName, string $recipientEmail, string $subject, string $body)
    {
        $this->recipientName = $recipientName;
        $this->recipientEmail = $recipientEmail;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $headers = array('Accept' => 'application/json');
        $body = array(
            'recipient_email' => $this->recipientEmail,
            'recipient_name' => $this->recipientName,
            'subject' => $this->subject,
            'message_body' => $this->body
        );

        Request::post(
            env('CRAYDEL_NOTIFICATION_SERVICE').'/notifications/send-email',
            $headers,
            $body
        );
    }
}
