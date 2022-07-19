<?php
/**
 * Created by PhpStorm.
 * User: 001908
 * Date: 27/03/2019
 * Time: 17:17
 */
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CanLog;
use Exception;

class LanguageTranslationHelper extends Controller {
    use CanLog;

    /**
     * Translate the message into user language
     *
     * @param $messageKey
     * @return string|null
     */
	public static function translate($messageKey): ?string
    {
		try{
			if(!empty($messageKey)){
				$locale = config('app.locale', 'en');
				return trans(strtolower($locale.'.'.$messageKey));
			}else{
				return "";
			}
		}catch (Exception $exception){
            (new self())->logException($exception);
			return null;
		}
	}
}
