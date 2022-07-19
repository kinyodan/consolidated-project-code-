<?php
/**
 * Created by PhpStorm.
 * User: 001908
 * Date: 23/01/2018
 * Time: 11:27
 */

namespace App\Http\Middleware;

use Closure;

class Response
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
