<?php

namespace App\Http\Controllers\ManageStudents;
use App\Http\Controllers\ManageSchools\Queries\ShowSchoolDetailsQueryController;
use App\Http\Controllers\ManageStudents\Commands\AddStudentDetailsCommandController;
use App\Http\Controllers\ManageStudents\Commands\DeleteStudentDetailsCommandController;
use App\Http\Controllers\ManageStudents\Commands\UpdateStudentDetailsCommandController;
use App\Http\Controllers\ManageStudents\Queries\ListStudentsQueryController;
use App\Http\Controllers\ManageStudents\Queries\ShowStudentDetailsQueryController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManageStudentsController
{
  
  private ListStudentsQueryController  $listStudentsQueryController;
  private  AddStudentDetailsCommandController $addStudentDetailsCommandController;
  private  DeleteStudentDetailsCommandController $deleteStudentDetailsCommandController;
  private UpdateStudentDetailsCommandController $updateStudentDetailsCommandController;
  private  ShowStudentDetailsQueryController $showStudentDetailsQueryController;
  /**
   * @var array $genders
   */
  public static array $genders = [
    'Female',
    'Male',
    'I would rather not say'
  ];
  
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->listStudentsQueryController = new ListStudentsQueryController();
    $this->addStudentDetailsCommandController = new AddStudentDetailsCommandController();
    $this->showStudentDetailsQueryController = new ShowStudentDetailsQueryController();
    $this->updateStudentDetailsCommandController = new UpdateStudentDetailsCommandController();
    $this->deleteStudentDetailsCommandController = new DeleteStudentDetailsCommandController();
 
  }
    /**
   * List the school students
   *
   * @param Request $request
   * @param string $school_code
   * @return JsonResponse
   */
  public function students(Request $request, string $school_code): JsonResponse
  {
    return $this->listStudentsQueryController->handle($request, $school_code);
  }
  public function add(Request $request, string $school_code): JsonResponse
  {
    return $this->addStudentDetailsCommandController->handle($request, $school_code);
  }
  public function show(Request $request, string $school_code,int $student_id): JsonResponse
  {
    return $this->showStudentDetailsQueryController->handle($request, $school_code,$student_id);
  }
  public function update(Request $request, string $school_code,int $student_id): JsonResponse
  {
    return $this->updateStudentDetailsCommandController->handle($request, $school_code,$student_id);
  }
  public function delete(Request $request, string $school_code,int $student_id): JsonResponse
  {
    return $this->deleteStudentDetailsCommandController->handle($request, $school_code,$student_id);
  }
}