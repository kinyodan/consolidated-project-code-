<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Models\CustomFooterFilterLink;
use Exception;

class CustomFilterCommandController
{
    use CanLog, CanCache;

    const CUSTOM_FILTER = 'custom_search';
    const CUSTOM_SOURCE = 'custom_source';

    /**
     * @var string $custom_filter_url_format
    */
    protected static string $custom_filter_url_format = "%s?type=%s&%s";

    /**
     * @var array $custom_urls
     */
    private static array $custom_filter_urls = [];

    /**
     * Make filter URL
    */
    public static function makeURL(): ?array
    {
        try{
            $custom_search = CustomFooterFilterLink::active();
            $custom_filters = $custom_search->where('filter_type', CustomFilterCommandController::CUSTOM_FILTER)->get();
            $source = $custom_search->where('filter_type', CustomFilterCommandController::CUSTOM_SOURCE)->get();

            self::_custom($custom_filters);

            return self::$custom_filter_urls;
        }catch (Exception $exception){
            (new self())->logException($exception);

            return null;
        }
    }

    /**
     * Make custom search URL
     *
     * @param object $filters
     *
     * @return array
     * @throws Exception
     */
    protected static function _custom(object $filters): ?array
    {
        if(!is_object($filters)){
            throw new Exception("Invalid custom filter options");
        }

        foreach($filters as $filter){
            if(isset($filter->attributes)){
                $attributes = json_decode($filter->attributes);

                if(!is_null($attributes)){
                    $url = sprintf(
                        self::$custom_filter_url_format,
                        CraydelHelperFunctions::slugifyString($filter->title),
                        CustomFilterCommandController::CUSTOM_FILTER,
                        urldecode(http_build_query($attributes))
                    );

                    array_push(self::$custom_filter_urls, [
                        'name' => $filter->title,
                        'slug' => $url
                    ]);
                }
            }
        }

        return self::$custom_filter_urls;
    }
}
