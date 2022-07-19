<?php
namespace App\Http\Controllers\PublicView\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Institution;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SingleInstitutionQueryController
{
    use CanLog, CanRespond;

    const UPDATE_SINGLE_INSTITUTION_CACHE = 'UPDATE_CACHE';
    const RETRIEVE_SINGLE_INSTITUTION_CACHE = 'RETRIEVE_CACHE';

    /**
     * View a single institution details
     *
     * @param string|null $institution_code
     *
     * @return JsonResponse
    */
    public function view(?string $institution_code): JsonResponse
    {
        try{
            if(empty($institution_code)){
                throw new Exception('Missing institution code.');
            }

            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);

            if(!DB::table((new Institution())->getTable())->where('institution_code', $institution_code)->exists()){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.invalid_institution_code')
                );
            }

            $institution_details = self::cache($institution_code);

            if(is_null($institution_code)){
                self::cache(
                    $institution_code,
                    self::UPDATE_SINGLE_INSTITUTION_CACHE
                );
            }

            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.details_shown'),
                $institution_details
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Cache the single institution details
     *
     * @param string|null $institution_code
     * @param string|null $action
     * @return null
     */
    public static function cache(?string $institution_code, ?string $action = self::RETRIEVE_SINGLE_INSTITUTION_CACHE){
        try{
            if(empty($institution_code)){
                throw new Exception('Missing institution code while accessing the institution cache.');
            }

            if(empty($action)){
                throw new Exception("Invalid single institution cache action");
            }

            $single_institution_cache_key = CraydelHelperFunctions::slugifyString('single_institution_'.$institution_code);
            $current_data = Cache::get($single_institution_cache_key);

            if($action == self::RETRIEVE_SINGLE_INSTITUTION_CACHE && !is_null($current_data)){
                return Cache::get($single_institution_cache_key);
            }else{
                (new self())->logMessage("Creating cache for institution code: ".$institution_code);

                Cache::forget($single_institution_cache_key);

                return Cache::remember(
                    $single_institution_cache_key,
                    config('craydle.system.cache_for_life_unless_updated'),
                    function () use($institution_code){
                        return Institution::with([
                            'type',
                            'country',
                            'reviews',
                            'gallery',
                            'accreditations'
                        ])->where('institution_code', $institution_code)->first()->toArray();
                    });
            }
        }catch (Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }
}
