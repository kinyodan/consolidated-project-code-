<?php
namespace App\Http\Middleware;

use App\Http\Controllers\Helpers\JWTHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;

class JWTAuthentication
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle( Request $request, Closure $next )
    {
        $token = $request->header('token');

        if(!$token) {
            return response()->json([
                'status' => false,
                'message' => LanguageTranslationHelper::translate('authentication.missing_auth_token'),
                'is_logged_in' => false
            ]);
        }

        try {
            $credentials = JWTHelper::decode($token);
        } catch(ExpiredException $e) {
            return response()->json([
                'status' => false,
                'message' => LanguageTranslationHelper::translate('authentication.expired_authentication_token'),
                'is_logged_in' => false
            ]);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'is_logged_in' => false
            ]);
        }

        if(empty($credentials->user_code)){
            return response()->json([
                'status' => false,
                'message' => LanguageTranslationHelper::translate('authentication.not_logged_in'),
                'is_logged_in' => false
            ]);
        }

        if(empty($credentials->email)){
            return response()->json([
                'status' => false,
                'message' => LanguageTranslationHelper::translate('authentication.not_logged_in'),
                'is_logged_in' => false
            ]);
        }

        $request->setUserResolver(function () use ($credentials) {
            return $credentials;
        });

        return $next($request);
    }
}
