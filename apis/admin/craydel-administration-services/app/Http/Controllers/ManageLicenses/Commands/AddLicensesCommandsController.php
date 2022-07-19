<?php

namespace App\Http\Controllers\ManageLicenses\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\School;
use App\Models\SchoolAdmin;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddLicensesCommandsController
{
  use CanLog, CanRespond;
  public function handle(Request $request, string $school_code): JsonResponse{
    try{
      $validation = (new ValidateLicenseDetailsCommandController())->handle($request, $school_code);
      
      if(!$validation->status){
        throw new Exception($validation->message ?? "Error while validating the licenses details");
      }
      
      $school_id =$validation->data['school_id'];
      $allowed_license_count =$validation->data['allowed_license_count'];
      
      $saved = false;
      
      DB::transaction(function () use($validation,$school_id,$allowed_license_count, &$saved){
        $saved = DB::table((new School())->getTable())
          ->where('id',$school_id)
          ->update(['allowed_license_count' => $allowed_license_count]);
      });
      
      if(!$saved){
        throw new Exception("Error while saving the allowed license count details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('licenses.success.added')
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