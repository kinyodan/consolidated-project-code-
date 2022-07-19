<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class InstitutionsHelper
{
    use CanLog, CanRespond;

    /**
     * Get institution summary details.
     *
     * @param string $institution_code
     *
     * @return CraydelInternalResponseHelper
     */
    public static function summary(string $institution_code): CraydelInternalResponseHelper
    {
        try{
            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);

            if(empty($institution_code)){
                throw new \Exception('Missing institution code.');
            }

            $url = sprintf(
                config('services.craydel_services.institutions_manager.endpoints.get_institution_summary'),
                $institution_code
            );

            $summary = RequestHelper::get(
                $url
            );

            $institution = null;

            if(isset($summary->data)){
                $response = json_decode($summary->data, JSON_UNESCAPED_SLASHES);
                $institution = (object)$response['data']['institution'];
            }

            return (new self())->internalResponse(new CraydelInternalResponseHelper(
                !is_null($institution),
                'Institution',
                call_user_func(function () use($institution){
                    return (object)$institution;
                })
            ));
        }catch (\Exception $exception){
            (new self())->logException($exception);

            return (new self())->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * get institutions list
    */
    public static function institutions(): CraydelInternalResponseHelper
    {
        try{
            $client = new Client();

            $list = $client->get(
                config('services.craydel_services.institutions_manager.endpoints.get_institutions_list')
            );

            if($list->getReasonPhrase() === 'OK'){
                return (new self())->internalResponse(
                    new CraydelInternalResponseHelper(
                        true,
                        'Listed',
                        call_user_func(function () use($list){
                            $data = json_decode($list->getBody()->getContents());

                            if(isset($data->status) && $data->status == true){
                                return $data->data ?? null;
                            }else{
                                return null;
                            }
                        })
                    )
                );
            }else{
                throw new \Exception('Unable to get the institutions list via RPC.');
            }
        }catch (\Exception $exception){
            (new self())->logException($exception);

            return (new self())->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage()
            ));
        } catch (GuzzleException $e) {
            (new self())->logException($e);

            return (new self())->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage()
            ));
        }
    }

    /**
     * Get currencies
    */
    public static function currencies(): CraydelInternalResponseHelper
    {
        try{
            $client = new Client();

            $get_currencies_list = $client->get(
                config('services.craydel_services.institutions_manager.endpoints.get_currencies_list')
            );

            if($get_currencies_list->getReasonPhrase() === 'OK'){
                return (new self())->internalResponse(
                    new CraydelInternalResponseHelper(
                        true,
                        'Listed',
                        call_user_func(function () use($get_currencies_list){
                            $data = json_decode($get_currencies_list->getBody()->getContents());

                            if(isset($data->status) && $data->status == true){
                                return $data->data ?? null;
                            }else{
                                return null;
                            }
                        })
                    )
                );
            }else{
                throw new \Exception('Unable to get the institutions currencies via RPC.');
            }
        }catch (\Exception $exception){
            (new self())->logException($exception);

            return (new self())->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage()
            ));
        } catch (GuzzleException $e) {
            (new self())->logException($e);

            return (new self())->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage()
            ));
        }
    }

    /**
     * Get supported countries
    */
    public static function countries(): CraydelInternalResponseHelper
    {
        try{
            $client = new Client();

            $get_countries_list = $client->get(
                config('services.craydel_services.institutions_manager.endpoints.get_countries_list')
            );

            if($get_countries_list->getReasonPhrase() === 'OK'){
                return (new self())->internalResponse(
                    new CraydelInternalResponseHelper(
                        true,
                        'Listed',
                        call_user_func(function () use($get_countries_list){
                            $data = json_decode($get_countries_list->getBody()->getContents());

                            if(isset($data->status) && $data->status == true){
                                return $data->data ?? null;
                            }else{
                                return null;
                            }
                        })
                    )
                );
            }else{
                throw new \Exception('Unable to get the list of supported countries via RPC.');
            }
        }catch (\Exception $exception){
            (new self())->logException($exception);

            return (new self())->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage()
            ));
        } catch (GuzzleException $e) {
            (new self())->logException($e);

            return (new self())->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage()
            ));
        }
    }
}
