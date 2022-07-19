<?php
namespace Database\Factories;

use App\Models\AcademicDiscipline;
use App\Models\Course;
use App\Models\Leads;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class LeadsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Leads::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $referrer_url = "https://lm.facebook.com/l.php?u=https://craydel.com/study-destinations/study-in-canada?utm_source=DG_Facebook&utm_medium=CPC&utm_campaign=Conversion+%7C+Destination+Campaign+%7C+ABO+%7C+TOF&utm_content=African+Univeristy+%7C+Lead+%7C+Persona+1&fbclid=IwAR1JssAWWBMy0q4xKX8e51vF39WXyrbMkN5jkbjnQAo2nrlukc7LCyb5PRY&h=AT2BjTwTZKsSDeqy2W_5YVqmp5yX3zX1VJ1rSwrhAtCYyVA6mfVpeW7ZDGHf-TIsi9m6-_RALlYnCNj8wbJYH0bmxpuM8LvV2bAw6XxcbtQ6YgSuOUEDYBivFBTCDMGuN6wsf8WDNkLrCcj-LFPWrXhi4hRvgAaxk5oct98PyKSRT0wIBbObg_SRrgOHsEMm90Ge9WtfoQ13KY3Ze94LLkxGOEjkzSoM9y6DeiqpmNhiEwGxOPEUysQRKXRL1S0bHSk395y4D-kPzhOjgMJ4Xh76RiBVEjOmAKhqTrCPlvH6TZUwclyi5DSv043EOu08p0x4X1u3XRBCv9vBUphLNIHfAlfR_sqYj4S-1oGPTCl03BxRDmmS_pSDq_mgY8L6PYDj4vBvTdNO7H9-cJMbvdZl4SdcTp9AWUX01Wd56kPXiJy-fQyVnxCqPcY3e2JHXRVG6KpqgVT755bJsmH1YiXdM1nbnQg32evHtLlEnj0APzcbUVlgwb-W_oNUQCvCTi5VAqKeSAVwhJWP8_lnFKQ_XCX7SfcKs89OyYPbhGFC8z1G2uhwQkSkj1wdgztrNNHRHJe9iTSCwiq2eHqgwJvu1tvQJwALJ5mXY6CVVtERVf7ffyxVVnOuyMSlue-o7j1KUA2UfLrNtp_SYdrx76ZBzWFlhW2s-6zrTiDk9FZjyvjWr36ong3bVA4EiuCATo2a-OS4u4OwpddKd4oRM-86VWq02wmnuImYsSUkxEIiLH3CDt08bC-pqvytlAqr9a7drUBVuvP-QsN__UsP4wUVFu3hEuNjGpBrIM83_Z6ia-qEBLgjJrqsBHRTcu3BsZYPoaCdRy3dftzNcMXtfoC6xJHSJ9v_gQMOCst1r2hJslBIB4RzvcEill0s4Y9l7RmkQlqvDprZpg_NK92IKwNo99TYzVkau0mM7ML7a_eAeQrvtKuD0NdFfAvghEAmusckwtwaZi1RwyCT70tF_Ti-kV5WudfiCRMpKFU3Q3Hy4QGQlIgR8amdEl_s5kIY478PiwP3uDYB2v0";

        return [
            'course_code' => call_user_func(function (){
                $courses = DB::table((new Course())->getTable())
                    ->select(['course_code'])
                    ->get()
                    ->toArray();

                $courses = call_user_func(function () use($courses) {
                    $list = [];

                    foreach ($courses as $course){
                        if(isset($course->course_code) && !in_array($course->course_code, $list)){
                            array_push($list, $course->course_code);
                        }
                    }

                    return $list;
                });

                if(count($courses) > 0){
                    return $this->faker->randomElement($courses);
                }else{
                    return $this->faker->randomNumber(5);
                }
            }),
            'mobile_number' => $this->faker->e164PhoneNumber,
            'email' => $this->faker->email,
            'city' => $this->faker->city,
            'country' => $this->faker->countryCode,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'description' => $this->faker->sentence,
            'course_name' => $this->faker->company,
            'course_category' => DB::table((new AcademicDiscipline())->getTable())->select('discipline_name')->inRandomOrder()->value('discipline_name'),
            'institution_name' => $this->faker->company,
            'page_url' => $this->faker->url,
            'referrer_url' => $referrer_url,
            'default_lead_source' => 'Craydel Marketplace',
            'student_academic_level' => $this->faker->text,
            'how_to_fund_education' => $this->faker->text,
            'partner_agent' => 'Craydel',
            'utm_source' => $this->faker->name,
            'utm_medium' => $this->faker->name,
            'utm_campaign' => $this->faker->name,
            'asset_id' => $this->faker->randomNumber(4),
            'lead_status' => 'New',
            'is_processed' => 0,
            'utm_term' => 'UTM_TERM',
            'ad_id' => 'AD_ID',
            'ad_set_id' => 'AD_SET_ID',
            'campaign_id' => 'CAMPAIGN_ID',
            'ad_name' => 'AD_NAME',
            'ad_set_name' => 'AD_SET_NAME',
            'ad_placement_position' => 'AD_PLACEMENT_POSITION',
            'site_source_name' => 'SITE_SOURCE_NAME',
            'utm_content' => 'UTM_CONTENT',
            'marketplace_search_term' => 'MARKETPLACE_SEARCH_TERM',
            'marketplace_search_query_id' => 'MARKETPLACE_SEARCH_QUERY_ID',
            'is_picked_for_processing' => 0,
            'time_picked_for_processing' => null,
            'created_at' => null
        ];
    }
}
