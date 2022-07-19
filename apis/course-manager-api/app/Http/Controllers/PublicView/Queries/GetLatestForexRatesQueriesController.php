<?php
namespace App\Http\Controllers\PublicView\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Providers\Forex\IForex;
use App\Http\Controllers\PublicView\CoursesPublicViewController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use PHPUnit\Util\Exception;

class GetLatestForexRatesQueriesController
{
    /**
     * @var CoursesPublicViewController $coursesPublicViewController
     */
    protected CoursesPublicViewController $coursesPublicViewController;

    /**
     * Construct
     */
    public function __construct(CoursesPublicViewController $coursesPublicViewController)
    {
        $this->coursesPublicViewController = $coursesPublicViewController;
    }

    /**
     * Latest rates
    */
    public function rates(): JsonResponse
    {
        try{
            $forex_provider = App::make(IForex::class);
            $rates = $forex_provider->latest();

            if(!isset($rates->status)){
                throw new Exception(isset($rates->message) && !empty($rates->message) ? $rates->message : "Error while getting the latest exchange rates.");
            }

            return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                true,
                'Latest',
                isset($rates->data) && is_object($rates->data) ? $rates->data : null
            ));
        }catch (\Exception $exception){
            $this->coursesPublicViewController->logException($exception);

            return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
