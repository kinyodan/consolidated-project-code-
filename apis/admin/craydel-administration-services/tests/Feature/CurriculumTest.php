<?php

namespace Tests\Feature;

use App\Http\Middleware\JWTAuthentication;
use App\Models\Curriculum;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CurriculumTest extends TestCase
{
  
  protected string $authentication_token;
  
  
  public function setUp(): void
  {
    parent::setUp();
    Schema::disableForeignKeyConstraints();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'test-token.txt'));
    $userData = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
    (new JWTAuthentication())->createUser($userData);
    $this->authentication_token = 'Bearer ' . $token;
  }
  
  /**
   * A basic feature test example.
   *
   * @return void
   */
  public function testIfUserCanGetTheUrl(): void
  {
    $response = $this->post('api/curriculums/list');
    $response->assertStatus(200);
  }
  
  public function testIfUserCanListCurriculums()
  {
    Schema::disableForeignKeyConstraints();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    $curriculum = Curriculum::factory()->count(3)->create();
    $curriculum = $curriculum->first();
    $this->post('api/curriculums/list', [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'curriculums', 3);
    $this->assertDatabaseHas(
      'curriculums', [
        'id' => $curriculum->id]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    
  }
  
  public function testIfUserCanAddCurriculum()
  {
    Schema::disableForeignKeyConstraints();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    $country_code = Factory::create()->countryCode;
    $curriculum_name = Factory::create()->name;
    $curriculum_slug = Factory::create()->slug();
    $curriculum_code = Factory::create()->text(5);
    $this->post('api/curriculums/add-a-curriculum', [
      'country_code' => $country_code,
      'curriculum_name' => $curriculum_name,
      'curriculum_slug' => $curriculum_slug,
      'curriculum_code' => $curriculum_code,
      'is_global' => 1
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'curriculums', 1);
    $this->assertDatabaseHas(
      'curriculums', [
        'country_code' => $country_code, 'curriculum_name' => $curriculum_name]
    );
    
    Schema::disableForeignKeyConstraints();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanUpdateCurriculum()
  {
    Schema::disableForeignKeyConstraints();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    $curriculum = Curriculum::factory()->count(2)->create();
    $curriculum = $curriculum->first();
    $curriculum_name = Factory::create()->name;
    
    $this->post('api/curriculums/' . $curriculum->id . '/update-a-curriculum', [
      'country_code' => $curriculum->country_code,
      'curriculum_name' => $curriculum_name,
      'curriculum_slug' => $curriculum->curriculum_slug,
      'curriculum_code' => $curriculum->curriculum_code,
      'is_global' => 1
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseHas(
      'curriculums', [
        'curriculum_name' => $curriculum_name]
    );
    
    Schema::disableForeignKeyConstraints();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    
  }
  
  public function testIfUserCanDeleteCurriculum()
  {
    Schema::disableForeignKeyConstraints();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    $curriculum = Curriculum::factory()->count(2)->create();
    $curriculum = $curriculum->first();
  
    $this->post('api/curriculums/' . $curriculum->id . '/delete-a-curriculum', [
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseHas(
      'curriculums', [
        'id' => $curriculum->id, 'is_active' => 0, 'is_deleted' => 1]
    );
  
    Schema::disableForeignKeyConstraints();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  
  }
  
}
