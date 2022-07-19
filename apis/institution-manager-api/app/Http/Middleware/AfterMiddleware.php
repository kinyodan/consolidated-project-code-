<?php
/**
 * Created by PhpStorm.
 * User: 001908
 * Date: 11/06/2018
 * Time: 12:23
 */
namespace App\Http\Middleware;

use Closure;

class AfterMiddleware {
	public function handle($request, Closure $next)
	{
		$response = $next($request);
		return $response;
	}
}