<?php

namespace App\Http\Controllers\ManageSchoolAdmin\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolAdmin;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateSchoolAdminDetailsCommandController
{
  
  use CanRespond;
  
  public function handle(Request $request, string $school_code,$school_admin_id): JsonResponse{
    try{
      $validation = (new ValidateSchoolAdminDetailsCommandController())->handle($request, $school_code,true);
      
      if(!$validation->status){
        throw new Exception($validation->message ?? "Error while validating the stream details");
      }
      
      $saved = false;
      
      DB::transaction(function () use($validation,$school_admin_id, &$saved){
        $saved = DB::table((new SchoolAdmin())->getTable())
          ->where('id',$school_admin_id)
          ->update($validation->data);
      });
      
      if(!$saved){
        throw new Exception("Error while saving the school bank details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('admins.success.updated')
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