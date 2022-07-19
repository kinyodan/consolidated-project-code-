<?php

namespace Tests\Feature;

use App\Http\Middleware\JWTAuthentication;
use App\Models\Curriculum;
use App\Models\UniversalSchoolClass;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UniversalSchoolClassTest extends TestCase
{
  /**
   * Setup
   */
  public function setUp(): void
  {
    parent::setUp();
    Schema::disableForeignKeyConstraints();
    DB::table((new UniversalSchoolClass())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'test-token.txt'));
    $userData = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
    (new JWTAuthentication())->createUser($userData);
    $this->authentication_token = 'Bearer ' . $token;
  }
  
  /**
   * @return void
   */
  public function testIfUserCanGetTheUrl()
  {
    $response = $this->post('api/classes/list-classes');
    
    $response->assertStatus(200);
  }
  
  public function testIfUserCanGetListOfClasses()
  {
    Curriculum::factory()->count(1)->create();
    $classes = UniversalSchoolClass::factory()->count(3)->create();
    $class = $classes->first();
    $this->post('api/classes/list-classes', [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'universal_school_classes', 3);
    $this->assertDatabaseHas(
      'universal_school_classes', [
        'id' => $class->id]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new UniversalSchoolClass())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanAddClasses()
  {
    $curriculum = Curriculum::factory()->count(1)->create();
    $class_name = Factory::create()->name;
    $class_name_slug = Factory::create()->slug();
    $this->post('api/classes/add-a-class', [
      'curriculum_id' => $curriculum->first()->id,
      'class_name_slug' => $class_name_slug,
      'class_name' => $class_name,
    
    ], [
      'authorization' => $this->authentication_token
    ]);
    
    $this->assertDatabaseHas(
      'universal_school_classes', [
        'curriculum_id' => $curriculum->first()->id]
    );
    
    Schema::disableForeignKeyConstraints();
    DB::table((new UniversalSchoolClass())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    
  }
  
  public function testIfUserCanUpdateClasses()
  {
    $curriculum = Curriculum::factory()->count(1)->create();
    $classes = UniversalSchoolClass::factory()->count(3)->create();
    $class_name = Factory::create()->name;
    $class_name_slug = Factory::create()->slug();
    $class = $classes->first();
    $this->post('api/classes/' . $class->id . '/update-a-class', [
      'curriculum_id' => $curriculum->first()->id,
      'class_name_slug' => $class_name_slug,
      'class_name' => $class_name,
    
    ], [
      'authorization' => $this->authentication_token
    ]);
    
    $this->assertDatabaseHas(
      'universal_school_classes', [
        'curriculum_id' => $curriculum->first()->id]
    );
    
    Schema::disableForeignKeyConstraints();
    DB::table((new UniversalSchoolClass())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    
  }
  
  public function testIfUserCanDeleteClasses()
  {
    Curriculum::factory()->count(1)->create();
    $class = UniversalSchoolClass::factory()->count(1)->create();
    $class = $class->first();
    $this->post('api/classes/' . $class->id . '/delete-a-class', [
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseHas(
      'universal_school_classes', [
        'id' => $class->id, 'is_active' => 0, 'is_deleted' => 1]
    );
    
    Schema::disableForeignKeyConstraints();
    DB::table((new UniversalSchoolClass())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    
  }
}
