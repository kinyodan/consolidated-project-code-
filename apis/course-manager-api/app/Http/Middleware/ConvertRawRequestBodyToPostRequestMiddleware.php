<?php
/**
 * Created by PhpStorm.
 * User: 001908
 * Date: 01/03/2018
 * Time: 16:17
 */
namespace App\Http\Middleware;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use Closure;
use Exception;
use Illuminate\Http\Request;

class ConvertRawRequestBodyToPostRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
     */
	public function handle(Request $request, Closure $next)
	{
        $data = file_get_contents('php://input');

        if(!is_null($data)){
            $request = CraydelHelperFunctions::convertRawRequestBodyToInputRequest($data);
            $input = $request->all();

            if ($input) {
                array_walk_recursive($input, function (&$item, $key) {});

                $request->merge($input);
            }
        }

		return $next($request);
	}
}
