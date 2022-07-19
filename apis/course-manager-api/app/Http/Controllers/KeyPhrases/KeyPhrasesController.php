<?php

namespace App\Http\Controllers\KeyPhrases;

use App\Http\Controllers\Controller;
use App\Http\Controllers\KeyPhrases\Commands\CreateKeyPhrasesCommandController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Http\Controllers\Traits\CanUploadImage;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use Illuminate\Http\Request;

class KeyPhrasesController
{

    use CanUploadImage, CanLog, CanCache, CanPaginate, CanRespond;


    private CreateKeyPhrasesCommandController $createKeyPhrasesCommandController;



    /**
     * Constructor
     */
    public function __construct()
    {

        $this->createKeyPhrasesCommandController = new CreateKeyPhrasesCommandController($this);



    }



    public  function create($course): JsonResponse
    {
        return $this->createKeyPhrasesCommandController->create($course);
    }



}
