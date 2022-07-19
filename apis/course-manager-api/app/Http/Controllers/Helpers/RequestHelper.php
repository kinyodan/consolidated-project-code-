<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\CraydelTypes\CraydelRequestContentType;
use App\Http\Controllers\Traits\CanLog;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class RequestHelper
{
    use CanLog;

    /**
     * Make get request
     * @param string $url
     * @param array|null $headers
     * @param array|null $parameters
     * @param bool $skip_url_validation
     *
     * @return CraydelInternalResponseHelper
     * @throws Exception
     */
    public static function get(string $url, ?array $headers = array(), ?array $parameters = null, bool $skip_url_validation = true): CraydelInternalResponseHelper
    {
        try{
            if (!filter_var($url, FILTER_VALIDATE_URL) && !$skip_url_validation) {
                throw new Exception("Invalid URL in get request.");
            }

            if(!is_null($parameters) && (!is_array($parameters) || count($parameters) <= 0)){
                throw new Exception("Invalid parameter list in get request.");
            }

            if(is_null(self::connect())){
                throw new Exception("Unable to get the request connector object.");
            }

            $response = self::connect()
                ->request('GET', $url, self::requestOptions(CraydelRequestContentType::JSON, $headers, $parameters))
                ->getBody()
                ->getContents();

            return new CraydelInternalResponseHelper(
                true,
                'True',
                $response
            );
        }catch (\Exception $exception){
            (new self())->logException($exception);

            return new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            );
        }catch (GuzzleException $ex){
            (new self())->logException($ex);

            return new CraydelInternalResponseHelper(
                false,
                $ex->getMessage(),
                null,
                $ex
            );
        }
    }

    /**
     * Make post request
     * @param string|null $url
     * @param string $contentType
     * @param array $parameters
     * @param array|null $headers
     *
     * @return CraydelInternalResponseHelper
     * @throws Exception
     */
    public static function post(?string $url, string $contentType, array $parameters, ?array $headers = array()): CraydelInternalResponseHelper
    {
        try{
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw new Exception("Invalid URL in post request.");
            }

            if(is_null($contentType) || empty($contentType)){
                throw new Exception("Invalid request content type.");
            }

            if(!is_null($parameters) && (!is_array($parameters) || count($parameters) <= 0)){
                throw new Exception("Invalid parameter list in post request.");
            }

            if(is_null(self::connect())){
                throw new Exception("Unable to post the request connector object.");
            }

            $request = self::connect()
                ->request('POST', $url, self::requestOptions($contentType, $headers, $parameters))
                ->getBody()
                ->getContents();

            return new CraydelInternalResponseHelper(
                true,
                'True',
                $request
            );
        }catch (\Exception $exception){
            (new self())->logException($exception);

            return new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            );
        }catch (GuzzleException $ex){
            return new CraydelInternalResponseHelper(
                false,
                $ex->getMessage(),
                null,
                $ex
            );
        }
    }

    /**
     * Build request options
     *
     * @param string $contentType
     * @param $headers
     * @param $parameters
     *
     * @return array
     */
    protected static function requestOptions(string $contentType, $headers, $parameters): ?array
    {
        $options = [];

        if(!is_null($headers) && is_array($headers) && count($headers) > 0){
            $options['headers'] = $headers;
        }

        if(!is_null($parameters) && is_array($parameters) && count($parameters) > 0){
            $options[''.$contentType.''] = $parameters;
        }

        return $options;
    }

    /**
     * Requests client
    */
    private static function connect(): Client
    {
        return new Client();
    }
}
