<?php

namespace Tests\Feature;

use App\Http\Middleware\JWTAuthentication;
use App\Models\Curriculum;
use App\Models\School;
use App\Models\Student;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StudentManageTest extends TestCase
{
  
  protected string $authentication_token;
  
  public function setUp(): void
  {
    {
      parent::setUp();
      Schema::disableForeignKeyConstraints();
      DB::table((new School())->getTable())->truncate();
      DB::table((new Curriculum())->getTable())->truncate();
      DB::table((new Student())->getTable())->truncate();
      Schema::enableForeignKeyConstraints();
      $token = file_get_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'test-token.txt'));
      $userData = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
      (new JWTAuthentication())->createUser($userData);
      $this->authentication_token = 'Bearer ' . $token;
    }
  }
  
  public function testIfUserCanListStudentDetails(): void
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    Student::factory()->count(1)->create();
    
    $this->post('api/school/' . $school->school_code . '/students', [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'students', 1);
    $this->assertDatabaseHas(
      'students', [
        'school_id' => $school->id]
    );
    
    Schema::disableForeignKeyConstraints();
    DB::table((new Student())->getTable())->truncate();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanAddStudentDetails()
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    
    $this->post('api/school/' . $school->school_code . '/students/add', [
      'student_code' => Factory::create()->randomNumber(),
      'craydel_user_id' => 1,
      'school_id' => $school->id,
      'stream_id' => 1,
      'class_id' => 1,
      'curriculum_id' => 1,
      'year_id' => 1,
      'student_name' => Factory::create()->name(),
      'student_email' => Factory::create()->email(),
      'gender' => 'Male',
      'nationality' => Factory::create()->country(),
      'student_phone_country_code' => 'KE',
      'student_phone' => '0715672738',
      'guardian_name' => Factory::create()->name(),
      'guardian_mobile_number' => '0715672738',
      'guardian_mobile_number_country_code' => 'KE'
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'students', 1);
    $this->assertDatabaseHas(
      'students', [
        'school_id' => $school->id,]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new Student())->getTable())->truncate();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanUpdateStudentDetails()
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    $student = Student::factory()->count(1)->create();
    $student_code = Factory::create()->randomNumber();
    $this->post('api/school/' . $school->school_code . '/students/' . $student->first()->id . '/update', [
      'student_code' => $student_code,
      'craydel_user_id' => 1,
      'school_id' => $school->id,
      'stream_id' => 1,
      'class_id' => 1,
      'curriculum_id' => 1,
      'year_id' => 1,
      'student_name' => Factory::create()->name(),
      'student_email' => Factory::create()->email(),
      'gender' => 'Male',
      'nationality' => Factory::create()->country(),
      'student_phone_country_code' => 'KE',
      'student_phone' => '0715672738',
      'guardian_name' => Factory::create()->name(),
      'guardian_mobile_number' => '0715672738',
      'guardian_mobile_number_country_code' => 'KE'
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'students', 1);
    $this->assertDatabaseHas(
      'students', [
        'school_id' => $school->id,
        'student_code' => $student_code
      ]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new Student())->getTable())->truncate();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanDeleteStudent()
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    $student = Student::factory()->count(1)->create();
    
    $this->post('api/school/' . $school->school_code . '/students/' . $student->first()->id . '/delete', [
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseHas(
      'students', [
        'id' => $student->first()->id,
        'is_deleted' => 1]
    );
    
    Schema::disableForeignKeyConstraints();
    DB::table((new Student())->getTable())->truncate();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    
  }
}
