<?php
/**
 * Created by PhpStorm.
 * User: 001908
 * Date: 20/12/2017
 * Time: 15:20
 */
namespace App\Http\Middleware;

use Illuminate\Http\Request;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next){
        try{
            $local = ($request->hasHeader('locale')) ? $request->header('locale') : 'en';
            config(['app.locale' => $local]);

            return $next($request);
        }catch (\Exception $exception){
	        return $next($request);
        }
    }
}
