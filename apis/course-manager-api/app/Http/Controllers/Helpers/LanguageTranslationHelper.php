<?php
/**
 * Created by PhpStorm.
 * User: 001908
 * Date: 27/03/2019
 * Time: 17:17
 */
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;

class LanguageTranslationHelper extends Controller {
	/**
	 * Translate the message into user language
	 *
	 * @param $messageKey
	 * @return string
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
		}catch (\Exception $exception){
			return null;
		}
	}
}
