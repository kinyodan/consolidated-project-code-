<?php
namespace App\Http\Controllers\Courses\Queries\PublicView;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\ManageSearchEngineDataHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchCourseQueryController
{
    use CanRespond, CanLog, CanCache;

    /**
     * Search for courses
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try{
            $search_client = (new ManageSearchEngineDataHelper());

            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                'Listed',
                $search_client->search($request)->data
            ));
        }catch (\Exception $exception){
            $this->logException($exception);

            return $this->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
