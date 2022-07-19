<?php
namespace App\Http\Controllers\Traits;

use Illuminate\Database\Eloquent\Model;

trait IsSearchable
{
    use CanLog;

    /**
     * Index item for for search
     *
     * @param Model $model
     * @param string $key
     * @param array $data
     *
     * @return void
    */
    public static function index(Model $model, string $key, array $data){
        try{

        }catch (\Exception $exception){
            (new self())->logException($exception);
        }
    }
}
