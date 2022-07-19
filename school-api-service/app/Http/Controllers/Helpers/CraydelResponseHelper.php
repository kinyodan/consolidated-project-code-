<?php

namespace App\Http\Controllers\Helpers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class CraydelResponseHelper
{
    /*
     * This function takes an exception and return the json response for the same
     *
     * @param $exception
     *
     * @return JsonResponse
     */
    public function craydelErrorResponse($exception): JsonResponse
    {
        return response()->json([
            'status' => false,
            'timestamp' => Carbon::today()->toDateTimeString(),
            'error_code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'data' => [],
        ], 200);

    }

    /*
     * This function takes parameters and return the json response for the same
     *
     * @param $status
     * @param $message
     * @param $data
     *
     * @return JsonResponse
     */
    public function craydelSuccessResponse($status =false, $message="", $data =[]): JsonResponse
    {
        //set the status and message
        $data['status']=$status;
        $data['message']= $message;

        //return response
        return response()->json($data, 200);
    }

}
