<?php
namespace App\Http\Controllers\Helpers;

class ImmutableOptions
{
    /**
     * @var array|null $parameters
    */
    private ?array $parameters;

    /**
     * @param array|null $parameters
     */
    public function __construct(?array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Get the parameter value
    */
    public function get(string $key, $default_value = null){
        if(isset($this->parameters[$key])){
            return !CraydelHelperFunctions::isNull($this->parameters[$key]) ? $this->parameters[$key] : $default_value;
        }else{
            return $default_value;
        }
    }
}
