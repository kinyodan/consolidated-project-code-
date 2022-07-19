<?php
namespace App\Http\Middleware;

use App\Http\Controllers\Helpers\CraydelResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class JTWAuthenticationMiddleware
{
    /**
     * Run the request filter.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->hasHeader('authorization')) {
            $token = $request->header('authorization');
            try {
                if (Str::startsWith($token, 'Bearer ')) {
                    $token = Str::substr($token, 7);
                    $userData = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
                    if (isset($userData->user_code)) {
                        $request->request->set('user_code', $userData->user_code);
                    } else {
                        return (new CraydelResponseHelper())->craydelSuccessResponse(false, 'Unauthorised', ['data' => []]);
                    }
                } else {
                    return (new CraydelResponseHelper())->craydelSuccessResponse(false, 'Unauthorised', ['data' => []]);
                }
            }catch (Throwable $exception){
                return (new CraydelResponseHelper())->craydelSuccessResponse(false, 'Unauthorised', ['data' => []]);
            }
        }else{
            return (new CraydelResponseHelper())->craydelSuccessResponse(false,'Unauthorised',['data'=>[]]);
        }
        return $next($request);
    }
}
