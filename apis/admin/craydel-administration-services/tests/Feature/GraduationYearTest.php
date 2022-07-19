<?php

namespace Tests\Feature;

use App\Http\Middleware\JWTAuthentication;
use App\Models\GraduationYear;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;


class GraduationYearTest extends TestCase
{
  
  /**
   * @var string $authentication_token
   */
  protected string $authentication_token;
  
  
  /**
   * Setup
   */
  public function setUp(): void
  {
    parent::setUp();
    Schema::disableForeignKeyConstraints();
    DB::table((new GraduationYear())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'test-token.txt'));
    $userData = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
    (new JWTAuthentication())->createUser($userData);
    $this->authentication_token = 'Bearer ' . $token;
  }
  
  public function testIfUserCanGetTheUrl(): void
  {
    $response = $this->post('api/classes/list-classes');
    
    $response->assertStatus(200);
  }
  
  public function testIfUserCanGetListOfGraduationYear()
  {
    
    $year = GraduationYear::factory()->count(3)->create();
    $year = $year->first();
    $this->post('api/classes/list-classes', [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseHas(
      'years', [
        'id' => $year->id]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new GraduationYear())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanAddGraduationYear()
  {
    $year = Factory::create()->year;
    $description = Factory::create()->text;
    $this->post('api/years/add-a-year', [
      'year' => $year,
      'description' => $description
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseHas(
      'years', [
        'year' => $year, 'description' => $description]
    );
  
    Schema::disableForeignKeyConstraints();
    DB::table((new GraduationYear())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanUpdateGraduationYear()
  {
    $year = GraduationYear::factory()->count(1)->create();
    $year = $year->first();
    $year_updated = Factory::create()->year;
    $description = Factory::create()->text;
    $this->post('api/years/' . $year->id . '/update-a-year', [
      'year' => $year_updated,
      'description' => $description
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseHas(
      'years', [
        'year' => $year_updated, 'description' => $description]
    );
  
    Schema::disableForeignKeyConstraints();
    DB::table((new GraduationYear())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    
  }
  
  public function testIfUserCanDeleteGraduationYear()
  {
    $year = GraduationYear::factory()->count(1)->create();
    $year = $year->first();
    $this->post('api/years/' . $year->id . '/delete-a-year', [
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseHas(
      'years', [
        'id' => $year->id, 'is_active' => 0, 'is_deleted' => 1]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new GraduationYear())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    
  }
  
  
}
