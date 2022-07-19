<?php
namespace App\Http\Controllers\Helpers;

use Illuminate\Http\Request;

class GetLoggedIUserHelper
{
    /**
     * Get current user
     *
     * @param Request $request
     *
     * @return object
     */
    public static function getUser(Request $request): ?object {
        try{
            $user = $request->user();

            if($user){
                return $user;
            }else{
                return null;
            }
        }catch (\Exception $exception){
            return null;
        }
    }
}
