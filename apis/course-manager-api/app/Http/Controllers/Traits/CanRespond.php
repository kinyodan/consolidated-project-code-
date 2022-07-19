<?php
namespace App\Http\Controllers\Traits;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use Exception;
use Illuminate\Http\JsonResponse;

trait CanRespond
{
    use CanLog;

    /**
     * Respond to the requesting service
     *
     * @param CraydelJSONResponseType $response
     *
     * @return JsonResponse
     */
    public function respondInJSON(CraydelJSONResponseType $response): JsonResponse
    {
        try{
            return response()->json([
                'status' => $response->isStatus(),
                'message' => $response->getMessage(),
                'data' => $response->getData()
            ]);
        }catch (Exception $exception){
            $this->logException($exception);

            return response()->json([
                'status' => false,
                'msg' => LanguageTranslationHelper::translate('general.error.missing_request_response'),
                'data' => null
            ]);
        }
    }

    /**
     * Respond to the requesting service with loginToken
     *
     * @param CraydelJSONResponseType $response
     *
     * @return JsonResponse
     */
    public function respondInJSONWithLoginToken(CraydelJSONResponseType $response): JsonResponse
    {
        try{
            return response()->json([
                'status' => $response->isStatus(),
                'message' => $response->getMessage(),
                '_token' => $response->getAuthenticationToken()
            ]);
        }catch (Exception $exception){
            $this->logException($exception);

            return response()->json([
                'status' => false,
                'msg' => LanguageTranslationHelper::translate('general.error.missing_request_response'),
                '_token' => null
            ]);
        }
    }

    /**
     * Respond as an internal response
     *
     * @param CraydelInternalResponseHelper $craydelInternalResponseHelper
     *
     * @return CraydelInternalResponseHelper
     */
    public function internalResponse(CraydelInternalResponseHelper $craydelInternalResponseHelper): CraydelInternalResponseHelper
    {
        try{
            if ($craydelInternalResponseHelper->exception instanceof Exception){
                $this->logException($craydelInternalResponseHelper->exception);
            }

            return $craydelInternalResponseHelper;
        }catch (Exception $exception){
            return (new CraydelInternalResponseHelper(
                false,
                $exception->getMessage().
                null,
                $exception
            ));
        }
    }
}
