<?php
namespace App\Http\Controllers\PublicView\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\BrickParsePhoneNumberHelper;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\CraydelURLHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\LearningModeHelper;
use App\Http\Controllers\Helpers\MySQLFunctionsHelper;
use App\Http\Controllers\Helpers\ParsePhoneNumberHelper;
use App\Http\Controllers\Providers\LeadManagement\LeadProviders;
use App\Http\Controllers\PublicView\CoursesPublicViewController;
use App\Models\Course;
use App\Models\Leads;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator;
use Respect\Validation\Validator as v;

class SubmitLeadCommandController
{
    /**
     * @var CoursesPublicViewController
     */
    protected CoursesPublicViewController $coursesPublicViewController;

    /**
     * @var v $validate
     */
    protected v $validate;

    /**
     * Constructor
     * @param CoursesPublicViewController $coursesPublicViewController
     */
    public function __construct(CoursesPublicViewController $coursesPublicViewController)
    {
        $this->coursesPublicViewController = $coursesPublicViewController;
        $this->validate = new Validator();
    }

    /**
     * Validate the lead
     *
     * @param Request $request
     *
     * @return CraydelInternalResponseHelper
     */
    protected function validate(Request $request): CraydelInternalResponseHelper
    {
        try {
            $course_code = $request->input('course_code');
            $city = $request->input('city');
            $year_of_birth = $request->input('year_of_birth');
            $email = $request->input('email');
            $mobile_number = $request->input('mobile_number');
            $mobile_number_country_code = $request->input('mobile_number_country_code');
            $full_name = $request->input('full_names');
            $description = $request->input('description');
            $institution_name = $request->input('institution_name');
            $page_url = $request->input('page_url');
            $referrer_url = $request->input('referrer_url');
            $education_level = $request->input('education_level');
            $education_fund = $request->input('education_fund');
            $form_type = $request->input('form_type', 'DEFAULT');

            if (!$this->validate::optional($this->validate::stringVal())->validate($course_code)) {
                throw new Exception('Missing course code.');
            }

            if (!empty($course_code) && !DB::table((new Course())->getTable())->where('course_code', trim($course_code))->exists()) {
                throw new Exception('Invalid course code');
            }

            if (!$this->validate::optional($this->validate::stringVal())->validate($city)) {
                throw new Exception('Invalid city name.');
            }

            if (!$this->validate::optional($this->validate::stringVal())->validate($year_of_birth)) {
                throw new Exception('Invalid year of birth.');
            }

            if (!$this->validate::optional($this->validate::email())->validate($email)) {
                throw new Exception('Invalid email address');
            }

            if (!$this->validate::phone()->notEmpty()->validate($mobile_number)) {
                throw new Exception('Missing or Invalid mobile number');
            }

            if (!$this->validate::optional($this->validate::stringVal())->validate($mobile_number_country_code)) {
                throw new Exception('Missing or Invalid mobile number country code');
            }

            if (!$this->validate::stringVal()->notEmpty()->validate($full_name)) {
                throw new Exception('Missing or invalid full names.');
            }

            if (!$this->validate::optional($this->validate::stringVal())->validate($description)) {
                throw new Exception('Invalid request description.');
            }

            if (!$this->validate::optional($this->validate::stringVal())->validate($institution_name)) {
                throw new Exception('Missing or invalid institution name');
            }

            if (!$this->validate::optional($this->validate::stringVal())->validate($page_url)) {
                throw new Exception('Missing or invalid current page URL');
            }

            if ($form_type === 'BOOK_FREE_COUNSELLING_LEAD_PAGE') {
                if (!$this->validate::optional($this->validate::stringVal())->validate($education_level)) {
                    throw new Exception('Invalid education level');
                }

                if (!$this->validate::optional($this->validate::stringVal())->validate($education_fund)) {
                    throw new Exception('Invalid way to fund your education.');
                }
            }

            return $this->coursesPublicViewController->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Validated'
            ));
        } catch (Exception $exception) {
            $this->coursesPublicViewController->logException($exception);

            return $this->coursesPublicViewController->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Submit lead
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function submit(Request $request): JsonResponse
    {
        try {
            $validate = $this->validate($request);

            if (!$validate->status) {
                return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    $validate->message
                ));
            }

            $course_code = CraydelHelperFunctions::toCleanString($request->input('course_code'));
            $course_category = CraydelHelperFunctions::toCleanString($request->input('course_category'));
            $city = CraydelHelperFunctions::toCleanString($request->input('city'));
            $email = CraydelHelperFunctions::makeCleanEmailAddress($request->input('email'));
            $mobile_number = CraydelHelperFunctions::toCleanString($request->input('mobile_number'));
            $mobile_number_country_code = CraydelHelperFunctions::toCleanString($request->input('mobile_number_country_code'));

            if (empty($mobile_number_country_code)) {
                $mobile_number_country_code = BrickParsePhoneNumberHelper::getCountryCodeFromMobileNumber($mobile_number);
            }

            $full_name = CraydelHelperFunctions::toCleanString($request->input('full_names'));
            $description = CraydelHelperFunctions::toCleanString($request->input('description'));
            $institution_name = CraydelHelperFunctions::toCleanString($request->input('institution_name'));
            $page_url = CraydelHelperFunctions::toCleanString($request->input('page_url'));
            $referrer_url = CraydelHelperFunctions::toCleanString($request->input('referrer_url'));
            $year_of_birth = CraydelHelperFunctions::toCleanString($request->input('year_of_birth'));
            $education_level = $request->input('education_level');
            $education_fund = $request->input('education_fund');
            $partner_agent = $request->input('partner_agent');
            $form_type = $request->input('form_type');
            $page_section = $request->input('page_section');
            $query_id = $request->input('query_id');
            $search_term = $request->input('search_term');
            $processed_page_url = CraydelURLHelper::process($page_url, $referrer_url);
            $utm_source = CraydelHelperFunctions::toCleanString($request->input('utm_source'));
            $utm_medium = CraydelHelperFunctions::toCleanString($request->input('utm_medium'));
            $utm_campaign = CraydelHelperFunctions::toCleanString($request->input('utm_campaign'));
            $utm_id = CraydelHelperFunctions::toCleanString($request->input('utm_id'));
            $ad_id = CraydelHelperFunctions::toCleanString($request->input('ad_id'));
            $ad_set_id = CraydelHelperFunctions::toCleanString($request->input('ad_set_id'));
            $campaign_id = CraydelHelperFunctions::toCleanString($request->input('campaign_id'));
            $ad_name = CraydelHelperFunctions::toCleanString($request->input('ad_name'));
            $ad_set_name = CraydelHelperFunctions::toCleanString($request->input('adset_name'));
            $ad_placement_position = CraydelHelperFunctions::toCleanString($request->input('placement'));
            $site_source_name = CraydelHelperFunctions::toCleanString($request->input('site_source_name'));
            $utm_content = CraydelHelperFunctions::toCleanString($request->input('utm_content'));
            $utm_term = CraydelHelperFunctions::toCleanString($request->input('utm_term'));
            $study_destination = CraydelHelperFunctions::toCleanString($request->input('study_destination'));
            $parent_full_names = CraydelHelperFunctions::toCleanString($request->input('parent_full_names'));
            $parent_mobile_number = CraydelHelperFunctions::toCleanString($request->input('parent_mobile_number'));

            $lead_source = CraydelHelperFunctions::toCleanString(
                $request->input('lead_source',
                    config('craydle.lead_management.default_lead_source')
                ));

            $course_details = Course::all()
                ->where('course_code', trim($course_code))
                ->first();

            $mobile_number = ParsePhoneNumberHelper::makeNationalizedMobileNumber(
                $mobile_number_country_code,
                $mobile_number
            );

            if(!empty($parent_mobile_number)){
                $parent_mobile_number = ParsePhoneNumberHelper::makeNationalizedMobileNumber(
                    $mobile_number_country_code,
                    $parent_mobile_number
                );
            }else{
                $parent_mobile_number = $mobile_number;
            }

            if (empty($mobile_number)) {
                throw new Exception('Invalid mobile number.');
            }

            if (DB::table((new Leads())->getTable())->where('email', $email)->exists()) {
                $current_status = DB::table((new Leads())->getTable())
                    ->where('email', $email)
                    ->value('utm_source');

                if ($current_status !== 'automated_chat_lead_form') {
                    return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                        true,
                        LanguageTranslationHelper::translate('courses.success.lead_submitted'),[
                            'lead_id' => DB::table((new Leads())->getTable())
                                ->where('email', $email)
                                ->orderBy('id', 'desc')
                                ->value('id')
                        ]
                    ));
                }
            }

            $utm_source = !CraydelHelperFunctions::isNull($utm_source) ? $utm_source : $processed_page_url->getUtmSource();
            $utm_medium = !CraydelHelperFunctions::isNull($utm_medium) ? $utm_medium : $processed_page_url->getUtmMedium();
            $utm_campaign = !CraydelHelperFunctions::isNull($utm_campaign) ? $utm_campaign : $processed_page_url->getUtmCampaign();
            $utm_id = !CraydelHelperFunctions::isNull($utm_id) ? $utm_id : $processed_page_url->getUtmId();
            $utm_term = !CraydelHelperFunctions::isNull($utm_term) ? $utm_term : $processed_page_url->getUtmTerm();
            $utm_content = !CraydelHelperFunctions::isNull($utm_content) ? $utm_content : $processed_page_url->getUtmContent();
            $ad_id = !CraydelHelperFunctions::isNull($ad_id) ? $ad_id : $processed_page_url->getAdId();
            $ad_set_id = !CraydelHelperFunctions::isNull($ad_set_id) ? $ad_set_id : $processed_page_url->getAdSetId();
            $campaign_id = !CraydelHelperFunctions::isNull($campaign_id) ? $campaign_id : $processed_page_url->getCampaignId();
            $ad_name = !CraydelHelperFunctions::isNull($ad_name) ? $ad_name : $processed_page_url->getAdName();
            $ad_set_name = !CraydelHelperFunctions::isNull($ad_set_name) ? $ad_set_name : $processed_page_url->getAdSetName();
            $ad_placement_position = !CraydelHelperFunctions::isNull($ad_placement_position) ? $ad_placement_position : $processed_page_url->getAdPlacementPosition();
            $site_source_name = !CraydelHelperFunctions::isNull($site_source_name) ? $site_source_name : $processed_page_url->getSiteSourceName();
            $site_source_name = !CraydelHelperFunctions::isNull($site_source_name) ? $site_source_name : $processed_page_url->getSiteSourceName();

            MySQLFunctionsHelper::insertOrUpdate((new Leads())->getTable(), [[
                'course_code' => trim($course_code),
                'mobile_number' => $mobile_number,
                'email' => !empty($email) ? $email : null,
                'city' => !empty($city) ? $city : null,
                'country' => !empty($mobile_number_country_code) ? CountryHelper::getName($mobile_number_country_code) : null,
                'study_destination' => !empty($study_destination) ? $study_destination : null ,
                'first_name' => CraydelHelperFunctions::makeFirstName($full_name),
                'last_name' => CraydelHelperFunctions::makeOtherNames($full_name),
                'parent_full_names' => !empty($parent_full_names) ? CraydelHelperFunctions::toCleanString($parent_full_names) : null,
                'parent_mobile_number' => !empty($parent_mobile_number) ? $parent_mobile_number : null,
                'description' => !empty($description) ? $description : null,
                'course_name' => isset($course_details->course_name) && !empty($course_details->course_name) ? trim($course_details->course_name) : $course_category,
                'course_category' => !empty($course_category) ? CraydelHelperFunctions::toCleanString($course_category) : null,
                'course_learning_mode' => isset($course_details->learning_mode) && !empty($course_details->learning_mode) ? LearningModeHelper::getLearningModeByID($course_details->learning_mode) : null,
                'current_course_intake' => isset($course_details->current_intake) && !empty($course_details->current_intake) ? trim($course_details->current_intake) : null,
                'institution_name' => !empty($institution_name) ? trim($institution_name) : null,
                'page_url' => !empty($page_url) ? $page_url : null,
                'referrer_url' => CraydelHelperFunctions::isURL($referrer_url) ? $referrer_url : null,
                'default_lead_source' => $lead_source,
                'lead_status' => config('craydle.lead_management.default_lead_status'),
                'created_at' => Carbon::now()->toDateTimeString(),
                'lms_provider' => LeadProviders::ZOHO_LEAD_PROVIDER,
                'year_of_birth' => !empty($year_of_birth) ? $year_of_birth : null,
                'utm_source' => !empty($utm_source) ? $utm_source : null,
                'utm_medium' => !empty($utm_medium) ? $utm_medium : null,
                'utm_campaign' => !empty($utm_campaign) ? $utm_campaign : null,
                'utm_term' => !empty($utm_term) ? $utm_term : null,
                'utm_content' => !empty($utm_content) ? $utm_content : null,
                'asset_id' => !empty($ad_id) ? $ad_id : null,
                'student_academic_level' => !empty($education_level) ? $education_level : null,
                'how_to_fund_education' => !empty($education_fund) ? $education_fund : null,
                'partner_agent' => !empty($partner_agent) ? $partner_agent : null,
                'form_type' => !empty($form_type) ? $form_type : null,
                'page_section' => !empty($page_section) ? $page_section : null,
                'marketplace_search_term' => !empty($search_term) ? $search_term : null,
                'marketplace_search_query_id' => !empty($query_id) ? $query_id : null,
                'ad_id' => !empty($ad_id) ? $ad_id : null,
                'ad_set_id' => !empty($ad_set_id) ? $ad_set_id : null,
                'campaign_id' => !empty($campaign_id) ? $campaign_id : $utm_id,
                'ad_name' => !empty($ad_name) ? $ad_name : null,
                'ad_set_name' => !empty($ad_set_name) ? $ad_set_name : null,
                'ad_placement_position' => !empty($ad_placement_position) ? $ad_placement_position : null,
                'site_source_name' => !empty($site_source_name) ? $site_source_name : null
            ]]);

            return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('courses.success.lead_submitted'),[
                    'lead_id' => DB::table((new Leads())->getTable())
                        ->where('email', $email)
                        ->orderBy('id', 'desc')
                        ->value('id')
                ]
            ));
        } catch (Exception $exception) {
            $this->coursesPublicViewController->logException($exception);

            return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
