<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanLog;
use Illuminate\Http\Request;

class BuildSearchEngineQueryHelper
{
    use CanLog;

    /**
     * @var Request $request
    */
    protected Request $request;

    /**
     * Constructor
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Build requests
     *
     * @return array|null
     */
    public function build(): ?array
    {
        try{
            $page = CraydelHelperFunctions::toNumbers($this->request->get('page', 0));

             return [
                'page' => !empty($page) ? intval($page) : 0,
                'facetFilters' => array_values(collect([
                        $this->country(),
                        $this->courseType(),
                        $this->discipline(),
                        $this->graduateLevel()
                    ])->reject(function ($option){
                        return is_null($option);
                    })->toArray()),
                'numericFilters' => array_values(collect([
                        $this->feePayable()
                    ])->reject(function ($option){
                        return is_null($option);
                    })->toArray()),
                'facets' => [
                    'country',
                    'course_type',
                    'discipline',
                    'graduate_level'
                ]
            ];
        }catch (\Exception $exception){
            $this->logException($exception);

            return null;
        }
    }

    /**
     * Add filters
    */
    private function filters(): ?array
    {
        try{
            return null;
        }catch (\Exception $exception){
            $this->logException($exception);

            return null;
        }
    }

    /**
     * Add country filter options
    */
    private function country(): ?array
    {
        try{
            $countries = $this->request->get('country');

            if(empty($countries)){
                return null;
            }

            $countries = explode(",", $countries);

            if(!is_array($countries) || count($countries) <= 0){
                return null;
            }

            $list = [];

            foreach ($countries as $key => $country){
                if(!empty($country)){
                    $list[] = 'country:' . CraydelHelperFunctions::toCleanString($country);
                }
            }

            return $list;
        }catch (\Exception $exception){
            $this->logException($exception);

            return null;
        }
    }

    /**
     * Add course type filter options
    */
    private function courseType(): ?array
    {
        try{
            $course_types = $this->request->get('course_type');

            if(empty($course_types)){
                return null;
            }

            $course_types = explode(",", $course_types);

            if(!is_array($course_types) || count($course_types) <= 0){
                return null;
            }

            $list = [];

            foreach ($course_types as $key => $course_type){
                if(!empty($course_type)){
                    $list[] = 'course_type:' . CraydelHelperFunctions::toCleanString($course_type);
                }
            }

            return $list;
        }catch (\Exception $exception){
            $this->logException($exception);

            return null;
        }
    }

    /**
     * Add discipline filter options
    */
    private function discipline(): ?array
    {
        try{
            $course_discipline = $this->request->get('discipline');

            if(empty($course_discipline)){
                return null;
            }

            $course_discipline = explode(",", $course_discipline);

            if(!is_array($course_discipline) || count($course_discipline) <= 0){
                return null;
            }

            $list = [];

            foreach ($course_discipline as $key => $discipline){
                if(!empty($discipline)){
                    $list[] = 'discipline:' . CraydelHelperFunctions::toCleanString($discipline);
                }
            }

            return $list;
        }catch (\Exception $exception){
            $this->logException($exception);

            return null;
        }
    }

    /**
     * Add graduate level filter options
    */
    private function graduateLevel(): ?array
    {
        try{
            $graduate_levels = $this->request->get('graduate_level');

            if(empty($graduate_levels)){
                return null;
            }

            $graduate_levels = explode(",", $graduate_levels);

            if(!is_array($graduate_levels) || count($graduate_levels) <= 0){
                return null;
            }

            $list = [];

            foreach ($graduate_levels as $key => $graduate_level){
                if(!empty($graduate_level)){
                    $list[] = 'graduate_level:' . CraydelHelperFunctions::toCleanString($graduate_level);
                }
            }

            return $list;
        }catch (\Exception $exception){
            $this->logException($exception);

            return null;
        }
    }

    /**
     * Add fees range filter
    */
    private function feePayable(): ?string
    {
        try{
            $standard_fee_payable = $this->request->get('standard_fee_payable');

            if(empty($standard_fee_payable)){
                return null;
            }

            $standard_fee_payable = explode(",", $standard_fee_payable);

            if(!is_array($standard_fee_payable) || count($standard_fee_payable) <= 0){
                return null;
            }

            $lower_limit = isset($standard_fee_payable[0]) ? floatval($standard_fee_payable[0]) : null;
            $upper_limit = isset($standard_fee_payable[1]) ? floatval($standard_fee_payable[1]) : null;

            return 'standard_fee_payable:'.$lower_limit.' TO '.$upper_limit;
        }catch (\Exception $exception){
            $this->logException($exception);

            return null;
        }
    }
}
