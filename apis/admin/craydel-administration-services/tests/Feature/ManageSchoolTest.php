<?php

namespace Tests\Feature;

use App\Http\Middleware\JWTAuthentication;
use App\Models\Curriculum;
use App\Models\School;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ManageSchoolTest extends TestCase
{
  protected string $authentication_token;
  
  /**
   * Setup
   */
  public function setUp(): void
  {
    parent::setUp();
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'test-token.txt'));
    $userData = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
    (new JWTAuthentication())->createUser($userData);
    $this->authentication_token = 'Bearer ' . $token;
  }
  
  public function testIfUserCanGetTheUrl(): void
  {
    $response = $this->post('api/schools/list-schools');
    
    $response->assertStatus(200);
  }
  
  public function testIfUsersCanListSchools()
  {
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    $this->post('api/schools/list-schools', [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseHas(
      'schools', [
        'id' => $school->id]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanAddSchoolDetails()
  {
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    $curriculum = Curriculum::factory()->count(2)->create();
    
    
    $this->post('api/schools/add-school-details', [
      'curriculum_id' => $curriculum->first()->id,
      'country_code' => 'KE',
      'discount_value' => 10,
      'school_name' => Factory::create()->name(),
      'school_email' => Factory::create()->email(),
      'school_phone' => Factory::create()->e164PhoneNumber(),
      'school_address' => Factory::create()->name(),
      'school_physical_address' => Factory::create()->name(),
      'school_website_url' => Factory::create()->url(),
      'status' => 1,
    
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseHas(
      'schools', [
        'curriculum_id' => $curriculum->first()->id,
        'status' => 1]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanUpdateSchoolDetails()
  {
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    $curriculum = Curriculum::factory()->count(1)->create();
    $school = School::factory()->count(1)->create();
    $school = $school->first();
    $school_name = Factory::create()->name();
    $this->post('api/schools/' . $school->id . '/update-school-details', [
      'curriculum_id' => $curriculum->first()->id,
      'country_code' => 'KE',
      'discount_value' => 10,
      'school_name' => $school_name,
      'school_email' => Factory::create()->email(),
      'school_phone' => Factory::create()->e164PhoneNumber(),
      'school_address' => Factory::create()->name(),
      'school_physical_address' => Factory::create()->name(),
      'school_website_url' => Factory::create()->url(),
      'school_logo_url' => Factory::create()->url(),
      'school_inverse_logo_url' => Factory::create()->url(),
      'status' => 1,
    
    ], [
      'authorization' => $this->authentication_token
    ]);
    
    $this->assertDatabaseHas(
      'schools', [
        'curriculum_id' => $curriculum->first()->id,
        'school_name' => $school_name]
    );
    
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserDeleteSchoolDetails()
  {
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    Curriculum::factory()->count(1)->create();
    $school = School::factory()->count(1)->create();
    $school = $school->first();
    
    $this->post('api/schools/' . $school->id . '/delete-school-details',[
    ], [
      'authorization' => $this->authentication_token
    ]);
    
    $this->assertDatabaseHas(
      'schools', [
        'is_deleted' => 1,
        'status' => 0]
    );
    
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
}
