<?php

namespace Tests\Feature;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Middleware\JWTAuthentication;
use App\Models\Curriculum;
use App\Models\School;
use App\Models\SchoolAdmin;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ManageSchoolAdminTest extends TestCase
{
  protected string $authentication_token;
  
  public function setUp(): void
  {
    parent::setUp();
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    DB::table((new SchoolAdmin())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'test-token.txt'));
    $userData = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
    (new JWTAuthentication())->createUser($userData);
    $this->authentication_token = 'Bearer ' . $token;
  }
  
  public function testIfUserCanListSchoolAdminUsers(): void
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    SchoolAdmin::factory()->count(1)->create();
    
    $this->post('api/school/' . $school->school_code . '/school-admins', [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'school_admin', 1);
    $this->assertDatabaseHas(
      'school_admin', [
        'school_id' => $school->id]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new SchoolAdmin())->getTable())->truncate();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function  testIfUserCanAddSchoolAdminDetails():void
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
  
    $this->post('api/school/' . $school->school_code . '/school-admins/add', [
      'school_id' => $school->id,
      'admin_name' => Factory::create()->name(),
      'admin_email' => Factory::create()->email(),
      'admin_phone' => Factory::create()->phoneNumber(),
      'admin_address' => Factory::create()->streetAddress(),
      'admin_role' => Factory::create()->jobTitle()
  
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'school_admin', 1);
    $this->assertDatabaseHas(
      'school_admin', [
        'school_id' => $school->id]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new SchoolAdmin())->getTable())->truncate();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanUpdateSchoolAdminDetails()
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(1)->create();
    $school = $schools->first();
  
    $school_admin = SchoolAdmin::factory()->count(1)->create();
    
    $post =$this->post('api/school/' . $school->school_code . '/school-admins/' . $school_admin->first()->id . '/update', [
      'school_id' => $school->id,
      'admin_name' => Factory::create()->name(),
      'admin_email' => 'info@craydel.com',
      'admin_phone' => Factory::create()->phoneNumber(),
      'admin_address' => Factory::create()->streetAddress(),
      'admin_role' => Factory::create()->jobTitle()
    
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'school_admin', 1);
    $this->assertDatabaseHas(
      'school_admin', [
        'school_id' => $school->id,
        'admin_email' => "info@craydel.com",
        ]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new SchoolAdmin())->getTable())->truncate();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    
  }
  
  public function testIfUserCanDeleteSchoolAdminDetails()
  {
    
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    $school_admin = SchoolAdmin::factory()->count(1)->create();
    
    $this->post('api/school/' . $school->school_code . '/school-admins/' . $school_admin->first()->id . '/delete', [], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseHas(
      'school_admin', [
        'id' => $school_admin->first()->id,
        'is_deleted' => 1,
      ]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new SchoolAdmin())->getTable())->truncate();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
}
