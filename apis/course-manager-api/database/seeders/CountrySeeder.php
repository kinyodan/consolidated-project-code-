<?php
namespace Database\Seeders;

use App\Models\Countries;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table((new Countries())->getTable())->truncate();
        DB::table((new Countries())->getTable())->insert(array(
            0 => array(
                'id' => 1,
                'code' => 'AF',
                'name' => 'Afghanistan',
                'dial_code' => 93,
                'currency_name' => 'Afghan afghani',
                'currency_symbol' => 'AFN',
                'currency_code' => 'AFN',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),
            1 => array
            (
                'id' => 2,
                'code' => 'AL',
                'name' => 'Albania',
                'dial_code' => 355,
                'currency_name' => 'Albanian lek',
                'currency_symbol' => 'L',
                'currency_code' => 'ALL',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),
            2 => array
            (
                'id' => 3,
                'code' => 'DZ',
                'name' => 'Algeria',
                'dial_code' => 213,
                'currency_name' => 'Algerian dinar',
                'currency_symbol' => 'DZD',
                'currency_code' => 'DZD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),
            3 => array
            (
                'id' => 4,
                'code' => 'AS',
                'name' => 'American Samoa',
                'dial_code' => 1684,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            4 => array
            (
                'id' => 5,
                'code' => 'AD',
                'name' => 'Andorra',
                'dial_code' => 376,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            5 => array
            (
                'id' => 6,
                'code' => 'AO',
                'name' => 'Angola',
                'dial_code' => 244,
                'currency_name' => 'Angolan kwanza',
                'currency_symbol' => 'Kz',
                'currency_code' => 'AOA',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            6 => array
            (
                'id' => 7,
                'code' => 'AI',
                'name' => 'Anguilla',
                'dial_code' => 1264,
                'currency_name' => 'East Caribbean dolla',
                'currency_symbol' => '$',
                'currency_code' => 'XCD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            7 => array
            (
                'id' => 8,
                'code' => 'AQ',
                'name' => 'Antarctica',
                'dial_code' => 0,
                'currency_name' => 'Antarctic dollar',
                'currency_symbol' => 'AAD',
                'currency_code' => 'AAD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            8 => array
            (
                'id' => 9,
                'code' => 'AG',
                'name' => 'Antigua And Barbuda',
                'dial_code' => 1268,
                'currency_name' => 'East Caribbean dolla',
                'currency_symbol' => '$',
                'currency_code' => 'XCD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            9 => array
            (
                'id' => 10,
                'code' => 'AR',
                'name' => 'Argentina',
                'dial_code' => 54,
                'currency_name' => 'Argentine peso',
                'currency_symbol' => '$',
                'currency_code' => 'ARS',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),
            10 => array
            (
                'id' => 11,
                'code' => 'AM',
                'name' => 'Armenia',
                'dial_code' => 374,
                'currency_name' => 'Armenian dram',
                'currency_symbol' => 'AMD',
                'currency_code' => 'AMD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            11 => array
            (
                'id' => 12,
                'code' => 'AW',
                'name' => 'Aruba',
                'dial_code' => 297,
                'currency_name' => 'Aruban florin',
                'currency_symbol' => 'AWG’',
                'currency_code' => 'AWG',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            12 => array
            (
                'id' => 13,
                'code' => 'AU',
                'name' => 'Australia',
                'dial_code' => 61,
                'currency_name' => 'Australian dollar',
                'currency_symbol' => '$',
                'currency_code' => 'AUD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            13 => array
            (
                'id' => 14,
                'code' => 'AT',
                'name' => 'Austria',
                'dial_code' => 43,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            14 => array
            (
                'id' => 15,
                'code' => 'AZ',
                'name' => 'Azerbaijan',
                'dial_code' => 994,
                'currency_name' => 'Azerbaijani manat',
                'currency_symbol' => 'AZN',
                'currency_code' => 'AZN',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            15 => array
            (
                'id' => 16,
                'code' => 'BS',
                'name' => 'Bahamas The',
                'dial_code' => 1242,
                'currency_name' => 'Bahamian dollar',
                'currency_symbol' => 'BSD',
                'currency_code' => 'BSD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            16 => array
            (
                'id' => 17,
                'code' => 'BH',
                'name' => 'Bahrain',
                'dial_code' => 973,
                'currency_name' => 'Bahraini dinar',
                'currency_symbol' => 'BHD',
                'currency_code' => 'BHD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            17 => array
            (
                'id' => 18,
                'code' => 'BD',
                'name' => 'Bangladesh',
                'dial_code' => 880,
                'currency_name' => 'Bangladeshi taka',
                'currency_symbol' => 'BDT',
                'currency_code' => 'BDT',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            18 => array
            (
                'id' => 19,
                'code' => 'BB',
                'name' => 'Barbados',
                'dial_code' => 1246,
                'currency_name' => 'Barbadian dollar',
                'currency_symbol' => '$',
                'currency_code' => 'BBD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            19 => array
            (
                'id' => 20,
                'code' => 'BY',
                'name' => 'Belarus',
                'dial_code' => 375,
                'currency_name' => 'Belarusian ruble',
                'currency_symbol' => 'Br',
                'currency_code' => 'BYR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            20 => array
            (
                'id' => 21,
                'code' => 'BE',
                'name' => 'Belgium',
                'dial_code' => 32,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            21 => array
            (
                'id' => 22,
                'code' => 'BZ',
                'name' => 'Belize',
                'dial_code' => 501,
                'currency_name' => 'Belize dollar',
                'currency_symbol' => '$',
                'currency_code' => 'BZD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            22 => array
            (
                'id' => 23,
                'code' => 'BJ',
                'name' => 'Benin',
                'dial_code' => 229,
                'currency_name' => 'West African CFA fra',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XOF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            23 => array
            (
                'id' => 24,
                'code' => 'BM',
                'name' => 'Bermuda',
                'dial_code' => 1441,
                'currency_name' => 'Bermudian dollar',
                'currency_symbol' => '$',
                'currency_code' => 'BMD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            24 => array
            (
                'id' => 25,
                'code' => 'BT',
                'name' => 'Bhutan',
                'dial_code' => 975,
                'currency_name' => 'Bhutanese ngultrum',
                'currency_symbol' => 'Nu.',
                'currency_code' => 'BTN',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            25 => array
            (
                'id' => 26,
                'code' => 'BO',
                'name' => 'Bolivia',
                'dial_code' => 591,
                'currency_name' => 'Bolivian boliviano',
                'currency_symbol' => 'Bs.',
                'currency_code' => 'BOB',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            26 => array
            (
                'id' => 27,
                'code' => 'BA',
                'name' => 'Bosnia and Herzegovina',
                'dial_code' => 387,
                'currency_name' => 'Bosnia and Herzegovi',
                'currency_symbol' => 'KM',
                'currency_code' => 'BAM',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            27 => array
            (
                'id' => 28,
                'code' => 'BW',
                'name' => 'Botswana',
                'dial_code' => 267,
                'currency_name' => 'Botswana pula',
                'currency_symbol' => 'P',
                'currency_code' => 'BWP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            28 => array
            (
                'id' => 29,
                'code' => 'BV',
                'name' => 'Bouvet Island',
                'dial_code' => 0,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            29 => array
            (
                'id' => 30,
                'code' => 'BR',
                'name' => 'Brazil',
                'dial_code' => 55,
                'currency_name' => 'Brazilian real',
                'currency_symbol' => 'R$',
                'currency_code' => 'BRL',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            30 => array
            (
                'id' => 31,
                'code' => 'IO',
                'name' => 'British Indian Ocean Territory',
                'dial_code' => 246,
                'currency_name' => 'United States dollar',
                'currency_symbol' => '$',
                'currency_code' => 'USD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            31 => array
            (
                'id' => 32,
                'code' => 'BN',
                'name' => 'Brunei',
                'dial_code' => 673,
                'currency_name' => 'Brunei dollar',
                'currency_symbol' => '$',
                'currency_code' => 'BND',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            32 => array
            (
                'id' => 33,
                'code' => 'BG',
                'name' => 'Bulgaria',
                'dial_code' => 359,
                'currency_name' => 'Bulgarian lev',
                'currency_symbol' => 'BGN',
                'currency_code' => 'BGN',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            33 => array
            (
                'id' => 34,
                'code' => 'BF',
                'name' => 'Burkina Faso',
                'dial_code' => 226,
                'currency_name' => 'West African CFA fra',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XOF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            34 => array
            (
                'id' => 35,
                'code' => 'BI',
                'name' => 'Burundi',
                'dial_code' => 257,
                'currency_name' => 'Burundian franc',
                'currency_symbol' => 'Fr',
                'currency_code' => 'BIF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            35 => array
            (
                'id' => 36,
                'code' => 'KH',
                'name' => 'Cambodia',
                'dial_code' => 855,
                'currency_name' => 'Cambodian riel',
                'currency_symbol' => 'KHR',
                'currency_code' => 'KHR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            36 => array
            (
                'id' => 37,
                'code' => 'CM',
                'name' => 'Cameroon',
                'dial_code' => 237,
                'currency_name' => 'Central African CFA ',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XAF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            37 => array
            (
                'id' => 38,
                'code' => 'CA',
                'name' => 'Canada',
                'dial_code' => 1,
                'currency_name' => 'Canadian dollar',
                'currency_symbol' => '$',
                'currency_code' => 'CAD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            38 => array
            (
                'id' => 39,
                'code' => 'CV',
                'name' => 'Cape Verde',
                'dial_code' => 238,
                'currency_name' => 'Cape Verdean escudo',
                'currency_symbol' => 'Esc',
                'currency_code' => 'CVE',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            39 => array
            (
                'id' => 40,
                'code' => 'KY',
                'name' => 'Cayman Islands',
                'dial_code' => 1345,
                'currency_name' => 'Cayman Islands dolla',
                'currency_symbol' => '$',
                'currency_code' => 'KYD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            40 => array
            (
                'id' => 41,
                'code' => 'CF',
                'name' => 'Central African Republic',
                'dial_code' => 236,
                'currency_name' => 'Central African CFA ',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XAF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            41 => array
            (
                'id' => 42,
                'code' => 'TD',
                'name' => 'Chad',
                'dial_code' => 235,
                'currency_name' => 'Central African CFA ',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XAF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            42 => array
            (
                'id' => 43,
                'code' => 'CL',
                'name' => 'Chile',
                'dial_code' => 56,
                'currency_name' => 'Chilean peso',
                'currency_symbol' => '$',
                'currency_code' => 'CLP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            43 => array
            (
                'id' => 44,
                'code' => 'CN',
                'name' => 'China',
                'dial_code' => 86,
                'currency_name' => 'Chinese yuan',
                'currency_symbol' => '¥',
                'currency_code' => 'CNY',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            44 => array
            (
                'id' => 45,
                'code' => 'CX',
                'name' => 'Christmas Island',
                'dial_code' => 61,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            45 => array
            (
                'id' => 46,
                'code' => 'CC',
                'name' => 'Cocos (Keeling), Islands',
                'dial_code' => 672,
                'currency_name' => 'Australian dollar',
                'currency_symbol' => '$',
                'currency_code' => 'AUD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            46 => array
            (
                'id' => 47,
                'code' => 'CO',
                'name' => 'Colombia',
                'dial_code' => 57,
                'currency_name' => 'Colombian peso',
                'currency_symbol' => '$',
                'currency_code' => 'COP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            47 => array
            (
                'id' => 48,
                'code' => 'KM',
                'name' => 'Comoros',
                'dial_code' => 269,
                'currency_name' => 'Comorian franc',
                'currency_symbol' => 'Fr',
                'currency_code' => 'KMF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            48 => array
            (
                'id' => 49,
                'code' => 'CG',
                'name' => 'Congo',
                'dial_code' => 242,
                'currency_name' => 'Congolese franc',
                'currency_symbol' => 'FC',
                'currency_code' => 'CDF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            49 => array
            (
                'id' => 50,
                'code' => 'CD',
                'name' => 'Congo The Democratic Republic Of The',
                'dial_code' => 242,
                'currency_name' => 'Congolese franc',
                'currency_symbol' => 'FC',
                'currency_code' => 'CDF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            50 => array
            (
                'id' => 51,
                'code' => 'CK',
                'name' => 'Cook Islands',
                'dial_code' => 682,
                'currency_name' => 'New Zealand dollar',
                'currency_symbol' => '$',
                'currency_code' => 'NZD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            51 => array
            (
                'id' => 52,
                'code' => 'CR',
                'name' => 'Costa Rica',
                'dial_code' => 506,
                'currency_name' => 'Costa Rican colón',
                'currency_symbol' => 'CRC',
                'currency_code' => 'CRC',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            52 => array
            (
                'id' => 53,
                'code' => 'CI',
                'name' => 'Cote DIvoire (Ivory Coast),',
                'dial_code' => 225,
                'currency_name' => 'West African CFA franc',
                'currency_symbol' => 'CFA',
                'currency_code' => 'CFA',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            53 => array
            (
                'id' => 54,
                'code' => 'HR',
                'name' => 'Croatia (Hrvatska),',
                'dial_code' => 385,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            54 => array
            (
                'id' => 55,
                'code' => 'CU',
                'name' => 'Cuba',
                'dial_code' => 53,
                'currency_name' => 'Cuban convertible pe',
                'currency_symbol' => '$',
                'currency_code' => 'CUC',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            55 => array
            (
                'id' => 56,
                'code' => 'CY',
                'name' => 'Cyprus',
                'dial_code' => 357,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            56 => array
            (
                'id' => 57,
                'code' => 'CZ',
                'name' => 'Czech Republic',
                'dial_code' => 420,
                'currency_name' => 'Czech koruna',
                'currency_symbol' => 'CZK',
                'currency_code' => 'CZK',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            57 => array
            (
                'id' => 58,
                'code' => 'DK',
                'name' => 'Denmark',
                'dial_code' => 45,
                'currency_name' => 'Danish krone',
                'currency_symbol' => 'kr',
                'currency_code' => 'DKK',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            58 => array
            (
                'id' => 59,
                'code' => 'DJ',
                'name' => 'Djibouti',
                'dial_code' => 253,
                'currency_name' => 'Djiboutian franc',
                'currency_symbol' => 'Fr',
                'currency_code' => 'DJF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            59 => array
            (
                'id' => 60,
                'code' => 'DM',
                'name' => 'Dominica',
                'dial_code' => 1767,
                'currency_name' => 'East Caribbean dolla',
                'currency_symbol' => '$',
                'currency_code' => 'XCD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            60 => array
            (
                'id' => 61,
                'code' => 'DO',
                'name' => 'Dominican Republic',
                'dial_code' => 1809,
                'currency_name' => 'Dominican peso',
                'currency_symbol' => '$',
                'currency_code' => 'DOP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            61 => array
            (
                'id' => 62,
                'code' => 'TP',
                'name' => 'East Timor',
                'dial_code' => 670,
                'currency_name' => 'United States dollar',
                'currency_symbol' => '$',
                'currency_code' => 'USD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            62 => array
            (
                'id' => 63,
                'code' => 'EC',
                'name' => 'Ecuador',
                'dial_code' => 593,
                'currency_name' => 'United States dollar',
                'currency_symbol' => '$',
                'currency_code' => 'USD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            63 => array
            (
                'id' => 64,
                'code' => 'EG',
                'name' => 'Egypt',
                'dial_code' => 20,
                'currency_name' => 'Egyptian pound',
                'currency_symbol' => 'EGP',
                'currency_code' => 'EGP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            64 => array
            (
                'id' => 65,
                'code' => 'SV',
                'name' => 'El Salvador',
                'dial_code' => 503,
                'currency_name' => 'United States dollar',
                'currency_symbol' => '$',
                'currency_code' => 'USD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            65 => array
            (
                'id' => 66,
                'code' => 'GQ',
                'name' => 'Equatorial Guinea',
                'dial_code' => 240,
                'currency_name' => 'Central African CFA ',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XAF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            66 => array
            (
                'id' => 67,
                'code' => 'ER',
                'name' => 'Eritrea',
                'dial_code' => 291,
                'currency_name' => 'Eritrean nakfa',
                'currency_symbol' => 'Nfk',
                'currency_code' => 'ERN',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            67 => array
            (
                'id' => 68,
                'code' => 'EE',
                'name' => 'Estonia',
                'dial_code' => 372,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            68 => array
            (
                'id' => 69,
                'code' => 'ET',
                'name' => 'Ethiopia',
                'dial_code' => 251,
                'currency_name' => 'Ethiopian birr',
                'currency_symbol' => 'Br',
                'currency_code' => 'ETB',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            69 => array
            (
                'id' => 70,
                'code' => 'XA',
                'name' => 'External Territories of Australia',
                'dial_code' => 61,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            70 => array
            (
                'id' => 71,
                'code' => 'FK',
                'name' => 'Falkland Islands',
                'dial_code' => 500,
                'currency_name' => 'Falkland Islands pou',
                'currency_symbol' => '£',
                'currency_code' => 'FKP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            71 => array
            (
                'id' => 72,
                'code' => 'FO',
                'name' => 'Faroe Islands',
                'dial_code' => 298,
                'currency_name' => 'Danish krone',
                'currency_symbol' => 'kr',
                'currency_code' => 'DKK',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            72 => array
            (
                'id' => 73,
                'code' => 'FJ',
                'name' => 'Fiji Islands',
                'dial_code' => 679,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            73 => array
            (
                'id' => 74,
                'code' => 'FI',
                'name' => 'Finland',
                'dial_code' => 358,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            74 => array
            (
                'id' => 75,
                'code' => 'FR',
                'name' => 'France',
                'dial_code' => 33,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            75 => array
            (
                'id' => 76,
                'code' => 'GF',
                'name' => 'French Guiana',
                'dial_code' => 594,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            76 => array
            (
                'id' => 77,
                'code' => 'PF',
                'name' => 'French Polynesia',
                'dial_code' => 689,
                'currency_name' => 'CFP franc',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XPF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            77 => array
            (
                'id' => 78,
                'code' => 'TF',
                'name' => 'French Southern Territories',
                'dial_code' => 0,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            78 => array
            (
                'id' => 79,
                'code' => 'GA',
                'name' => 'Gabon',
                'dial_code' => 241,
                'currency_name' => 'Central African CFA ',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XAF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            79 => array
            (
                'id' => 80,
                'code' => 'GM',
                'name' => 'Gambia The',
                'dial_code' => 220,
                'currency_name' => 'Gambian dalasi',
                'currency_symbol' => 'D',
                'currency_code' => 'GMD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            80 => array
            (
                'id' => 81,
                'code' => 'GE',
                'name' => 'Georgia',
                'dial_code' => 995,
                'currency_name' => 'Georgian lari',
                'currency_symbol' => 'GEL',
                'currency_code' => 'GEL',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            81 => array
            (
                'id' => 82,
                'code' => 'DE',
                'name' => 'Germany',
                'dial_code' => 49,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            82 => array
            (
                'id' => 83,
                'code' => 'GH',
                'name' => 'Ghana',
                'dial_code' => 233,
                'currency_name' => 'Ghana cedi',
                'currency_symbol' => 'GHS',
                'currency_code' => 'GHS',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            83 => array
            (
                'id' => 84,
                'code' => 'GI',
                'name' => 'Gibraltar',
                'dial_code' => 350,
                'currency_name' => 'Gibraltar pound',
                'currency_symbol' => '£',
                'currency_code' => 'GIP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            84 => array
            (
                'id' => 85,
                'code' => 'GR',
                'name' => 'Greece',
                'dial_code' => 30,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            85 => array
            (
                'id' => 86,
                'code' => 'GL',
                'name' => 'Greenland',
                'dial_code' => 299,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            86 => array
            (
                'id' => 87,
                'code' => 'GD',
                'name' => 'Grenada',
                'dial_code' => 1473,
                'currency_name' => 'East Caribbean dolla',
                'currency_symbol' => '$',
                'currency_code' => 'XCD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            87 => array
            (
                'id' => 88,
                'code' => 'GP',
                'name' => 'Guadeloupe',
                'dial_code' => 590,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            88 => array
            (
                'id' => 89,
                'code' => 'GU',
                'name' => 'Guam',
                'dial_code' => 1671,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            89 => array
            (
                'id' => 90,
                'code' => 'GT',
                'name' => 'Guatemala',
                'dial_code' => 502,
                'currency_name' => 'Guatemalan quetzal',
                'currency_symbol' => 'Q',
                'currency_code' => 'GTQ',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            90 => array
            (
                'id' => 91,
                'code' => 'XU',
                'name' => 'Guernsey and Alderney',
                'dial_code' => 44,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            91 => array
            (
                'id' => 92,
                'code' => 'GN',
                'name' => 'Guinea',
                'dial_code' => 224,
                'currency_name' => 'Guinean franc',
                'currency_symbol' => 'Fr',
                'currency_code' => 'GNF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            92 => array
            (
                'id' => 93,
                'code' => 'GW',
                'name' => 'Guinea-Bissau',
                'dial_code' => 245,
                'currency_name' => 'West African CFA fra',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XOF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            93 => array
            (
                'id' => 94,
                'code' => 'GY',
                'name' => 'Guyana',
                'dial_code' => 592,
                'currency_name' => 'Guyanese dollar',
                'currency_symbol' => '$',
                'currency_code' => 'GYD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            94 => array
            (
                'id' => 95,
                'code' => 'HT',
                'name' => 'Haiti',
                'dial_code' => 509,
                'currency_name' => 'Haitian gourde',
                'currency_symbol' => 'G',
                'currency_code' => 'HTG',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            95 => array
            (
                'id' => 96,
                'code' => 'HM',
                'name' => 'Heard and McDonald Islands',
                'dial_code' => 0,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            96 => array
            (
                'id' => 97,
                'code' => 'HN',
                'name' => 'Honduras',
                'dial_code' => 504,
                'currency_name' => 'Honduran lempira',
                'currency_symbol' => 'L',
                'currency_code' => 'HNL',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            97 => array
            (
                'id' => 98,
                'code' => 'HK',
                'name' => 'Hong Kong.',
                'dial_code' => 852,
                'currency_name' => 'Hong Kong dollar',
                'currency_symbol' => 'HK$',
                'currency_code' => 'HKD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            98 => array
            (
                'id' => 99,
                'code' => 'HU',
                'name' => 'Hungary',
                'dial_code' => 36,
                'currency_name' => 'Hungarian forint',
                'currency_symbol' => 'Ft',
                'currency_code' => 'HUF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            99 => array
            (
                'id' => 100,
                'code' => 'IS',
                'name' => 'Iceland',
                'dial_code' => 354,
                'currency_name' => 'Icelandic krÃ³na',
                'currency_symbol' => 'kr',
                'currency_code' => 'ISK',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            100 => array
            (
                'id' => 101,
                'code' => 'IN',
                'name' => 'India',
                'dial_code' => 91,
                'currency_name' => 'Indian rupee',
                'currency_symbol' => 'INR',
                'currency_code' => 'INR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            101 => array
            (
                'id' => 102,
                'code' => 'ID',
                'name' => 'Indonesia',
                'dial_code' => 62,
                'currency_name' => 'Indonesian rupiah',
                'currency_symbol' => 'Rp',
                'currency_code' => 'IDR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            102 => array
            (
                'id' => 103,
                'code' => 'IR',
                'name' => 'Iran',
                'dial_code' => 98,
                'currency_name' => 'Iranian rial',
                'currency_symbol' => 'IRR',
                'currency_code' => 'IRR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            103 => array
            (
                'id' => 104,
                'code' => 'IQ',
                'name' => 'Iraq',
                'dial_code' => 964,
                'currency_name' => 'Iraqi dinar',
                'currency_symbol' => 'IQD',
                'currency_code' => 'IQD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            104 => array
            (
                'id' => 105,
                'code' => 'IE',
                'name' => 'Ireland',
                'dial_code' => 353,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            105 => array
            (
                'id' => 106,
                'code' => 'IL',
                'name' => 'Israel',
                'dial_code' => 972,
                'currency_name' => 'Israeli new shekel',
                'currency_symbol' => 'ILS',
                'currency_code' => 'ILS',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            106 => array
            (
                'id' => 107,
                'code' => 'IT',
                'name' => 'Italy',
                'dial_code' => 39,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            107 => array
            (
                'id' => 108,
                'code' => 'JM',
                'name' => 'Jamaica',
                'dial_code' => 1876,
                'currency_name' => 'Jamaican dollar',
                'currency_symbol' => '$',
                'currency_code' => 'JMD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            108 => array
            (
                'id' => 109,
                'code' => 'JP',
                'name' => 'Japan',
                'dial_code' => 81,
                'currency_name' => 'Japanese yen',
                'currency_symbol' => '¥',
                'currency_code' => 'JPY',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            109 => array
            (
                'id' => 110,
                'code' => 'XJ',
                'name' => 'Jersey',
                'dial_code' => 44,
                'currency_name' => 'British pound',
                'currency_symbol' => '£',
                'currency_code' => 'GBP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            110 => array
            (
                'id' => 111,
                'code' => 'JO',
                'name' => 'Jordan',
                'dial_code' => 962,
                'currency_name' => 'Jordanian dinar',
                'currency_symbol' => 'KD',
                'currency_code' => 'JOD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            111 => array
            (
                'id' => 112,
                'code' => 'KZ',
                'name' => 'Kazakhstan',
                'dial_code' => 7,
                'currency_name' => 'Kazakhstani tenge',
                'currency_symbol' => '₸',
                'currency_code' => 'KZT',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            112 => array
            (
                'id' => 113,
                'code' => 'KE',
                'name' => 'Kenya',
                'dial_code' => 254,
                'currency_name' => 'Kenyan shilling',
                'currency_symbol' => 'Sh',
                'currency_code' => 'KES',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            113 => array
            (
                'id' => 114,
                'code' => 'KI',
                'name' => 'Kiribati',
                'dial_code' => 686,
                'currency_name' => 'Australian dollar',
                'currency_symbol' => '$',
                'currency_code' => 'AUD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            114 => array
            (
                'id' => 115,
                'code' => 'KP',
                'name' => 'Korea North',
                'dial_code' => 850,
                'currency_name' => 'North Korean won',
                'currency_symbol' => '₩',
                'currency_code' => 'KPW',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            115 => array
            (
                'id' => 116,
                'code' => 'KR',
                'name' => 'Korea South',
                'dial_code' => 82,
                'currency_name' => 'South Korean won',
                'currency_symbol' => '₩',
                'currency_code' => 'KRW',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            116 => array
            (
                'id' => 117,
                'code' => 'KW',
                'name' => 'Kuwait',
                'dial_code' => 965,
                'currency_name' => 'Kuwaiti dinar',
                'currency_symbol' => 'KD',
                'currency_code' => 'KWD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            117 => array
            (
                'id' => 118,
                'code' => 'KG',
                'name' => 'Kyrgyzstan',
                'dial_code' => 996,
                'currency_name' => 'Kyrgyzstani som',
                'currency_symbol' => 'KGS',
                'currency_code' => 'KGS',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            118 => array
            (
                'id' => 119,
                'code' => 'LA',
                'name' => 'Laos',
                'dial_code' => 856,
                'currency_name' => 'Lao kip',
                'currency_symbol' => 'LAK',
                'currency_code' => 'LAK',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            119 => array
            (
                'id' => 120,
                'code' => 'LV',
                'name' => 'Latvia',
                'dial_code' => 371,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            120 => array
            (
                'id' => 121,
                'code' => 'LB',
                'name' => 'Lebanon',
                'dial_code' => 961,
                'currency_name' => 'Lebanese pound',
                'currency_symbol' => 'LBP',
                'currency_code' => 'LBP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            121 => array
            (
                'id' => 122,
                'code' => 'LS',
                'name' => 'Lesotho',
                'dial_code' => 266,
                'currency_name' => 'Lesotho loti',
                'currency_symbol' => 'L',
                'currency_code' => 'LSL',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            122 => array
            (
                'id' => 123,
                'code' => 'LR',
                'name' => 'Liberia',
                'dial_code' => 231,
                'currency_name' => 'Liberian dollar',
                'currency_symbol' => '$',
                'currency_code' => 'LRD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            123 => array
            (
                'id' => 124,
                'code' => 'LY',
                'name' => 'Libya',
                'dial_code' => 218,
                'currency_name' => 'Libyan dinar',
                'currency_symbol' => 'LD',
                'currency_code' => 'LYD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            124 => array
            (
                'id' => 125,
                'code' => 'LI',
                'name' => 'Liechtenstein',
                'dial_code' => 423,
                'currency_name' => 'Swiss franc',
                'currency_symbol' => 'Fr',
                'currency_code' => 'CHF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            125 => array
            (
                'id' => 126,
                'code' => 'LT',
                'name' => 'Lithuania',
                'dial_code' => 370,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            126 => array
            (
                'id' => 127,
                'code' => 'LU',
                'name' => 'Luxembourg',
                'dial_code' => 352,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            127 => array
            (
                'id' => 128,
                'code' => 'MO',
                'name' => 'Macau S.A.R.',
                'dial_code' => 853,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            128 => array
            (
                'id' => 129,
                'code' => 'MK',
                'name' => 'Macedonia',
                'dial_code' => 389,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            129 => array
            (
                'id' => 130,
                'code' => 'MG',
                'name' => 'Madagascar',
                'dial_code' => 261,
                'currency_name' => 'Malagasy ariary',
                'currency_symbol' => 'Ar',
                'currency_code' => 'MGA',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            130 => array
            (
                'id' => 131,
                'code' => 'MW',
                'name' => 'Malawi',
                'dial_code' => 265,
                'currency_name' => 'Malawian kwacha',
                'currency_symbol' => 'MK',
                'currency_code' => 'MWK',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            131 => array
            (
                'id' => 132,
                'code' => 'MY',
                'name' => 'Malaysia',
                'dial_code' => 60,
                'currency_name' => 'Malaysian ringgit',
                'currency_symbol' => 'RM',
                'currency_code' => 'MYR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            132 => array
            (
                'id' => 133,
                'code' => 'MV',
                'name' => 'Maldives',
                'dial_code' => 960,
                'currency_name' => 'Maldivian rufiyaa',
                'currency_symbol' => 'MVR',
                'currency_code' => 'MVR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            133 => array
            (
                'id' => 134,
                'code' => 'ML',
                'name' => 'Mali',
                'dial_code' => 223,
                'currency_name' => 'West African CFA fra',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XOF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            134 => array
            (
                'id' => 135,
                'code' => 'MT',
                'name' => 'Malta',
                'dial_code' => 356,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            135 => array
            (
                'id' => 136,
                'code' => 'XM',
                'name' => 'Man (Isle of),',
                'dial_code' => 44,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            136 => array
            (
                'id' => 137,
                'code' => 'MH',
                'name' => 'Marshall Islands',
                'dial_code' => 692,
                'currency_name' => 'United States dollar',
                'currency_symbol' => '$',
                'currency_code' => 'USD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            137 => array
            (
                'id' => 138,
                'code' => 'MQ',
                'name' => 'Martinique',
                'dial_code' => 596,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            138 => array
            (
                'id' => 139,
                'code' => 'MR',
                'name' => 'Mauritania',
                'dial_code' => 222,
                'currency_name' => 'Mauritanian ouguiya',
                'currency_symbol' => 'UM',
                'currency_code' => 'MRO',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            139 => array
            (
                'id' => 140,
                'code' => 'MU',
                'name' => 'Mauritius',
                'dial_code' => 230,
                'currency_name' => 'Mauritius rupee',
                'currency_symbol' => 'Rs',
                'currency_code' => 'MUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            140 => array
            (
                'id' => 141,
                'code' => 'YT',
                'name' => 'Mayotte',
                'dial_code' => 269,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            141 => array
            (
                'id' => 142,
                'code' => 'MX',
                'name' => 'Mexico',
                'dial_code' => 52,
                'currency_name' => 'Mexican peso',
                'currency_symbol' => '$',
                'currency_code' => 'MXN',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            142 => array
            (
                'id' => 143,
                'code' => 'FM',
                'name' => 'Micronesia',
                'dial_code' => 691,
                'currency_name' => 'United States Dollar',
                'currency_symbol' => '$',
                'currency_code' => 'USD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            143 => array
            (
                'id' => 144,
                'code' => 'MD',
                'name' => 'Moldova',
                'dial_code' => 373,
                'currency_name' => 'Moldovan leu',
                'currency_symbol' => 'L',
                'currency_code' => 'MDL',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            144 => array
            (
                'id' => 145,
                'code' => 'MC',
                'name' => 'Monaco',
                'dial_code' => 377,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            145 => array
            (
                'id' => 146,
                'code' => 'MN',
                'name' => 'Mongolia',
                'dial_code' => 976,
                'currency_name' => 'Mongolian Tögrög',
                'currency_symbol' => '₮',
                'currency_code' => 'MNT',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            146 => array
            (
                'id' => 147,
                'code' => 'MS',
                'name' => 'Montserrat',
                'dial_code' => 1664,
                'currency_name' => 'East Caribbean dolla',
                'currency_symbol' => '$',
                'currency_code' => 'XCD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            147 => array
            (
                'id' => 148,
                'code' => 'MA',
                'name' => 'Morocco',
                'dial_code' => 212,
                'currency_name' => 'Moroccan dirham',
                'currency_symbol' => 'MAD',
                'currency_code' => 'MAD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            148 => array
            (
                'id' => 149,
                'code' => 'MZ',
                'name' => 'Mozambique',
                'dial_code' => 258,
                'currency_name' => 'Mozambican metical',
                'currency_symbol' => 'MT',
                'currency_code' => 'MZN',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            149 => array
            (
                'id' => 150,
                'code' => 'MM',
                'name' => 'Myanmar',
                'dial_code' => 95,
                'currency_name' => 'Burmese kyat',
                'currency_symbol' => 'Ks',
                'currency_code' => 'MMK',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            150 => array
            (
                'id' => 151,
                'code' => 'NA',
                'name' => 'Namibia',
                'dial_code' => 264,
                'currency_name' => 'Namibian dollar',
                'currency_symbol' => '$',
                'currency_code' => 'NAD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            151 => array
            (
                'id' => 152,
                'code' => 'NR',
                'name' => 'Nauru',
                'dial_code' => 674,
                'currency_name' => 'Australian dollar',
                'currency_symbol' => '$',
                'currency_code' => 'AUD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            152 => array
            (
                'id' => 153,
                'code' => 'NP',
                'name' => 'Nepal',
                'dial_code' => 977,
                'currency_name' => 'Nepalese rupee',
                'currency_symbol' => 'Rs.',
                'currency_code' => 'NPR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            153 => array
            (
                'id' => 154,
                'code' => 'AN',
                'name' => 'Netherlands Antilles',
                'dial_code' => 599,
                'currency_name' => 'Netherlands Antillean guilder',
                'currency_symbol' => 'NAf',
                'currency_code' => 'ANG',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            154 => array
            (
                'id' => 155,
                'code' => 'NL',
                'name' => 'Netherlands The',
                'dial_code' => 31,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            155 => array
            (
                'id' => 156,
                'code' => 'NC',
                'name' => 'New Caledonia',
                'dial_code' => 687,
                'currency_name' => 'CFP franc',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XPF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            156 => array
            (
                'id' => 157,
                'code' => 'NZ',
                'name' => 'New Zealand',
                'dial_code' => 64,
                'currency_name' => 'New Zealand dollar',
                'currency_symbol' => '$',
                'currency_code' => 'NZD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            157 => array
            (
                'id' => 158,
                'code' => 'NI',
                'name' => 'Nicaragua',
                'dial_code' => 505,
                'currency_name' => 'Nicaraguan córdoba',
                'currency_symbol' => 'C$',
                'currency_code' => 'NIO',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            158 => array
            (
                'id' => 159,
                'code' => 'NE',
                'name' => 'Niger',
                'dial_code' => 227,
                'currency_name' => 'West African CFA fra',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XOF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            159 => array
            (
                'id' => 160,
                'code' => 'NG',
                'name' => 'Nigeria',
                'dial_code' => 234,
                'currency_name' => 'Nigerian naira',
                'currency_symbol' => '₦',
                'currency_code' => 'NGN',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            160 => array
            (
                'id' => 161,
                'code' => 'NU',
                'name' => 'Niue',
                'dial_code' => 683,
                'currency_name' => 'New Zealand dollar',
                'currency_symbol' => '$',
                'currency_code' => 'NZD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            161 => array
            (
                'id' => 162,
                'code' => 'NF',
                'name' => 'Norfolk Island',
                'dial_code' => 672,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            162 => array
            (
                'id' => 163,
                'code' => 'MP',
                'name' => 'Northern Mariana Islands',
                'dial_code' => 1670,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            163 => array
            (
                'id' => 164,
                'code' => 'NO',
                'name' => 'Norway',
                'dial_code' => 47,
                'currency_name' => 'Norwegian krone',
                'currency_symbol' => 'kr',
                'currency_code' => 'NOK',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            164 => array
            (
                'id' => 165,
                'code' => 'OM',
                'name' => 'Oman',
                'dial_code' => 968,
                'currency_name' => 'Omani rial',
                'currency_symbol' => 'OMR',
                'currency_code' => 'OMR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            165 => array
            (
                'id' => 166,
                'code' => 'PK',
                'name' => 'Pakistan',
                'dial_code' => 92,
                'currency_name' => 'Pakistani rupee',
                'currency_symbol' => '₨',
                'currency_code' => 'PKR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            166 => array
            (
                'id' => 167,
                'code' => 'PW',
                'name' => 'Palau',
                'dial_code' => 680,
                'currency_name' => 'United States Dollar',
                'currency_symbol' => '$',
                'currency_code' => 'USD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            167 => array
            (
                'id' => 168,
                'code' => 'PS',
                'name' => 'Palestinian Territory Occupied',
                'dial_code' => 970,
                'currency_name' => 'Palestine pound',
                'currency_symbol' => '£',
                'currency_code' => 'PSP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            168 => array
            (
                'id' => 169,
                'code' => 'PA',
                'name' => 'Panama',
                'dial_code' => 507,
                'currency_name' => 'Panamanian balboa',
                'currency_symbol' => 'PAB',
                'currency_code' => 'PAB',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            169 => array
            (
                'id' => 170,
                'code' => 'PG',
                'name' => 'Papua new Guinea',
                'dial_code' => 675,
                'currency_name' => 'Papua New Guinean ki',
                'currency_symbol' => 'K',
                'currency_code' => 'PGK',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            170 => array
            (
                'id' => 171,
                'code' => 'PY',
                'name' => 'Paraguay',
                'dial_code' => 595,
                'currency_name' => 'Paraguayan guaraní',
                'currency_symbol' => '₲',
                'currency_code' => 'PYG',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            171 => array
            (
                'id' => 172,
                'code' => 'PE',
                'name' => 'Peru',
                'dial_code' => 51,
                'currency_name' => 'Peruvian nuevo sol',
                'currency_symbol' => 'PEN',
                'currency_code' => 'PEN',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            172 => array
            (
                'id' => 173,
                'code' => 'PH',
                'name' => 'Philippines',
                'dial_code' => 63,
                'currency_name' => 'Philippine peso',
                'currency_symbol' => '₱',
                'currency_code' => 'PHP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            173 => array
            (
                'id' => 174,
                'code' => 'PN',
                'name' => 'Pitcairn Island',
                'dial_code' => 0,
                'currency_name' => 'Pitkern Ailen dollar',
                'currency_symbol' => '$',
                'currency_code' => 'PND',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            174 => array
            (
                'id' => 175,
                'code' => 'PL',
                'name' => 'Poland',
                'dial_code' => 48,
                'currency_name' => 'Zloty zloty',
                'currency_symbol' => 'PLN',
                'currency_code' => 'PLN',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            175 => array
            (
                'id' => 176,
                'code' => 'PT',
                'name' => 'Portugal',
                'dial_code' => 351,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            176 => array
            (
                'id' => 177,
                'code' => 'PR',
                'name' => 'Puerto Rico',
                'dial_code' => 1787,
                'currency_name' => 'United States Dollar',
                'currency_symbol' => '$',
                'currency_code' => 'USD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            177 => array
            (
                'id' => 178,
                'code' => 'QA',
                'name' => 'Qatar',
                'dial_code' => 974,
                'currency_name' => 'Qatari riyal',
                'currency_symbol' => 'QR',
                'currency_code' => 'QAR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            178 => array
            (
                'id' => 179,
                'code' => 'RE',
                'name' => 'Reunion',
                'dial_code' => 262,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            179 => array
            (
                'id' => 180,
                'code' => 'RO',
                'name' => 'Romania',
                'dial_code' => 40,
                'currency_name' => 'Romanian leu',
                'currency_symbol' => 'lei',
                'currency_code' => 'RON',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            180 => array
            (
                'id' => 181,
                'code' => 'RU',
                'name' => 'Russia',
                'dial_code' => 70,
                'currency_name' => 'Russian ruble',
                'currency_symbol' => '₽',
                'currency_code' => 'RUB',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            181 => array
            (
                'id' => 182,
                'code' => 'RW',
                'name' => 'Rwanda',
                'dial_code' => 250,
                'currency_name' => 'Rwandan franc',
                'currency_symbol' => 'Fr',
                'currency_code' => 'RWF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            182 => array
            (
                'id' => 183,
                'code' => 'SH',
                'name' => 'Saint Helena',
                'dial_code' => 290,
                'currency_name' => 'Saint Helena pound',
                'currency_symbol' => '£',
                'currency_code' => 'SHP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            183 => array
            (
                'id' => 184,
                'code' => 'KN',
                'name' => 'Saint Kitts And Nevis',
                'dial_code' => 1869,
                'currency_name' => 'East Caribbean dolla',
                'currency_symbol' => '$',
                'currency_code' => 'XCD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            184 => array
            (
                'id' => 185,
                'code' => 'LC',
                'name' => 'Saint Lucia',
                'dial_code' => 1758,
                'currency_name' => 'East Caribbean dolla',
                'currency_symbol' => '$',
                'currency_code' => 'XCD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            185 => array
            (
                'id' => 186,
                'code' => 'PM',
                'name' => 'Saint Pierre and Miquelon',
                'dial_code' => 508,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            186 => array
            (
                'id' => 187,
                'code' => 'VC',
                'name' => 'Saint Vincent And The Grenadines',
                'dial_code' => 1784,
                'currency_name' => 'East Caribbean dolla',
                'currency_symbol' => '$',
                'currency_code' => 'XCD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            187 => array
            (
                'id' => 188,
                'code' => 'WS',
                'name' => 'Samoa',
                'dial_code' => 684,
                'currency_name' => 'Samoan tālā',
                'currency_symbol' => 'T',
                'currency_code' => 'WST',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            188 => array
            (
                'id' => 189,
                'code' => 'SM',
                'name' => 'San Marino',
                'dial_code' => 378,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            189 => array
            (
                'id' => 190,
                'code' => 'ST',
                'name' => 'Sao Tome and Principe',
                'dial_code' => 239,
                'currency_name' => 'São Tomé and Príncipe dobra',
                'currency_symbol' => 'Db',
                'currency_code' => 'STD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            190 => array
            (
                'id' => 191,
                'code' => 'SA',
                'name' => 'Saudi Arabia',
                'dial_code' => 966,
                'currency_name' => 'Saudi riyal',
                'currency_symbol' => 'SR',
                'currency_code' => 'SAR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            191 => array
            (
                'id' => 192,
                'code' => 'SN',
                'name' => 'Senegal',
                'dial_code' => 221,
                'currency_name' => 'West African CFA fra',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XOF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            192 => array
            (
                'id' => 193,
                'code' => 'RS',
                'name' => 'Serbia',
                'dial_code' => 381,
                'currency_name' => 'Serbian dinar',
                'currency_symbol' => 'din.',
                'currency_code' => 'RSD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            193 => array
            (
                'id' => 194,
                'code' => 'SC',
                'name' => 'Seychelles',
                'dial_code' => 248,
                'currency_name' => 'Seychellois rupee',
                'currency_symbol' => 'SRe',
                'currency_code' => 'SCR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            194 => array
            (
                'id' => 195,
                'code' => 'SL',
                'name' => 'Sierra Leone',
                'dial_code' => 232,
                'currency_name' => 'Sierra Leonean leone',
                'currency_symbol' => 'Le',
                'currency_code' => 'SLL',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            195 => array
            (
                'id' => 196,
                'code' => 'SG',
                'name' => 'Singapore',
                'dial_code' => 65,
                'currency_name' => 'Brunei dollar',
                'currency_symbol' => '$',
                'currency_code' => 'BND',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            196 => array
            (
                'id' => 197,
                'code' => 'SK',
                'name' => 'Slovakia',
                'dial_code' => 421,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            197 => array
            (
                'id' => 198,
                'code' => 'SI',
                'name' => 'Slovenia',
                'dial_code' => 386,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            198 => array
            (
                'id' => 199,
                'code' => 'XG',
                'name' => 'Smaller Territories of the UK',
                'dial_code' => 44,
                'currency_name' => 'British Pound',
                'currency_symbol' => '£',
                'currency_code' => 'GBP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            199 => array
            (
                'id' => 200,
                'code' => 'SB',
                'name' => 'Solomon Islands',
                'dial_code' => 677,
                'currency_name' => 'Solomon Islands doll',
                'currency_symbol' => '$',
                'currency_code' => 'SBD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            200 => array
            (
                'id' => 201,
                'code' => 'SO',
                'name' => 'Somalia',
                'dial_code' => 252,
                'currency_name' => 'Somali shilling',
                'currency_symbol' => 'Sh',
                'currency_code' => 'SOS',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            201 => array
            (
                'id' => 202,
                'code' => 'ZA',
                'name' => 'South Africa',
                'dial_code' => 27,
                'currency_name' => 'South African rand',
                'currency_symbol' => 'R',
                'currency_code' => 'ZAR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            202 => array
            (
                'id' => 203,
                'code' => 'GS',
                'name' => 'South Georgia',
                'dial_code' => 500,
                'currency_name' => 'Georgian lari',
                'currency_symbol' => 'GEL',
                'currency_code' => 'GEL',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            203 => array
            (
                'id' => 204,
                'code' => 'SS',
                'name' => 'South Sudan',
                'dial_code' => 211,
                'currency_name' => 'South Sudanese pound',
                'currency_symbol' => '£',
                'currency_code' => 'SSP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            204 => array
            (
                'id' => 205,
                'code' => 'ES',
                'name' => 'Spain',
                'dial_code' => 34,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            205 => array
            (
                'id' => 206,
                'code' => 'LK',
                'name' => 'Sri Lanka',
                'dial_code' => 94,
                'currency_name' => 'Sri Lankan rupee',
                'currency_symbol' => 'Rs',
                'currency_code' => 'LKR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            206 => array
            (
                'id' => 207,
                'code' => 'SD',
                'name' => 'Sudan',
                'dial_code' => 249,
                'currency_name' => 'Sudanese pound',
                'currency_symbol' => '£SD',
                'currency_code' => 'SDG',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            207 => array
            (
                'id' => 208,
                'code' => 'SR',
                'name' => 'Suriname',
                'dial_code' => 597,
                'currency_name' => 'Surinamese dollar',
                'currency_symbol' => '$',
                'currency_code' => 'SRD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            208 => array
            (
                'id' => 209,
                'code' => 'SJ',
                'name' => 'Svalbard And Jan Mayen Islands',
                'dial_code' => 47,
                'currency_name' => 'krone, NOK',
                'currency_symbol' => 'kr',
                'currency_code' => 'NOK',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            209 => array
            (
                'id' => 210,
                'code' => 'SZ',
                'name' => 'Swaziland',
                'dial_code' => 268,
                'currency_name' => 'Swazi lilangeni',
                'currency_symbol' => 'L',
                'currency_code' => 'SZL',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            210 => array
            (
                'id' => 211,
                'code' => 'SE',
                'name' => 'Sweden',
                'dial_code' => 46,
                'currency_name' => 'Swedish krona',
                'currency_symbol' => 'kr',
                'currency_code' => 'SEK',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            211 => array
            (
                'id' => 212,
                'code' => 'CH',
                'name' => 'Switzerland',
                'dial_code' => 41,
                'currency_name' => 'Swiss franc',
                'currency_symbol' => 'Fr',
                'currency_code' => 'CHF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            212 => array
            (
                'id' => 213,
                'code' => 'SY',
                'name' => 'Syria',
                'dial_code' => 963,
                'currency_name' => 'Syrian pound',
                'currency_symbol' => '£s',
                'currency_code' => 'SYP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            213 => array
            (
                'id' => 214,
                'code' => 'TW',
                'name' => 'Taiwan',
                'dial_code' => 886,
                'currency_name' => 'New Taiwan dollar',
                'currency_symbol' => '$',
                'currency_code' => 'TWD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            214 => array
            (
                'id' => 215,
                'code' => 'TJ',
                'name' => 'Tajikistan',
                'dial_code' => 992,
                'currency_name' => 'Tajikistani somoni',
                'currency_symbol' => 'TJS',
                'currency_code' => 'TJS',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            215 => array
            (
                'id' => 216,
                'code' => 'TZ',
                'name' => 'Tanzania',
                'dial_code' => 255,
                'currency_name' => 'Tanzanian shilling',
                'currency_symbol' => 'Sh',
                'currency_code' => 'TZS',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            216 => array
            (
                'id' => 217,
                'code' => 'TH',
                'name' => 'Thailand',
                'dial_code' => 66,
                'currency_name' => 'Thai baht',
                'currency_symbol' => '฿',
                'currency_code' => 'THB',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            217 => array
            (
                'id' => 218,
                'code' => 'TG',
                'name' => 'Togo',
                'dial_code' => 228,
                'currency_name' => 'West African CFA fra',
                'currency_symbol' => 'Fr',
                'currency_code' => 'XOF',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            218 => array
            (
                'id' => 219,
                'code' => 'TK',
                'name' => 'Tokelau',
                'dial_code' => 690,
                'currency_name' => 'New Zealand dollar',
                'currency_symbol' => '$',
                'currency_code' => 'NZD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            219 => array
            (
                'id' => 220,
                'code' => 'TO',
                'name' => 'Tonga',
                'dial_code' => 676,
                'currency_name' => 'Tongan paʻanga',
                'currency_symbol' => 'T$',
                'currency_code' => 'TOP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            220 => array
            (
                'id' => 221,
                'code' => 'TT',
                'name' => 'Trinidad And Tobago',
                'dial_code' => 1868,
                'currency_name' => 'Trinidad and Tobago ',
                'currency_symbol' => '$',
                'currency_code' => 'TTD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            221 => array
            (
                'id' => 222,
                'code' => 'TN',
                'name' => 'Tunisia',
                'dial_code' => 216,
                'currency_name' => 'Tunisian dinar',
                'currency_symbol' => 'DT',
                'currency_code' => 'TND',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            222 => array
            (
                'id' => 223,
                'code' => 'TR',
                'name' => 'Turkey',
                'dial_code' => 90,
                'currency_name' => 'Turkish lira',
                'currency_symbol' => '₺',
                'currency_code' => 'TRY',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            223 => array
            (
                'id' => 224,
                'code' => 'TM',
                'name' => 'Turkmenistan',
                'dial_code' => 7370,
                'currency_name' => 'Turkmenistan manat',
                'currency_symbol' => 'T',
                'currency_code' => 'TMT',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            224 => array
            (
                'id' => 225,
                'code' => 'TC',
                'name' => 'Turks And Caicos Islands',
                'dial_code' => 1649,
                'currency_name' => 'United States dollar',
                'currency_symbol' => '$',
                'currency_code' => 'USD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            225 => array
            (
                'id' => 226,
                'code' => 'TV',
                'name' => 'Tuvalu',
                'dial_code' => 688,
                'currency_name' => 'Australian dollar',
                'currency_symbol' => '$',
                'currency_code' => 'AUD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            226 => array
            (
                'id' => 227,
                'code' => 'UG',
                'name' => 'Uganda',
                'dial_code' => 256,
                'currency_name' => 'Ugandan shilling',
                'currency_symbol' => 'Sh',
                'currency_code' => 'UGX',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            227 => array
            (
                'id' => 228,
                'code' => 'UA',
                'name' => 'Ukraine',
                'dial_code' => 380,
                'currency_name' => 'Ukrainian hryvnia',
                'currency_symbol' => 'UAH',
                'currency_code' => 'UAH',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            228 => array
            (
                'id' => 229,
                'code' => 'AE',
                'name' => 'United Arab Emirates',
                'dial_code' => 971,
                'currency_name' => 'United Arab Emirates',
                'currency_symbol' => 'AED',
                'currency_code' => 'AED',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            229 => array
            (
                'id' => 230,
                'code' => 'GB',
                'name' => 'United Kingdom',
                'dial_code' => 44,
                'currency_name' => 'British pound',
                'currency_symbol' => 'Â£',
                'currency_code' => 'GBP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            230 => array
            (
                'id' => 231,
                'code' => 'US',
                'name' => 'United States',
                'dial_code' => 1,
                'currency_name' => 'United States dollar',
                'currency_symbol' => '$',
                'currency_code' => 'USD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            231 => array
            (
                'id' => 232,
                'code' => 'UM',
                'name' => 'United States Minor Outlying Islands',
                'dial_code' => 1,
                'currency_name' => 'United States dollar',
                'currency_symbol' => '$',
                'currency_code' => 'USD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            232 => array
            (
                'id' => 233,
                'code' => 'UY',
                'name' => 'Uruguay',
                'dial_code' => 598,
                'currency_name' => 'Uruguayan peso',
                'currency_symbol' => '$',
                'currency_code' => 'UYU',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            233 => array
            (
                'id' => 234,
                'code' => 'UZ',
                'name' => 'Uzbekistan',
                'dial_code' => 998,
                'currency_name' => 'Uzbekistani som',
                'currency_symbol' => '',
                'currency_code' => 'UZS',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            234 => array
            (
                'id' => 235,
                'code' => 'VU',
                'name' => 'Vanuatu',
                'dial_code' => 678,
                'currency_name' => 'Vanuatu vatu',
                'currency_symbol' => 'Vt',
                'currency_code' => 'VUV',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            235 => array
            (
                'id' => 236,
                'code' => 'VA',
                'name' => 'Vatican City State (Holy See),',
                'dial_code' => 39,
                'currency_name' => 'Euro',
                'currency_symbol' => '€',
                'currency_code' => 'EUR',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            236 => array
            (
                'id' => 237,
                'code' => 'VE',
                'name' => 'Venezuela',
                'dial_code' => 58,
                'currency_name' => 'Venezuelan bolívar',
                'currency_symbol' => 'Bs.',
                'currency_code' => 'VES',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            237 => array
            (
                'id' => 238,
                'code' => 'VN',
                'name' => 'Vietnam',
                'dial_code' => 84,
                'currency_name' => 'Vietnamese dong',
                'currency_symbol' => '₫',
                'currency_code' => 'VND',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            238 => array
            (
                'id' => 239,
                'code' => 'VG',
                'name' => 'Virgin Islands (British),',
                'dial_code' => 1284,
                'currency_name' => 'British Pound',
                'currency_symbol' => '£',
                'currency_code' => 'GBP',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            239 => array
            (
                'id' => 240,
                'code' => 'VI',
                'name' => 'Virgin Islands (US),',
                'dial_code' => 1340,
                'currency_name' => 'US dollar',
                'currency_symbol' => '$',
                'currency_code' => 'USD',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            240 => array
            (
                'id' => 241,
                'code' => 'WF',
                'name' => 'Wallis And Futuna Islands',
                'dial_code' => 681,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            241 => array
            (
                'id' => 242,
                'code' => 'EH',
                'name' => 'Western Sahara',
                'dial_code' => 212,
                'currency_name' => '',
                'currency_symbol' => '',
                'currency_code' => '',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            242 => array
            (
                'id' => 243,
                'code' => 'YE',
                'name' => 'Yemen',
                'dial_code' => 967,
                'currency_name' => 'Yemeni rial',
                'currency_symbol' => 'YER',
                'currency_code' => 'YER',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            243 => array
            (
                'id' => 244,
                'code' => 'YU',
                'name' => 'Yugoslavia',
                'dial_code' => 38,
                'currency_name' => 'Yugoslav dinar',
                'currency_symbol' => 'din.',
                'currency_code' => 'YUG',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            244 => array
            (
                'id' => 245,
                'code' => 'ZM',
                'name' => 'Zambia',
                'dial_code' => 260,
                'currency_name' => 'Zambian kwacha',
                'currency_symbol' => 'ZK',
                'currency_code' => 'ZMW',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            ),

            245 => array
            (
                'id' => 246,
                'code' => 'ZW',
                'name' => 'Zimbabwe',
                'dial_code' => 263,
                'currency_name' => 'Zimbabwe dollar',
                'currency_symbol' => '$',
                'currency_code' => 'ZWL',
                'created_at' => '2021-05-07 09:19:51',
                'updated_at' => '2021-05-07 09:19:51'
            )
        ));
    }
}
