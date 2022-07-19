<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanLog;
use Illuminate\Support\Facades\DB;

class MySQLFunctionsHelper
{
    use CanLog;

    /**
     * Bulk insert or update command
     *
     * @param $tableName
     * @param $dataToInsert
     * @param $connection
     *
     * @return void
    */
    public static function insertOrUpdate($tableName, $dataToInsert, $connection = null){
        try{
            if(empty($tableName)){
                throw new \Exception('Missing table name provided.');
            }

            if(empty($dataToInsert)){
                throw new \Exception('Missing data to insert or update.');
            }

            if(!is_array($dataToInsert)){
                throw new \Exception('Invalid data to insert or update provided.');
            }

            $first = reset($dataToInsert);

            $columns = implode( ',',
                array_map( function( $value ) { return "$value"; } , array_keys($first) )
            );

            $values = implode( ',', array_map( function( $row ) {
                    return '('.implode( ',',
                            array_map( function( $value ) { return '"'.str_replace('"', '""', $value).'"'; } , $row )
                        ).')';
                } , $dataToInsert )
            );

            $updates = implode( ',',
                array_map( function( $value ) { return "$value = VALUES($value)"; } , array_keys($first) )
            );

            $sql = "INSERT INTO {$tableName}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";

            if(empty($connection)){
                DB::statement( $sql );
            }else{
                DB::connection($connection)->statement( $sql );
            }
        }catch (\Exception $exception){
            (new self())->logException($exception);
        }
    }


    /**
     * Bulk insert ignore duplicates
     *
     * @param $tableName
     * @param $dataToInsert
     * @param $connection
     *
     * @return void
    */
    public static function insertOrIgnore($tableName, $dataToInsert, $connection = null){
        try{
            if(empty($tableName)){
                throw new \Exception('Missing table name provided, when running an insert or ignore the data.');
            }

            if(empty($dataToInsert)){
                throw new \Exception('Missing data to insert or update, when running an insert or ignore the data.');
            }

            if(!is_array($dataToInsert)){
                throw new \Exception('Invalid data to insert or update provided, when running an insert or ignore the data.');
            }

            $first = reset($dataToInsert);

            $columns = implode( ',',
                array_map( function( $value ) { return "$value"; } , array_keys($first) )
            );

            $values = implode( ',', array_map( function( $row ) {
                    return '('.implode( ',',
                            array_map( function( $value ) { return '"'.str_replace('"', '""', $value).'"'; } , $row )
                        ).')';
                } , $dataToInsert )
            );

            $sql = "INSERT IGNORE INTO {$tableName}({$columns}) VALUES {$values}";

            if(empty($connection)){
                DB::statement( $sql );
            }else{
                DB::connection($connection)->statement( $sql );
            }
        }catch (\Exception $exception){
            (new self())->logException($exception);
        }
    }
}
