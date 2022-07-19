<?php
/**
 * Created by PhpStorm.
 * User: 001908
 * Date: 01/03/2018
 * Time: 16:17
 */
namespace App\Http\Middleware;

use App\Http\Controllers\Traits\CanLog;
use Closure;
use Illuminate\Http\Request;

class BeforeAutoTrimmer
{
    use CanLog;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
	public function handle(Request $request, Closure $next)
	{
		$input = $request->all();
		if ($input) {
			array_walk_recursive($input, function (&$item, $key) {});
			$request->merge($input);
		}
		return $next($request);
	}
}
