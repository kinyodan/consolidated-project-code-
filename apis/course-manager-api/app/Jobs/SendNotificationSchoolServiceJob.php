<?php
namespace App\Jobs;

use Illuminate\Support\Facades\Http;

class SendNotificationSchoolServiceJob extends Job
{
    /**
     * @var string|null $user_email
    */
    protected ?string $user_email;

    /**
     * @var string|null $opportunity_code
    */
    protected ?string $opportunity_code;

    /**
     * @var string|null $opportunity_status
    */
    protected ?string $opportunity_status;

    /**
     * @var string|null $opportunity_institution
    */
    protected ?string $opportunity_institution;

    /**
     * @var string|null $opportunity_institution_location
    */
    protected ?string $opportunity_institution_location;

    /**
     * @var string|null $opportunity_intake
    */
    protected ?string $opportunity_intake;

    /**
     * @var string|null $opportunity_course
    */
    protected ?string $opportunity_course;

    /**
     * Create a new job instance.
     *
     * @param string $user_email
     * @param string $opportunity_code
     * @param string|null $opportunity_status
     * @param string|null $opportunity_institution
     * @param string|null $opportunity_institution_location
     * @param string|null $opportunity_intake
     * @param string|null $opportunity_course
     */
    public function __construct(string $user_email, string $opportunity_code, ?string $opportunity_status, ?string $opportunity_institution, ?string $opportunity_institution_location, ?string $opportunity_intake, ?string $opportunity_course)
    {
        $this->user_email = $user_email;
        $this->opportunity_code = $opportunity_code;
        $this->opportunity_status = $opportunity_status;
        $this->opportunity_institution = $opportunity_institution;
        $this->opportunity_institution_location = $opportunity_institution_location;
        $this->opportunity_intake = $opportunity_intake;
        $this->opportunity_course = $opportunity_course;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $api_url = config('services.craydel_services.school_service.higher_learning_update_notification');

        Http::post($api_url, [
            'email' => $this->user_email,
            'opportunity_code' => $this->opportunity_code,
            'opportunity_status' => $this->opportunity_status,
            'opportunity_institution' => $this->opportunity_institution,
            'opportunity_institution_location' => $this->opportunity_institution_location,
            'opportunity_intake' => $this->opportunity_intake,
            'opportunity_course' => $this->opportunity_course
        ]);
    }
}
