<?php

namespace App\Http\Controllers\ManageSchoolAdmin\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolAdmin;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddSchoolAdminDetailsCommandController
{
  use CanRespond, CanLog;
  
  public function handle(Request $request, string $school_code): JsonResponse{
    try{
      $validation = (new ValidateSchoolAdminDetailsCommandController())->handle($request, $school_code);
      
      if(!$validation->status){
        throw new Exception($validation->message ?? "Error while validating the stream details");
      }
      
      $saved = false;
      
      DB::transaction(function () use($validation, &$saved){
        $saved = DB::table((new SchoolAdmin())->getTable())
          ->insert($validation->data);
      });
      
      if(!$saved){
        throw new Exception("Error while saving the class name details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('admins.success.added')
      ));
    }catch (Exception $exception){
      self::logException($exception);
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        false,
        $exception->getMessage()
      ));
    }
  }
  
}