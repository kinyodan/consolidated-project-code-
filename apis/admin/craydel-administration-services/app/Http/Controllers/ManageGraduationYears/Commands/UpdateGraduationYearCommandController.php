<?php
namespace App\Http\Controllers\ManageGraduationYears\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\GraduationYear;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateGraduationYearCommandController
{
  use CanLog, CanRespond;
  
  /**
   * Add school class
   *
   * @param Request $request
   * @param int $year_id
   * @return JsonResponse
   */
  public function handle(Request $request, int $year_id): JsonResponse{
    try{
      $validation = (new ValidateGraduationYearsController())->handle($request, true);
      
      if(!$validation->status){
        throw new Exception($validation->message ?? "Error while validating the year details");
      }
      
      $saved = false;
      
      DB::transaction(function () use($validation, $year_id, &$saved){
        $saved = DB::table((new GraduationYear())->getTable())
          ->where('id', $year_id)
          ->update($validation->data);
      });
      
      if(!$saved){
        throw new Exception("Error while updating the class name details.");
      }
      
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('years.success.updated')
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
