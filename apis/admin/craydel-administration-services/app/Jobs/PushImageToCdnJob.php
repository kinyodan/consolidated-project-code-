<?php

namespace App\Jobs;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\School;
use Carbon\Carbon;
use Exception;



class PushImageToCdnJob extends Job
{
  use CanUploadImage,CanLog;
  
  
  protected string $school_code;
  
  
  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(string $school_code)
  {
    $this->school_code = $school_code;
  }
  
  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    
    try{
      $school = School::where('school_code', $this->school_code)
        ->first();
      $this->pushSchoolImage($school);
    
    
    }catch (Exception $exception){
      School::where('school_code', $this->school_code)
        ->update([
          'temp_school_logo_url_error' => $exception->getMessage()
        ]);
    
      self::logException($exception);
    }
  }
  protected function upload(string $path): ?object
  {
    if(CraydelHelperFunctions::isNull($path)){
      return null;
    }
    
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $file_name = CraydelHelperFunctions::makeRandomString(20).'.'.$extension;
    return self::_toCDN($path, $file_name);
  }
  protected function pushSchoolImage(School $school){
  
    $temp_school_image = $school->temp_school_logo_url ?? null;
    
    if(!CraydelHelperFunctions::isNull($temp_school_image)){
      if(!file_exists($temp_school_image)){
        $school->update([
          'temp_school_logo_url_error' => "temp_student_image doesn't exist"
        ]);
      }
      
      $data = $this->upload($temp_school_image);
      
      if($data->status){
        if(!CraydelHelperFunctions::isNull($school->school_logo_url ?? null)){
          self::_deleteFromCDN($school->school_logo_url ?? null);
        }
        $school->update([
          'school_logo_url' => $data->file_path_cdn ?? null,
          'updated_at' => Carbon::now()->toDateTimeString(),
          'temp_school_logo_url' => null,
          'temp_school_logo_url_error' => null
        ]);
      }else{
        $school->update([
          'updated_at' => Carbon::now()->toDateTimeString(),
          'temp_school_logo_url_error' => $data->msg ?? null
        ]);
      }
    }
  }
}
