<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanLog;
use Carbon\Carbon;
use Firebase\JWT\JWT;

class JWTHelper
{
    use CanLog;

    /**
     * Create JWT token
     *
     * @param $objectIdentifier
     * @param array $payload
     * @param string $tokenID
     *
     * @return ?string
     */
    public static function encode($objectIdentifier, array $payload, string $tokenID): ?string
    {
        try{
            if(!empty($objectIdentifier)){
                $_payload = [
                    'iss' => "Craydel Authentication Service",
                    'sub' => $objectIdentifier,
                    '_token_id' => $tokenID,
                    'iat' => Carbon::now(config('app.timezone'))->getTimestamp(),
                    'exp' => Carbon::now(config('app.timezone'))
                        ->addMinutes(config('craydle.system.security.login_token_keep_alive_minutes'))
                        ->getTimestamp()
                ];

                if(is_array($payload)){
                    foreach ($payload as $key => $value){
                        if(!empty($key)){
                            $key = strtolower(CraydelHelperFunctions::toCleanString($key));
                            $_payload[''.$key.''] = $value;
                        }
                    }
                }

                $key = file_get_contents(storage_path('oauth-private.key'));
                return JWT::encode($_payload, $key, 'RS256');
            }

            throw new \Exception('Unable to encode the provided data.');
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Decode JWT token
     *
     * @param string $token
     * @return object
     */
    public static function decode(string $token): ?object
    {
        try{
            if(!is_null($token)){
                $key = file_get_contents(storage_path('oauth-public.key'));

                return JWT::decode($token, $key, ['RS256']);
            }

            throw new \Exception('Unable to decode the token provided.');
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }
}
