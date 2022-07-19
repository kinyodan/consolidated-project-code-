<?php

namespace Tests\Feature;

use App\Http\Middleware\JWTAuthentication;
use App\Models\Curriculum;
use App\Models\School;
use App\Models\SchoolBankDetail;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SchoolBankDetailsTest extends TestCase
{
  protected string $authentication_token;
  
  
  public function setUp(): void
  {
    {
      parent::setUp();
      Schema::disableForeignKeyConstraints();
      DB::table((new School())->getTable())->truncate();
      DB::table((new Curriculum())->getTable())->truncate();
      DB::table((new SchoolBankDetail())->getTable())->truncate();
      Schema::enableForeignKeyConstraints();
      $token = file_get_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'test-token.txt'));
      $userData = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
      (new JWTAuthentication())->createUser($userData);
      $this->authentication_token = 'Bearer ' . $token;
    }
  }
  
  public function testIfUserCanListSchoolBankDetails(): void
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    SchoolBankDetail::factory()->count(1)->create();
    
    $this->post('api/school/' . $school->school_code . '/bank-details', [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'school_bank_details', 1);
    $this->assertDatabaseHas(
      'school_bank_details', [
        'school_id' => $school->id]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    DB::table((new SchoolBankDetail())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanAddSchoolBankDetails()
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    
    $this->post('api/school/' . $school->school_code . '/bank-details/add', [
      'school_id' => $school->id,
      'account_name' => Factory::create()->name(),
      'account_number' => Factory::create()->creditCardNumber(),
      'bank_name' => Factory::create()->company(),
      'branch_name' => Factory::create()->streetName(),
      'swift_code' => Factory::create()->swiftBicNumber()
    
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'school_bank_details', 1);
    $this->assertDatabaseHas(
      'school_bank_details', [
        'school_id' => $school->id,]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    DB::table((new SchoolBankDetail())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanUpdateSchoolBankDetails()
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    $bank_details = SchoolBankDetail::factory()->count(1)->create();
    $account_name = Factory::create()->name();
    $account_number = Factory::create()->creditCardNumber();
    $this->post('api/school/' . $school->school_code . '/bank-details/' . $bank_details->first()->id . '/update', [
      'school_id' => $school->id,
      'account_name' => $account_number,
      'account_number' => $account_number,
      'bank_name' => Factory::create()->company(),
      'branch_name' => Factory::create()->streetName(),
      'swift_code' => Factory::create()->swiftBicNumber()
    
    ], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseCount(
      'school_bank_details', 1);
    $this->assertDatabaseHas(
      'school_bank_details', [
        'school_id' => $school->id,
        'account_name' => $account_number,
        'account_number' => $account_number]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    DB::table((new SchoolBankDetail())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
  public function testIfUserCanDeleteSchoolBankDetails()
  {
    Curriculum::factory()->count(1)->create();
    $schools = School::factory()->count(3)->create();
    $school = $schools->first();
    $bank_details = SchoolBankDetail::factory()->count(1)->create();
    $this->post('api/school/' . $school->school_code . '/bank-details/' . $bank_details->first()->id . '/delete', [], [
      'authorization' => $this->authentication_token
    ]);
    $this->assertDatabaseHas(
      'school_bank_details', [
        'id' => $bank_details->first()->id,
        'is_deleted' => 1
      ]
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new School())->getTable())->truncate();
    DB::table((new Curriculum())->getTable())->truncate();
    DB::table((new SchoolBankDetail())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
}
