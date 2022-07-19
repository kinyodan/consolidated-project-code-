<?php

namespace App\Http\Controllers\GenerateEnrollmentDetails;

use App\Events\CourseUpdatedEvent;

use App\Http\Controllers\CraydelTypes\CraydelCourseType;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\GenerateEnrollmentDetails\Queries\ListCoursesByEnrollmentDates;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\EnrollmentDateHelper;
use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\InstitutionsHelper;
use App\Http\Controllers\Helpers\CourseTypesHelper;
use App\Http\Controllers\Helpers\ManageSearchEngineDataHelper;
use App\Http\Controllers\Providers\Forex\ForexController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Jobs\UploadImageToCDNJob;
use App\Models\AcademicDiscipline;
use App\Models\Course;
use App\Models\CourseSearchIndexList;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class GenerateEnrollmentDetailsController
{
    use CanUploadImage, CanLog, CanCache, CanPaginate, CanRespond;

    private ListCoursesByEnrollmentDates $listCoursesByEnrollmentDates;

    public function __construct()
    {
        $this->listCoursesByEnrollmentDates = new ListCoursesByEnrollmentDates($this);
    }

    public function getCoursesByEnrollmentDates(Request $request): JsonResponse
    {
        return $this->listCoursesByEnrollmentDates->get($request);
    }

    public static function selectedCourse($course)
    {
        $enrollments_details = json_decode(EnrollmentDateHelper::make($course->enrollment_details));
        $course_academic_discipline = AcademicDisciplineHelper::getDisciplineNameByID($course->course_academic_category_is_generated);
        $country = CountryHelper::getName($course->country_code);
        $institution = InstitutionsHelper::summary($course->institution_code);
        $institution_name = $institution->data->institution_name;
        $course_name = $course->course_name;
        $course_type = CourseTypesHelper::getCourseTypeNameByID($course->course_type);
        $course_id = $course->id;
        foreach ($enrollments_details as $enrollments_detail) {
            DB::table('course_enrollment_summary')->insertOrIgnore([
                ['course_name' => $course_name, 'course_category' => $course_academic_discipline, 'course_type' => $course_type,
                    'country' => $country, 'institution' => $institution_name, 'enrollment_dates' => $enrollments_detail,
                    'created_at' => Carbon::now()->toDateTimeString()]
            ]);

        }
        $update_data = [
            'is_completed_for_enrollment_details' => 1,
            'updated_at' => Carbon::now()->toDateTimeString()
        ];

        DB::table('courses')
            ->where('id', $course_id)
            ->update($update_data);


    }

}
