<?php

namespace Database\Seeders;

use App\Models\SchoolBankDetail;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolBankDetailsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table((new SchoolBankDetail())->getTable())->truncate();
      DB::table((new SchoolBankDetail())->getTable())->insert(array(
        0 => array(
          'school_id' => 1,
          'account_name' => Factory::create()->name(),
          'account_number' => Factory::create()->creditCardNumber(),
          'bank_name' => Factory::create()->company(),
          'branch_name' => Factory::create()->streetName(),
          'swift_code' => Factory::create()->swiftBicNumber(),
          'status' => 1,
        )));
    }
}
