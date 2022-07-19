<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\CourseTypesHelper;
use App\Http\Controllers\Helpers\InstitutionsHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\LearningModeHelper;
use Illuminate\Http\JsonResponse;
use phpDocumentor\Reflection\Types\Collection;

class BuildNewCourseCommandController
{
    /**
     * @var CourseController
    */
    protected $courseController;

    /**
     * Constructor
     * @param CourseController $courseController
     */
    public function __construct(CourseController $courseController){
        $this->courseController = $courseController;
    }

    /**
     * Build a new course request
    */
    public function build(): JsonResponse
    {
        return $this->courseController->respondInJSON((new CraydelJSONResponseType(
            true,
            LanguageTranslationHelper::translate('courses.success.built'), [
                'types' => CourseTypesHelper::types(),
                'learning_mode' => LearningModeHelper::modes(),
                'academic_disciplines' => AcademicDisciplineHelper::disciplines(),
                'attendance_type' => ValidateCourseDetails::getAttendanceType(),
                'graduate_levels' => ValidateCourseDetails::getGraduateLevels(),
                'standard_fee_billing_type' => ValidateCourseDetails::getStandardFeeBillingType(),
                'course_duration_category' => ValidateCourseDetails::getCourseDurationCategory(),
                'institutions' => call_user_func(function (){
                    $institutions = InstitutionsHelper::institutions();

                    if($institutions->status == true && count($institutions->data) > 0){
                        return collect($institutions->data)->map(function ($item){
                            return [
                                'country_code' => isset($item->country_code) ? trim($item->country_code) : null,
                                'institution_code' => isset($item->institution_code) ? trim($item->institution_code) : null,
                                'institution_name' => isset($item->institution_name) ? trim($item->institution_name) : null
                            ];
                        })->values()->toArray();
                    }else{
                        return array();
                    }
                })
            ]
        )));
    }
}
