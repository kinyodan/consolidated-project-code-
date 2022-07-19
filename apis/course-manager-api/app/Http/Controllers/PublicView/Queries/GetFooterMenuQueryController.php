<?php
namespace App\Http\Controllers\PublicView\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\PublicView\CoursesPublicViewController;
use App\Http\Controllers\Traits\CanCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetFooterMenuQueryController
{
    use CanCache;

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
     * Get footer menu
    */
    public function footer(Request $request): JsonResponse
    {
        try{
            $country_name = $request->input('country');

            if(empty($country_name)){
                $footer = self::cache('global-footer');
            }else{
                $footer = self::cache(CraydelHelperFunctions::slugifyString($country_name.' footer'));

                if(is_null($footer)){
                    $footer = self::cache('global-footer');
                }
            }

            return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                true,
                'Listed',
                $footer
            ));
        }catch (\Exception $exception){
            $this->coursesPublicViewController->logException($exception);

            return $this->coursesPublicViewController->respondInJSON( new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
