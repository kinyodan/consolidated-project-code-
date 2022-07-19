<?php
namespace App\Http\Controllers\Administration\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Institution;
use http\Env\Request;
use Illuminate\Http\JsonResponse;

class GetSummaryDetailsQueryController
{
    use CanLog, CanRespond, CanCache;

    /**
     * Get the institution summary details
     *
     * @param string|null $institution_code
     * @return JsonResponse
     */
    public function summary(?string $institution_code): JsonResponse
    {
        try{
            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);

            if(empty($institution_code)){
                throw new \Exception('Missing institution code');
            }

            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                'Summary',[
                    'institution' => Institution::with(['type', 'country', 'reviews'])
                        ->where('institution_code', $institution_code)
                        ->first()
                ]
            ));
        }catch (\Exception $exception){
            return $this->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
