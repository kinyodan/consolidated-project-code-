<?php
namespace App\Jobs;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Traits\CanLog;
use Exception;
use Illuminate\Support\Facades\Http;

class PushLeadAndOpportunitiesDataToDateLakeJob extends Job
{
    use CanLog;

    /**
     * @var string $event_code
    */
    protected string $event_code;

    /**
     * @var string $event_payload
    */
    protected string $event_payload;

    /**
     * Create a new job instance.
     *
     * @param string $event_code
     * @param string $event_payload
     */
    public function __construct(string $event_code, string $event_payload)
    {
        $this->event_code = $event_code;
        $this->event_payload = $event_payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $body = [
                'event_code' => $this->event_code,
                'event_payload' => $this->event_payload,
                'event_stream_id' => CraydelHelperFunctions::makeRandomString(10)
            ];

            self::logMessage("Pushing data to the data lake: ".print_r($body, true));

            $response = Http::post(config('services.craydel_services.data_lake.push_updates'), $body);

            self::logMessage("Pushing data response: ".$response->body());
        }catch (Exception $exception){
            self::logException($exception);
        }
    }
}
