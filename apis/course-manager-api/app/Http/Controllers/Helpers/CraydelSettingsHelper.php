<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\DB;

class CraydelSettingsHelper
{
    use CanCache, CanLog;

    /**
     * Constant
    */
    const ZOHO_ACCESS_TOKEN = 'ZOHO_ACCESS_TOKEN';
    const ZOHO_ACCESS_TOKEN_EXPIRY_DATETIME = 'ZOHO_ACCESS_TOKEN_EXPIRY_DATETIME';
    const ZOHO_REFRESH_TOKEN = 'ZOHO_ACCESS_TOKEN';

    /**
     * Update Craydel settings
     *
     * @param string|null $setting_key
     * @param $setting_value
     * @return mixed
     * @throws Exception
     */
    public static function updateSetting(?string $setting_key, $setting_value){
        if (empty($setting_key)){
            throw new Exception("{".get_class(new self())."} does not allow an empty setting key in updateSetting");
        }

        if(is_array($setting_value)){
            $setting_value = json_encode($setting_value);
        }

        if(empty($setting_value)){
            throw new Exception("{".get_class(new self())."} does not allow an empty settings value in updateSetting");
        }

        DB::transaction(function () use($setting_key, $setting_value){
            DB::table((new Setting())->getTable())
                ->upsert([
                    'setting_key' => CraydelHelperFunctions::toCleanString($setting_key),
                    'setting_value' => CraydelHelperFunctions::toCleanString($setting_value)
                ],[
                    'setting_key'
                ]);
        });

        return self::cache($setting_key, $setting_value);
    }

    /**
     * Get Craydel setting
     * @param string|null $setting_key
     * @return mixed
     * @throws Exception
     */
    public static function getSetting(?string $setting_key){
        if (empty($setting_key)){
            throw new Exception("{".get_class(new self())."} does not allow an empty setting key in getSetting");
        }

        $setting_value = self::cache($setting_key);

        if(empty($setting_value)){
            return $setting_value;
        }

        $setting_value = DB::table((new Setting())->getTable())
            ->where('setting_key', CraydelHelperFunctions::toCleanString($setting_key))
            ->value('setting_value');

        return self::cache($setting_key, $setting_value);
    }
}
