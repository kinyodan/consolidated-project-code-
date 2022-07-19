<?php
namespace App\Helpers;

use App\Http\Controllers\Traits\CanLog;
use Illuminate\Support\Facades\DB;

class DBSchemaJHelper
{
    use CanLog;

    /**
     * @var string $currentDBDriver
     */
    protected $currentDBDriver;

    /**
     * Constructor
     */
    public function __construct() {
        $this->currentDBDriver = config('database.default');
    }

    /**
     * Check if index exists
     *
     * @param $tableName
     * @param $indexName
     *
     * @return boolean
     */
    public static function tableHasIndex (?string $tableName, ?string $indexName): bool
    {
        try{
            if(!is_null($tableName) && !is_null($indexName)){
                $dbDriver = (new self())->currentDBDriver;
                switch ($dbDriver){
                    case "mysql":
                        return (new self())->mysqlCheckIfTableHasIndex($tableName, $indexName);
                }
            }

            return true;
        }catch (\Exception $exception){
            (new self())->errorServiceProvider->exception($exception);
            return true;
        }
    }

    /**
     * Check if index exists
     *
     * @param string|null $query
     *
     * @return bool
     */
    public static function runRawQuery (?string $query): bool
    {
        try{
            if(!is_null($query)){
                $dbDriver = (new self())->currentDBDriver;
                switch ($dbDriver){
                    case "mysql":
                        return (new self())->mysqlRunRawQuery($query);
                }
            }

            return true;
        }catch (\Exception $exception){
            (new self())->errorServiceProvider->exception($exception);
            return true;
        }
    }

    /**
     * MySql check if index exists
     *
     * @param $tableName
     * @param $indexName
     *
     * @return boolean
     */
    private function mysqlCheckIfTableHasIndex ($tableName, $indexName): bool
    {
        try{
            if(!is_null($tableName) && !is_null($indexName)){
                $result = DB::select("SHOW INDEX FROM `".$tableName."` WHERE Key_name = '".$indexName."'");
                return isset($result[0]->Key_name) && !empty($result[0]->Key_name);
            }

            return true;
        }catch (\Exception $exception){
            $this->logException($exception);
            return true;
        }
    }

    /**
     * Run a raw DB query
     *
     * @param ?string $query
     *
     * @return mixed
     */
    private function mysqlRunRawQuery(?string $query): bool
    {
        try{
            return DB::statement($query);
        }catch (\Exception $exception){
            $this->logException($exception);
            return false;
        }
    }
}
