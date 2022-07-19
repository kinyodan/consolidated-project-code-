<?php
namespace App\Jobs;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use Unirest\Request;

class SendEmailJob extends Job
{
    /**
     * @var string|null $recipientName
    */
    protected ?string $recipientName;

    /**
     * @var string|null $recipientEmail
    */
    protected ?string $recipientEmail;

    /**
     * @var string|null $subject
    */
    protected ?string $subject;

    /**
     * @var string|null $body
    */
    protected ?string $body;

    /**
     * @var string|null $sender_name
    */
    protected ?string $sender_name;

    /**
     * @var string|null $sender_email
    */
    protected ?string $sender_email;

    /**
     * Create a new job instance.
     *
     * @param string $recipientName
     * @param string $recipientEmail
     * @param string $subject
     * @param string $body
     * @param string|null $sender_name
     * @param string|null $sender_email
     */
    public function __construct(string $recipientName, string $recipientEmail, string $subject, string $body, ?string $sender_name = null, ?string $sender_email = null)
    {
        $this->recipientName = $recipientName;
        $this->recipientEmail = $recipientEmail;
        $this->subject = $subject;
        $this->body = $body;
        $this->sender_name = $sender_name;
        $this->sender_email = $sender_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $headers = array('Accept' => 'application/json');
        $sender_email = CraydelHelperFunctions::toEmailAddress($this->sender_email);
        $sender_name = isset($this->sender_name) && !empty($this->sender_name) ? $this->sender_name : null;

        $body = array(
            'recipient_email' => $this->recipientEmail,
            'recipient_name' => $this->recipientName,
            'subject' => $this->subject,
            'message_body' => $this->body
        );

        if(!empty($sender_email)){
            $body['sender_email'] = $sender_email;
        }

        if(!empty($sender_name)){
            $body['sender_name'] = $sender_name;
        }

        Request::post(
            config('services.craydel_services.notification.endpoints.send_email'),
            $headers,
            $body
        );
    }
}
