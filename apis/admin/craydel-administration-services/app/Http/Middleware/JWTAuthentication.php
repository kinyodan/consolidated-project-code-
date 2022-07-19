<?php
namespace App\Http\Middleware;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelResponseHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class JWTAuthentication
{
  use CanLog;
  /**
   * @param Request $request
   * @param Closure $next
   *
   * @return mixed
   */
//  public function handle( Request $request, Closure $next ): mixed
//  {
//    $token = $request->header('token');
//
//    if(!$token) {
//      return response()->json([
//        'status' => false,
//        'message' => LanguageTranslationHelper::translate('authentication.missing_auth_token'),
//        'is_logged_in' => false
//      ]);
//    }
//
//    try {
//      $credentials = JWTHelper::decode($token);
//      dd($credentials);
//    } catch(ExpiredException $e) {
//      return response()->json([
//        'status' => false,
//        'message' => LanguageTranslationHelper::translate('authentication.expired_authentication_tokeng'),
//        'is_logged_in' => false
//      ]);
//    } catch(Exception $e) {
//      return response()->json([
//        'status' => false,
//        'message' => $e->getMessage(),
//        'is_logged_in' => false
//      ]);
//    }
//
//    if(empty($credentials->user_code)){
//      return response()->json([
//        'status' => false,
//        'message' => LanguageTranslationHelper::translate('authentication.not_logged_in'),
//        'is_logged_in' => false
//      ]);
//    }
//
//    if(empty($credentials->email)){
//      return response()->json([
//        'status' => false,
//        'message' => LanguageTranslationHelper::translate('authentication.not_logged_in'),
//        'is_logged_in' => false
//      ]);
//    }
//
//    $request->setUserResolver(function () use ($credentials) {
//      return $credentials;
//    });
//
//    return $next($request);
//  }
  
  public function handle(Request $request, Closure $next): mixed
  {
    try{
      $token = $request->header('authorization');
      
      if(CraydelHelperFunctions::isNull($token)){
        throw new Exception("Missing authorization header");
      }
      
      if (!Str::startsWith($token, 'Bearer ')) {
        throw new Exception("The token is not a Bearer token");
      }
      
      $token = Str::substr($token, 7);
      $userData = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
      
      if (!isset($userData->user_code)) {
        throw new Exception("Unable to decode the access token");
      }
      
      $this->createUser($userData);
      
      $request->setUserResolver(function () use ($userData) {
        return User::with(['schools', 'schools.curriculum'])
          ->where('email', CraydelHelperFunctions::toEmailAddress($userData->email))
          ->first();
      });
      
      return $next($request);
    }catch (Exception $exception){
      self::logMessage($exception);
      
      return response()->json([
        'status' => false,
        'message' => $exception->getMessage(),
        'is_logged_in' => false
      ]);
    }
  }
  public function createUser(object $user): bool
  {
    try{
      $email = isset($user->email) && !CraydelHelperFunctions::isNull($user->email) ? CraydelHelperFunctions::toEmailAddress($user->email) : null;
      
      $data = [
        'country_id' => isset($user->country_id) && !CraydelHelperFunctions::isNull($user->country_id) ? CraydelHelperFunctions::toNumbers($user->country_id) : null,
        'country_code' => isset($user->country_code) && !CraydelHelperFunctions::isNull($user->country_code) ? CraydelHelperFunctions::toCleanString($user->country_code) : null,
        'user_code' => isset($user->user_code) && !CraydelHelperFunctions::isNull($user->user_code) ? CraydelHelperFunctions::toCleanString($user->user_code) : null,
        'user_provider' => isset($user->user_provider) && !CraydelHelperFunctions::isNull($user->user_provider) ? CraydelHelperFunctions::toCleanString($user->user_provider) : null,
        'email' => isset($user->email) && !CraydelHelperFunctions::isNull($user->email) ? CraydelHelperFunctions::toCleanString($user->email) : null,
        'default_language' => isset($user->default_language) && !CraydelHelperFunctions::isNull($user->default_language) ? CraydelHelperFunctions::toCleanString($user->default_language) : 'en',
        'default_currency_name' => isset($user->default_currency_name) && !CraydelHelperFunctions::isNull($user->default_currency_name) ? CraydelHelperFunctions::toCleanString($user->default_currency_name) : null,
        'default_currency_code' => isset($user->default_currency_code) && !CraydelHelperFunctions::isNull($user->default_currency_code) ? CraydelHelperFunctions::toCleanString($user->default_currency_code) : null,
        'timezone' => isset($user->timezone) && !CraydelHelperFunctions::isNull($user->timezone) ? CraydelHelperFunctions::toCleanString($user->timezone) : null,
        'full_name' => isset($user->full_name) && !CraydelHelperFunctions::isNull($user->full_name) ? CraydelHelperFunctions::toCleanString($user->full_name) : null,
        'first_name' => isset($user->first_name) && !CraydelHelperFunctions::isNull($user->first_name) ? CraydelHelperFunctions::toCleanString($user->first_name) : null,
        'last_name' => isset($user->last_name) && !CraydelHelperFunctions::isNull($user->last_name) ? CraydelHelperFunctions::toCleanString($user->last_name) : null,
        'display_name' => isset($user->display_name) && !CraydelHelperFunctions::isNull($user->display_name) ? CraydelHelperFunctions::toCleanString($user->display_name) : null,
        'acronym' => isset($user->acronym) && !CraydelHelperFunctions::isNull($user->acronym) ? CraydelHelperFunctions::toCleanString($user->acronym) : null,
        'gender' => isset($user->gender) && !CraydelHelperFunctions::isNull($user->gender) ? CraydelHelperFunctions::toCleanString($user->gender) : null,
        'profile_picture_url' => isset($user->profile_picture_url) && !CraydelHelperFunctions::isNull($user->profile_picture_url) ? CraydelHelperFunctions::toCleanString($user->profile_picture_url) : null,
        'mobile_number' => isset($user->mobile_number) && !CraydelHelperFunctions::isNull($user->mobile_number) ? CraydelHelperFunctions::toCleanString($user->mobile_number) : null,
        'permissions' => isset($user->access_permissions) ? json_encode($user->access_permissions) : null
      ];
      
      if(CraydelHelperFunctions::isNull($email)){
        throw new Exception("User token is missing an email address.");
      }
      
      if(!DB::table((new User())->getTable())->where('email', $email)->exists()){
        DB::table((new User())->getTable())->insert($data);
      }else{
        DB::table((new User())->getTable())->where('email', $email)->update($data);
      }
      
      return true;
    }catch (Exception $exception){
      self::logException($exception);
      
      return false;
    }
  }
}
