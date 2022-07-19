<?php
namespace App\Http\Controllers\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait CanLog
{
    /**
     * Log exception
     *
     * @param $exception
     *
     * @return void
    */
    public function logException(Exception $exception){
        try{
            Log::error($exception->getMessage());
        }catch (Exception $exception){
            $this->logException($exception);
        }
    }

    /**
     * Log message
     *
     * @param string|null $message
     */
    public function logMessage(?string $message){
        try{
            Log::info($message);
        }catch (Exception $exception){
            $this->logException($exception);
        }
    }
}
