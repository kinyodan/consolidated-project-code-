<?php
namespace Database\Seeders;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
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
        DB::transaction(function () {
            DB::table('countries')->delete();
            DB::table('countries')->insert( array(
                0 => array(
                    'id' => 1,
                    'continent' => 'Africa',
                    'geographical_region' => 'East Africa',
                    'iso_code' => 'KE',
                    'iso3_code' => 'KEN',
                    'timezone' => 'Africa/Nairobi',
                    'name' => 'Kenya',
                    'slug' => 'kenya',
                    'number_code' => '404',
                    'phone_code' => '254',
                    'currency_code' => 'KES',
                    'currency_name' => 'Kenya Shillings'
                ),
                1 => array(
                    'id' => 2,
                    'continent' => 'Africa',
                    'geographical_region' => 'East Africa',
                    'iso_code' => 'UG',
                    'iso3_code' => 'UGA',
                    'timezone' => 'Africa/Kampala',
                    'name' => 'Uganda',
                    'slug' => 'uganda',
                    'number_code' => '800',
                    'phone_code' => '256',
                    'currency_code' => 'UGX',
                    'currency_name' => 'Uganda Shillings'
                ),
                2 => array(
                    'id' => 3,
                    'continent' => 'Africa',
                    'geographical_region' => 'Southern Africa',
                    'iso_code' => 'MZ',
                    'iso3_code' => 'MOZ',
                    'timezone' => 'Africa/Maputo',
                    'name' => 'Mozambique',
                    'slug' => 'Mozambique',
                    'number_code' => '508',
                    'phone_code' => '258',
                    'currency_code' => 'MZN',
                    'currency_name' => 'Mozambican Metical'
                ),
                3 => array(
                    'id' => 4,
                    'continent' => 'Africa',
                    'geographical_region' => 'East Africa',
                    'iso_code' => 'TZ',
                    'iso3_code' => 'TZA',
                    'timezone' => 'Africa/Dar_es_Salaam',
                    'name' => 'Tanzania, United Republic Of (TZ)',
                    'slug' => 'tanzania',
                    'number_code' => '834',
                    'phone_code' => '255',
                    'currency_code' => 'TZS',
                    'currency_name' => 'Tanzania Shillings',
                ),
                4 => array(
                    'id' => 5,
                    'continent' => 'Africa',
                    'geographical_region' => 'West Africa',
                    'iso_code' => 'GH',
                    'iso3_code' => 'GHA',
                    'timezone' => 'Africa/Accra',
                    'name' => 'Ghana',
                    'slug' => 'ghana',
                    'number_code' => '288',
                    'phone_code' => '233',
                    'currency_code' => 'GHC',
                    'currency_name' => 'Ghanaian Cedi',
                ),
                5 => array(
                    'id' => 6,
                    'continent' => 'Africa',
                    'geographical_region' => 'West Africa',
                    'iso_code' => 'NG',
                    'iso3_code' => 'NGA',
                    'timezone' => 'Africa/Lagos',
                    'name' => 'Nigeria',
                    'slug' => 'nigeria',
                    'number_code' => '566',
                    'phone_code' => '234',
                    'currency_code' => 'NGN',
                    'currency_name' => 'Nigeria Naira',
                ),
                6 => array(
                    'id' => 7,
                    'continent' => 'Africa',
                    'geographical_region' => 'South Africa',
                    'iso_code' => 'ZA',
                    'iso3_code' => 'ZAF',
                    'timezone' => 'Africa/Johannesburg',
                    'name' => 'South Africa',
                    'slug' => 'south_africa',
                    'number_code' => '00',
                    'phone_code' => '27',
                    'currency_code' => 'ZAR',
                    'currency_name' => 'South African Rand',
                ),
                7 => array(
                    'id' => 8,
                    'continent' => 'Africa',
                    'geographical_region' => 'South Africa',
                    'iso_code' => 'NA',
                    'iso3_code' => 'NAM',
                    'timezone' => 'Africa/Windhoek',
                    'name' => 'Namibia',
                    'slug' => 'namibia',
                    'number_code' => '00',
                    'phone_code' => '264',
                    'currency_code' => 'NAD',
                    'currency_name' => 'Namibian Dollar',
                ),
                8 => array(
                    'id' => 9,
                    'continent' => 'Africa',
                    'geographical_region' => 'South Africa',
                    'iso_code' => 'BW',
                    'iso3_code' => 'BWA',
                    'timezone' => 'Africa/Gaborone',
                    'name' => 'Botswana',
                    'slug' => 'botswana',
                    'number_code' => '00',
                    'phone_code' => '267',
                    'currency_code' => 'BWP',
                    'currency_name' => 'Pula',
                ),
                9 => array(
                    'id' => 10,
                    'continent' => 'Africa',
                    'geographical_region' => 'Africa',
                    'iso_code' => 'ET',
                    'iso3_code' => 'ETH',
                    'timezone' => 'Africa/Addis_Ababa',
                    'name' => 'Ethiopia',
                    'slug' => 'ethiopia',
                    'number_code' => '231',
                    'phone_code' => '251',
                    'currency_code' => 'ETB',
                    'currency_name' => 'Ethiopian Birr',
                ),
                10 => array(
                    'id' => 11,
                    'continent' => 'Europe',
                    'geographical_region' => 'Europe',
                    'iso_code' => 'ES',
                    'iso3_code' => 'ESP',
                    'timezone' => 'Europe/Madrid',
                    'name' => 'Spain',
                    'slug' => 'Spain',
                    'number_code' => '724',
                    'phone_code' => '34',
                    'currency_code' => 'EUR',
                    'currency_name' => 'Euro',
                ),
                11 => array(
                    'id' => 12,
                    'continent' => 'Europe',
                    'geographical_region' => 'Europe',
                    'iso_code' => 'DE',
                    'iso3_code' => 'DEU',
                    'timezone' => 'Europe/Berlin',
                    'name' => 'Germany',
                    'slug' => 'Germany',
                    'number_code' => '276',
                    'phone_code' => '49',
                    'currency_code' => 'EUR',
                    'currency_name' => 'Euro',
                ),
                12 => array(
                    'id' => 13,
                    'continent' => 'Europe',
                    'geographical_region' => 'Europe',
                    'iso_code' => 'CH',
                    'iso3_code' => 'CHE',
                    'timezone' => 'Europe/Berlin',
                    'name' => 'Switzerland',
                    'slug' => 'Switzerland',
                    'number_code' => '756',
                    'phone_code' => '41',
                    'currency_code' => 'EUR',
                    'currency_name' => 'Euro',
                ),
                13 => array(
                    'id' => 14,
                    'continent' => 'Europe',
                    'geographical_region' => 'Europe',
                    'iso_code' => 'GB',
                    'iso3_code' => 'GBR',
                    'timezone' => 'Europe/London',
                    'name' => 'United Kingdom',
                    'slug' => 'United Kingdom',
                    'number_code' => '826',
                    'phone_code' => '44',
                    'currency_code' => 'GBP',
                    'currency_name' => 'Pound',
                ),
                14 => array(
                    'id' => 15,
                    'continent' => 'Australia',
                    'geographical_region' => 'Australia',
                    'iso_code' => 'AU',
                    'iso3_code' => 'AUS',
                    'timezone' => 'Australia/Melbourne',
                    'name' => 'Australia',
                    'slug' => 'Australia',
                    'number_code' => '36',
                    'phone_code' => '61',
                    'currency_code' => 'AUD',
                    'currency_name' => 'Australian dollar',
                ),
                15 => array(
                    'id' => 16,
                    'continent' => 'North America',
                    'geographical_region' => 'North America',
                    'iso_code' => 'US',
                    'iso3_code' => 'USA',
                    'timezone' => 'America/New_York',
                    'name' => 'United States',
                    'slug' => 'United States',
                    'number_code' => '840',
                    'phone_code' => '1',
                    'currency_code' => 'USD',
                    'currency_name' => 'United States Dollar',
                ),
                16 => array(
                    'id' => 17,
                    'continent' => 'Africa',
                    'geographical_region' => 'South Africa',
                    'iso_code' => 'ZW',
                    'iso3_code' => 'ZWE',
                    'timezone' => 'Africa/Harare',
                    'name' => 'Zimbabwe',
                    'slug' => 'Zimbabwe',
                    'number_code' => '716',
                    'phone_code' => '263',
                    'currency_code' => 'ZWL',
                    'currency_name' => 'Zimbabwean Dollar',
                ),
                17 => array(
                    'id' => 18,
                    'continent' => 'Africa',
                    'geographical_region' => 'South Africa',
                    'iso_code' => 'MW',
                    'iso3_code' => 'MWI',
                    'timezone' => 'Africa/Lilongwe',
                    'name' => 'Malawi',
                    'slug' => 'Malawi',
                    'number_code' => '454',
                    'phone_code' => '265',
                    'currency_code' => 'MWK',
                    'currency_name' => 'Malawian kwacha',
                ),
                18 => array(
                    'id' => 19,
                    'continent' => 'Africa',
                    'geographical_region' => 'South Africa',
                    'iso_code' => 'ZM',
                    'iso3_code' => 'ZMB',
                    'timezone' => 'Africa/Lusaka',
                    'name' => 'Zambia',
                    'slug' => 'Zambia',
                    'number_code' => '894',
                    'phone_code' => '260',
                    'currency_code' => 'ZMW',
                    'currency_name' => 'Zambia kwacha',
                ),
                19 => array(
                    'id' => 20,
                    'continent' => 'North America',
                    'geographical_region' => 'North America',
                    'iso_code' => 'CA',
                    'iso3_code' => 'CAN',
                    'timezone' => 'Canada/Central',
                    'name' => 'Canada',
                    'slug' => 'Canada',
                    'number_code' => '124',
                    'phone_code' => '1',
                    'currency_code' => 'CAD',
                    'currency_name' => 'Canadian Dollar',
                ),
                20 => array(
                    'id' => 21,
                    'continent' => 'Asia',
                    'geographical_region' => 'Western Asia',
                    'iso_code' => 'AE',
                    'iso3_code' => 'ARE',
                    'timezone' => 'Asia/Dubai',
                    'name' => 'United Arab Emirates',
                    'slug' => CraydelHelperFunctions::slugifyString('United Arab Emirates'),
                    'number_code' => '784',
                    'phone_code' => '971',
                    'currency_code' => 'AED',
                    'currency_name' => 'United Arab Emirates Dirham',
                ),
                21 => array(
                    'id' => 22,
                    'continent' => 'South America',
                    'geographical_region' => 'South America',
                    'iso_code' => 'GY',
                    'iso3_code' => 'GUY',
                    'timezone' => 'America/Guyana',
                    'name' => 'Guyana',
                    'slug' => CraydelHelperFunctions::slugifyString('Guyana'),
                    'number_code' => '328',
                    'phone_code' => '592',
                    'currency_code' => 'GYD',
                    'currency_name' => 'Guyanese Dollar',
                ),
                22 => array(
                    'id' => 23,
                    'continent' => 'Global',
                    'geographical_region' => 'Global',
                    'iso_code' => 'Global',
                    'iso3_code' => 'Global',
                    'timezone' => 'UTC',
                    'name' => 'Global',
                    'slug' => CraydelHelperFunctions::slugifyString('Global'),
                    'number_code' => '404',
                    'phone_code' => '254',
                    'currency_code' => 'USD',
                    'currency_name' => 'USD',
                ),
                23 => array(
                    'id' => 24,
                    'continent' => 'Asia',
                    'geographical_region' => 'Asia',
                    'iso_code' => 'CY',
                    'iso3_code' => 'CYP',
                    'timezone' => 'Asia/Nicosia',
                    'name' => 'Cyprus',
                    'slug' => CraydelHelperFunctions::slugifyString('Cyprus'),
                    'number_code' => '196',
                    'phone_code' => '357',
                    'currency_code' => 'Euro',
                    'currency_name' => 'Euro',
                ),
                24 => array(
                    'id' => 25,
                    'continent' => 'Europe',
                    'geographical_region' => 'Europe',
                    'iso_code' => 'IE',
                    'iso3_code' => 'IRL',
                    'timezone' => 'Europe/Dublin',
                    'name' => 'Ireland',
                    'slug' => 'ireland',
                    'number_code' => '353',
                    'phone_code' => '353',
                    'currency_code' => 'EUR',
                    'currency_name' => 'Euro',
                ),
                25 => array(
                    'id' => 26,
                    'continent' => 'Europe',
                    'geographical_region' => 'Europe',
                    'iso_code' => 'TR',
                    'iso3_code' => 'TUR',
                    'timezone' => 'Europe/Istanbul',
                    'name' => 'Turkey',
                    'slug' => 'turkey',
                    'number_code' => '792',
                    'phone_code' => '90',
                    'currency_code' => 'TRY',
                    'currency_name' => 'Lira',
                ),
                26 => array(
                    'id' => 27,
                    'continent' => 'Asia',
                    'geographical_region' => 'Southeast Asia',
                    'iso_code' => 'MY',
                    'iso3_code' => 'MYS',
                    'timezone' => 'Asia/Kuala_Lumpur',
                    'name' => 'Malaysia',
                    'slug' => 'malaysia',
                    'number_code' => '458',
                    'phone_code' => '60',
                    'currency_code' => 'MYR',
                    'currency_name' => 'Ringgit',
                ),
                27 => array(
                    'id' => 28,
                    'continent' => 'Oceania',
                    'geographical_region' => 'Oceania',
                    'iso_code' => 'NZ',
                    'iso3_code' => 'NZL',
                    'timezone' => 'Pacific/Auckland',
                    'name' => 'New Zealand',
                    'slug' => 'new-zealand',
                    'number_code' => '554',
                    'phone_code' => '64',
                    'currency_code' => 'NZ$',
                    'currency_name' => 'Dollar',
                )
            ) );
        });
    }
}