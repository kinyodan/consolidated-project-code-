<?php
namespace App\Http\Controllers\Helpers;
use App\Http\Controllers\Traits\CanLog;
use Exception;
use Spatie\Url\Url;

class CraydelURLHelper
{
    use CanLog;
    /**
     * @var Url|null $page_url_object
     */
    protected static ?Url $page_url_object = null;

    /**
     * @var Url|null $referrer_url
     */
    protected static ?Url $referrer_url = null;

    /**
     * Process URL
     *
     * @param string|null $page_url_object
     * @param string|null $referrer_url
     * @return CraydelURLHelper|null
     */
    public static function process(?string $page_url_object, ?string $referrer_url = null): ?CraydelURLHelper
    {
        try {

            $page_url_object = urldecode($page_url_object);
            $referrer_url = urldecode($referrer_url);

            if(empty($page_url_object)){
                throw new Exception("Missing URL value while processing the URL.");
            }

            $page_url_object = str_replace(" ", "", $page_url_object);

            if(!CraydelHelperFunctions::isURL($page_url_object)){
                throw new Exception("Invalid URL value while processing the URL.");
            }

            $page_url_object = Url::fromString($page_url_object);


                $referrer_url = str_replace(" ", "", $referrer_url);
                self::$referrer_url = Url::fromString($referrer_url);

            self::$page_url_object = $page_url_object;

            return (new self());
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Get utm_campaign
     * @return string|null
     */
    public function getUtmCampaign(): ?string
    {
        try{
            if(!is_null(self::$page_url_object)){
                $utm_campaign = self::$page_url_object->getQueryParameter('utm_campaign') ?? null;

                if(empty($utm_campaign)){
                    if(self::$page_url_object->getDirname() == '/campaigns'){
                        $utm_campaign = ucwords(strtolower(str_replace('-', ' ', trim(self::$page_url_object->getLastSegment()))));
                    }else{
                        $utm_campaign = self::$page_url_object->getLastSegment();
                    }
                }

                return !CraydelHelperFunctions::isNull($utm_campaign) ? CraydelHelperFunctions::toCleanString($utm_campaign) : null;
            }

            return null;
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Get utm_source
     * @return string|null
     */
    public function getUtmSource(): ?string
    {
        try{
            if(!is_null(self::$page_url_object)){
                $utm_source = self::$page_url_object->getQueryParameter('utm_source') ?? null;

                if(empty($utm_source)){
                    if(!is_null(self::$referrer_url)){
                        $utm_source = ucwords(strtolower(trim(str_replace(array('www.'), array(""), self::$referrer_url->getAuthority()))));

                        if (str_contains(strtolower($utm_source), 'craydel')) {
                            $utm_source = 'Direct Leads';
                        }
                    }

                    if(empty($utm_source)){
                        $utm_source = ucwords(strtolower(trim(str_replace(array('www.'), array(""), self::$page_url_object->getAuthority()))));

                        if (str_contains(strtolower($utm_source), 'craydel')) {
                            $utm_source = 'Direct Leads';
                        }
                    }
                }

                return !CraydelHelperFunctions::isNull($utm_source) ? CraydelHelperFunctions::toCleanString($utm_source) : null;
            }

            return null;
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Get utm_medium
     */
    public function getUtmMedium(): ?string
    {
        try{
            $utm_medium = !is_null(self::$page_url_object) ? self::$page_url_object->getQueryParameter('utm_medium') : null;
            return !CraydelHelperFunctions::isNull($utm_medium) ? CraydelHelperFunctions::toCleanString($utm_medium) : null;
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Get campaign
     */
    public function getUtmId(): ?string
    {
        try{
            $utm_id = !is_null(self::$page_url_object) ? self::$page_url_object->getQueryParameter('utm_id') : null;
            return !CraydelHelperFunctions::isNull($utm_id) ? CraydelHelperFunctions::toCleanString($utm_id) : null;
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Get utm_term
     */
    public function getUtmTerm(): ?string
    {
        try{
            $utm_term = !is_null(self::$page_url_object) ? self::$page_url_object->getQueryParameter('utm_term') : null;
            return !CraydelHelperFunctions::isNull($utm_term) ? CraydelHelperFunctions::toCleanString($utm_term) : null;
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Get ad_id
     */
    public function getAdId(): ?string
    {
        try{
            $ad_id = !is_null(self::$page_url_object) ? self::$page_url_object->getQueryParameter('ad_id') : null;
            return !CraydelHelperFunctions::isNull($ad_id) ? CraydelHelperFunctions::toCleanString($ad_id) : null;
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Get ad_set_id
     */
    public function getAdSetId(): ?string
    {
        try{
            $ad_set_id = !is_null(self::$page_url_object) ? self::$page_url_object->getQueryParameter('adset_id') : null;
            return !CraydelHelperFunctions::isNull($ad_set_id) ? CraydelHelperFunctions::toCleanString($ad_set_id) : null;
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Get ad_set_id
     */
    public function getCampaignId(): ?string
    {
        try{
            $campaign_id = !is_null(self::$page_url_object) ? self::$page_url_object->getQueryParameter('campaign_id') : null;
            return !CraydelHelperFunctions::isNull($campaign_id) ? CraydelHelperFunctions::toCleanString($campaign_id) : null;
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Get ad_name
     */
    public function getAdName(): ?string
    {
        try{
            $ad_name = !is_null(self::$page_url_object) ? self::$page_url_object->getQueryParameter('ad_name') : null;
            return !CraydelHelperFunctions::isNull($ad_name) ? CraydelHelperFunctions::toCleanString($ad_name) : null;
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Get ad_set_name
     */
    public function getAdSetName(): ?string
    {
        try{
            $ad_set_name = !is_null(self::$page_url_object) ? self::$page_url_object->getQueryParameter('adset_name') : null;
            return !CraydelHelperFunctions::isNull($ad_set_name) ? CraydelHelperFunctions::toCleanString($ad_set_name) : null;
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Get ad_placement_position
     */
    public function getAdPlacementPosition(): ?string
    {
        try{
            $ad_placement_position = !is_null(self::$page_url_object) ? self::$page_url_object->getQueryParameter('placement') : null;
            return !CraydelHelperFunctions::isNull($ad_placement_position) ? CraydelHelperFunctions::toCleanString($ad_placement_position) : null;
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Get site_source_name
     */
    public function getSiteSourceName(): ?string
    {
        try{
            $site_source_name = !is_null(self::$page_url_object) ? self::$page_url_object->getQueryParameter('site_source_name') : null;
            return !CraydelHelperFunctions::isNull($site_source_name) ? CraydelHelperFunctions::toCleanString($site_source_name) : null;
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Get site_source_name
     */
    public function getUtmContent(): ?string
    {
        try{
            $utm_content = !is_null(self::$page_url_object) ? self::$page_url_object->getQueryParameter('utm_content') : null;
            return !CraydelHelperFunctions::isNull($utm_content) ? CraydelHelperFunctions::toCleanString($utm_content) : null;
        }catch (Exception $exception){
            self::craydelExceptionLogger($exception);
            return null;
        }
    }

    /**
     * Remove parameter from URL
     *
     * @param string|null $url
     * @param string|null $key
     * @return string|null
     */
    public static function removeParameterFromUrl(?string $url, ?string $key): ?string
    {
        $query_strings = [];
        $parsed = parse_url($url);

        if(isset($parsed['query'])){
            parse_str($parsed['query'], $query_strings);
            unset($query_strings[''.$key.'']);
            return preg_replace('/\s+/', '', "{$parsed['scheme']}://{$parsed['host']}{$parsed['path']}?".self::buildHttpQueryFromArray($query_strings));
        }else{
            return $url;
        }
    }

    /**
     * Remove parameters from URL
     *
     * @param string|null $url
     * @return string|null
     */
    public static function removeParametersFromURL(?string $url): ?string
    {
        $parsed = parse_url($url);
        return preg_replace('/\s+/', '', "{$parsed['scheme']}://{$parsed['host']}{$parsed['path']}");
    }

    /**
     * Build HTTP query string from array
     *
     * @param array|null $query
     * @return string|null
     */
    public static function buildHttpQueryFromArray(?array $query ): ?string
    {
        $query_array = array();
        foreach( $query as $key => $key_value ){
            $query_array[] = ( $key ) . '=' . ( $key_value );
        }

        return implode( '&', $query_array );
    }
}
