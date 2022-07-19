<?php

namespace Tests\Feature;

use App\Http\Middleware\JWTAuthentication;
use App\Models\Curriculum;
use App\Models\School;
use App\Models\SchoolStream;
use Database\Factories\SchoolStreamFactory;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ManageStreamsTest extends TestCase
{
  protected string $authentication_token;
  
  public function setUp(): void
  {
    parent::setUp();
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    DB::table((new SchoolStream())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'test-token.txt'));
    $userData = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
    (new JWTAuthentication())->createUser($userData);
    $this->authentication_token = 'Bearer ' . $token;
  }
  
  public function testIfUserCanListSchoolStreams(): void
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    SchoolStream::factory()->count(1)->create();
    
    $this->post('api/school/' . $school->school_code . '/streams', [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'streams', 1);
    $this->assertDatabaseHas(
      'streams', [
        'school_id' => $school->id]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanAddSchoolStreams()
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    
    $this->post('api/school/' . $school->school_code . '/streams/add', [
      'school_id' => $school->id,
      'stream_name' => Factory::create()->name(),
    
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'streams', 1);
    $this->assertDatabaseHas(
      'streams', [
        'school_id' => $school->id]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    DB::table((new SchoolStream())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanUpdateSchoolStream()
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    $stream = SchoolStream::factory()->count(1)->create();
    $stream_name = Factory::create()->name();
    
    $this->post('api/school/' . $school->school_code . '/streams/' . $stream->first()->id . '/update', [
      'stream_name' => $stream_name,
    
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'streams', 1);
    $this->assertDatabaseHas(
      'streams', [
        'stream_name' => $stream_name]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    DB::table((new SchoolStream())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    
  }
  
  public function testIfUserCanDeleteSchoolStream()
  {
    
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    $stream = SchoolStream::factory()->count(1)->create();
    
    $this->post('api/school/' . $school->school_code . '/streams/' . $stream->first()->id . '/delete', [], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseHas(
      'streams', [
        'id' => $stream->first()->id,
        'is_deleted' => 1
      ]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    DB::table((new SchoolStream())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
}
