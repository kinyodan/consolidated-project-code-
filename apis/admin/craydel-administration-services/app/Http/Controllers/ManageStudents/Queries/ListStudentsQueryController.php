<?php

namespace App\Http\Controllers\ManageStudents\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\DateHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Curriculum;
use App\Models\Student;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ListStudentsQueryController
{
  
  use CanRespond,CanLog,CanCache,CanPaginate;
  
  public function handle(Request $request, string $school_code):JsonResponse
  {
    try{
      
      $school = GetLoggedIUserHelper::getUserSchool($request, $school_code);
      
      $students = Student::with(['school', 'class', 'stream', 'curriculum', 'year'])
        ->where('is_deleted', 0)
        ->where('school_id', $school->id);
      
      $search = $request->input('search');
      
      if(!CraydelHelperFunctions::isNull($search)){
        $students = $students->where('student_name', 'like', '%'.$search.'%');
      }
      
      $students = $this->filter($request, $students);
      
      $currentPage = $this->getCurrentPage($request);
      $this->currentPaginationPage = $currentPage;
      $this->totalNumberOfEntities = $students->count('id');
      $this->itemsPerPage = $request->input('items_per_page', config('craydle.items_per_page', 10));
      
      Paginator::currentPageResolver(function () use ($currentPage) {
        return $currentPage;
      });
      
      $sort_by = $request->input('sort_by');
      $sort_direction = $request->input('sort_direction');
      
      if(!CraydelHelperFunctions::isNull($sort_by) && !CraydelHelperFunctions::isNull($sort_direction)){
        $students = $students->orderBy($sort_by, $sort_direction);
      }else{
        $students = $students->orderBy('id', 'desc');
      }
      
      $students = $students
        ->simplePaginate($this->itemsPerPage);
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('students.success.listed'),
        (object)[
          'items' => collect($students->items())->map(function ($student){
            return [
              'id' => $student->id ?? null,
              'school_id' => $student->school_id ?? null,
              'school' => call_user_func(function () use($student){
                return [
                  'school_name' => $student->school->school_name ?? null,
                  'school_code' => $student->school->school_code ?? null,
                  'school_phone' => $student->school->school_phone ?? null,
                  'school_address'=> $student->school->school_address ?? null,
                  'school_logo_url' => $student->school->school_logo_url ?? null,
                  'school_has_to_collect_full' => $student->school->school_has_to_collect_full ?? false,
                  'country_code' => $student->school->country_code ?? null
                ];
              }),
              'curriculum' => call_user_func(function () use($student){
                return null ?? $student->curriculum;
              }),
              'student_code' => $student->student_code ?? null,
              'craydel_user_id' => $student->craydel_user_id ?? null,
              'student_name' => $student->student_name ?? null,
              'student_email' => $student->student_email ?? null,
              'student_phone' => $student->student_phone ?? null,
              'student_address' => $student->student_address ?? null,
              'is_craydel_account_created' => $student->is_craydel_account_created ?? null,
              'is_invite_sent' => $student->is_invite_sent ?? null,
              'is_account_activated' => $student->is_account_activated ?? null,
              'has_done_career_counselling' => $student->has_done_career_counselling ?? null,
              'has_applied_for_course' => $student->has_applied_for_course ?? null,
              'has_subscribed_for_assessment' => $student->has_subscribed_for_assessment ?? null,
              'student_assessment_code' => $student->student_assessment_code ?? null,
              'student_opportunity_code' => $student->student_opportunity_code ?? null,
              'student_opportunity_status' => $student->student_opportunity_status ?? null,
              'student_opportunity_institution' => $student->student_opportunity_institution ?? null,
              'student_opportunity_institution_location' => $student->student_opportunity_institution_location ?? null,
              'student_opportunity_intake' => $student->student_opportunity_intake ?? null,
              'student_opportunity_course' => $student->student_opportunity_course ?? null,
              'student_lead_code' => $student->student_lead_code ?? null,
              'student_lead_status' => $student->student_lead_status ?? null,
              'created_at' => isset($student->created_at) && CraydelHelperFunctions::isDate($student->created_at) ?
                DateHelper::makeDisplayDateTime($student->created_at, 'd-m-Y') : null,
              'updated_at' => isset($student->updated_at) && CraydelHelperFunctions::isDate($student->updated_at) ?
                DateHelper::makeDisplayDateTime($student->updated_at, 'd-m-Y') : null,
              'created_by' => $student->created_by ?? null,
              'updated_by' => $student->updated_by ?? null,
              'deleted_by' => $student->deleted_by ?? null,
              'class' => call_user_func(function () use($student){
                return [
                  'id' => $student->class->id ?? null,
                  'class_name' => $student->class->class_name ?? null,
                  'curriculum' => call_user_func(function () use($student){
                    $curriculum_id = $student->class->curriculum_id ?? null;
                    
                    if(!CraydelHelperFunctions::isNull($curriculum_id)){
                      return DB::table((new Curriculum())->getTable())
                        ->where('id', $curriculum_id)
                        ->first([
                          'id',
                          'curriculum_name',
                          'curriculum_code'
                        ]);
                    }else{
                      return null;
                    }
                  })
                ];
              }),
              'stream' => call_user_func(function () use($student){
                return [
                  'id' => $student->stream->id ?? null,
                  'stream_name' => $student->stream->stream_name ?? null
                ];
              }),
              'student_image' => $student->student_image ?? null,
              'date_of_birth' => $student->date_of_birth ?? null,
              'gender' => $student->gender ?? null,
              'nationality' => $student->nationality ?? null,
              'curriculum_id' => $student->curriculum_id ?? null,
              'date_enrolled' => $student->date_enrolled ?? null,
              'year_id' => $student->year_id ?? null,
              'guardian_profile_photo' => $student->guardian_profile_photo ?? null,
              'guardian_name' => $student->guardian_name ?? null,
              'guardian_mobile_number' => $student->guardian_mobile_number ?? null,
              'guardian_email' => $student->guardian_email ?? null,
              'guardian_student_relationship' => $student->guardian_student_relationship ?? null,
              'year' => call_user_func(function () use($student){
                return [
                  'id' => $student->year->id ?? null,
                  'year' => $student->year->year ?? null,
                  'description' => $student->year->description ?? null
                ];
              })
            ];
          }),
          'items_per_page' => $this->itemsPerPage,
          'current_page' => $this->currentPaginationPage,
          'previous_page' => $this->previousPage(),
          'next_page' => $this->nextPage(),
          'number_of_pages' => $this->getTotalNumberOfPages(),
          'items_count' => $this->totalNumberOfEntities
        ]
      ));
    }catch (Exception $exception){
      self::logException($exception);
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        false,
        $exception->getMessage()
      ));
    }
  }
  
  protected function filter(Request $request, Builder $model): Builder
  {
    
    $student_classes = $request->input('student_classes');
    $student_graduation_years = $request->input('student_graduation_years');
    $student_streams = $request->input('student_streams');
    $student_genders = $request->input('student_genders');
    $student_milestones = $request->input('student_milestones');
    
    if(!CraydelHelperFunctions::isNull($student_classes)){
      $student_classes = explode(',', $student_classes);
      $student_classes = array_map(function ($class){
        return intval($class);
      }, $student_classes);
      
      $model = $model->whereIn('class_id', $student_classes);
    }
    
    if(!CraydelHelperFunctions::isNull($student_graduation_years)){
      $student_graduation_years = explode(',', $student_graduation_years);
      $student_graduation_years = array_map(function ($year){
        return intval($year);
      }, $student_graduation_years);
      
      $model = $model->whereIn('year_id', $student_graduation_years);
    }
    
    if(!CraydelHelperFunctions::isNull($student_streams)){
      $student_streams = explode(',', $student_streams);
      $student_streams = array_map(function ($stream){
        return intval($stream);
      }, $student_streams);
      
      $model = $model->whereIn('stream_id', $student_streams);
    }
    
    if(!CraydelHelperFunctions::isNull($student_genders)){
      $student_genders = explode(',', $student_genders);
      $student_genders = array_map(function ($gender){
        return CraydelHelperFunctions::toCleanString($gender);
      }, $student_genders);
      
      $model = $model->whereIn('gender', $student_genders);
    }
    
    if(!CraydelHelperFunctions::isNull($student_milestones)){
      $student_milestones = explode(',', $student_milestones);
      
      foreach ($student_milestones as $milestone){
        if(CraydelHelperFunctions::toCleanString($milestone) === 'Invite Sent'){
          $model = $model->where('is_invite_sent', 1);
        }elseif(CraydelHelperFunctions::toCleanString($milestone) === 'Account Created'){
          $model = $model->where('is_craydel_account_created', 1);
        }elseif(CraydelHelperFunctions::toCleanString($milestone) === 'Payment Confirmed'){
          $model = $model->where('has_subscribed_for_assessment', 1);
        }elseif(CraydelHelperFunctions::toCleanString($milestone) === 'Assessment Completed'){
          $model = $model->where('has_done_career_counselling', 1);
        }elseif(CraydelHelperFunctions::toCleanString($milestone) === 'Application Submitted'){
          $model = $model->where('has_applied_for_course', 1);
        }
      }
    }
    
    return $model;
  }
  
}