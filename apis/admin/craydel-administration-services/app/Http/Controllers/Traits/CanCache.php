<?php
namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Cache;

trait CanCache
{
    /**
     * Cache some data
     * @param $key
     * @param $data
     *
     * @return mixed
     */
    private static function cache($key, $data = null){
        if(is_null($data)){
            return Cache::get($key);
        }

        return Cache::remember($key, config('craydle.system.db_data_cache_length'), function () use($data){
            return $data;
        });
    }

    /**
     * Refresh cache
     *
     * @param $key
     * @return mixed
     */
    private static function clearCache($key): bool{
        return Cache::has($key) ? Cache::forget($key) : false;
    }
}
