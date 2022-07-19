<?php
namespace App\Http\Controllers\PublicView;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Providers\MailList\IMailingListProvider;
use App\Http\Controllers\PublicView\Commands\SubmitLeadCommandController;
use App\Http\Controllers\PublicView\Commands\SubscribeUserToMailingListCommandController;
use App\Http\Controllers\PublicView\Commands\UpdateProgressiveLeadFormFieldsCommandController;
use App\Http\Controllers\PublicView\Queries\GetFooterMenuQueryController;
use App\Http\Controllers\PublicView\Queries\GetLatestForexRatesQueriesController;
use App\Http\Controllers\PublicView\Queries\GetMarketplaceSitemapQueryController;
use App\Http\Controllers\PublicView\Queries\GetTopCoursesQueryController;
use App\Http\Controllers\PublicView\Queries\SingleCourseQueryController;
use App\Http\Controllers\PublicView\Queries\GetCoursesPerDisciplineQueryController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\AcademicDiscipline;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Respect\Validation\Validator;

class CoursesPublicViewController
{
    use CanRespond, CanLog, CanCache;

    /**
     * @var SubmitLeadCommandController
    */
    protected SubmitLeadCommandController $submitLeadCommandController;

    /**
     * @var SingleCourseQueryController
    */
    protected SingleCourseQueryController $singleCourseQueryController;

    /**
     * @var GetLatestForexRatesQueriesController
    */
    protected GetLatestForexRatesQueriesController $getLatestForexRatesQueriesController;

    /**
     * @var GetTopCoursesQueryController $getTopCoursesQueryController
    */
    protected GetTopCoursesQueryController $getTopCoursesQueryController;

    /**
     * @var GetCoursesPerDisciplineQueryController
     */
    protected GetCoursesPerDisciplineQueryController $getCoursesPerDisciplineQueryController;

    /**
     * @var GetFooterMenuQueryController $getFooterMenuQueryController
    */
    protected GetFooterMenuQueryController $getFooterMenuQueryController;

    /**
     * @var GetMarketplaceSitemapQueryController $getMarketplaceSitemapQueryController
    */
    protected GetMarketplaceSitemapQueryController $getMarketplaceSitemapQueryController;

    /**
     * @var SubscribeUserToMailingListCommandController $subscribeUserToMailingListCommandController
    */
    protected SubscribeUserToMailingListCommandController $subscribeUserToMailingListCommandController;

    /**
     * @var UpdateProgressiveLeadFormFieldsCommandController $updateProgressiveLeadFormFieldsCommandController
    */
    protected UpdateProgressiveLeadFormFieldsCommandController $updateProgressiveLeadFormFieldsCommandController;

    /**
     * @var UpdateProgressiveLeadFormFieldsCommandController $updateProgressiveLeadFormFieldsCommandController
     */

    /**
     * @var Validator $validator
     */
    public static Validator $formValidator;

    /**
     * Constructor
    */
    public function __construct()
    {
        $this->submitLeadCommandController = new SubmitLeadCommandController($this);
        $this->singleCourseQueryController = new SingleCourseQueryController($this);
        $this->getLatestForexRatesQueriesController = new GetLatestForexRatesQueriesController($this);
        $this->getFooterMenuQueryController = new GetFooterMenuQueryController($this);
        $this->getTopCoursesQueryController = new GetTopCoursesQueryController($this);
        $this->getCoursesPerDisciplineQueryController = new GetCoursesPerDisciplineQueryController($this);
        $this->getMarketplaceSitemapQueryController = new GetMarketplaceSitemapQueryController($this);
        $this->subscribeUserToMailingListCommandController = new SubscribeUserToMailingListCommandController();
        $this->updateProgressiveLeadFormFieldsCommandController = new UpdateProgressiveLeadFormFieldsCommandController($this);
        self::$formValidator = new Validator();
    }

    /**
     * Submit lead
     * @param Request $request
     * @return JsonResponse
     */
    public function submitLead(Request $request): JsonResponse
    {
        return $this->submitLeadCommandController->submit($request);
    }

    /**
     * Submit progressive lead form
     * @param Request $request
     * @return JsonResponse
     */
    public function submitProgressiveLeadForm(Request $request): JsonResponse
    {
        return $this->updateProgressiveLeadFormFieldsCommandController->submit($request);
    }

    /**
     * Get single course details
    */
    public function course(?string $course_code): JsonResponse
    {
        return $this->singleCourseQueryController->course($course_code);
    }

    /**
     * Get the latest exchange rate
    */
    public function usdExchangeRate(): JsonResponse
    {
        return $this->getLatestForexRatesQueriesController->rates();
    }

    /**
     * Get academic disciplines
    */
    public function getAcademicDisciplines(): JsonResponse
    {
        try{
            $with_courses = self::cache('academic_disciplines_with_courses');

            if(is_null($with_courses)){
                self::cache(
                    'academic_disciplines_with_courses',
                    AcademicDiscipline::hasPublishedCourses()
                        ->where('is_deleted', 0)
                        ->orderBY('discipline_name')
                        ->get()
                );
            }

            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                "Loaded",
                (object)[
                    'academic_disciplines' => self::cache('academic_disciplines_with_courses')
                ]
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }


     /**
     * Get footer menu
     *
     * @param Request $request
     * @return JsonResponse
    */
    public function getFooterMenu(Request $request): JsonResponse
    {
        return $this->getFooterMenuQueryController->footer($request);
    }

    /**
     * Get top courses
     *
     * @param Request $request
     * @return JsonResponse
    */
    public function getTopCourses(Request $request): JsonResponse
    {
        return $this->getTopCoursesQueryController->courses($request);
    }

    /**
     * Get Courses per discipline
     */

    public function getCoursesPerDiscipline(Request $request): JsonResponse
    {
        return $this->getCoursesPerDisciplineQueryController->courses($request);
    }


    /**
     * Get sitemap
    */
    public function getSitemap(): JsonResponse
    {
        return $this->getMarketplaceSitemapQueryController->generate();
    }

    /**
     * Subscribe user to mailing list
    */
    public function subscribeUserToMailingList(Request $request, IMailingListProvider $provider): JsonResponse
    {
        return $this->subscribeUserToMailingListCommandController->subscribe($request, $provider);
    }
}
