<?php
namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Cache;

trait CanCache
{
    /**
     * Cache some data
     * @param $key
     * @param null $data
     * @param int|null $cache_length
     *
     * @return mixed
     */
    private static function cache($key, $data = null, ?int $cache_length = null){
        if(is_null($data)){
            return Cache::get($key);
        }

        if(is_null($cache_length)){
            $cache_length = config('craydle.system.cache.long_lived_cache_length', 60);
        }

        return Cache::remember($key, $cache_length, function () use($data){
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
        return Cache::has($key) && Cache::forget($key);
    }
}
