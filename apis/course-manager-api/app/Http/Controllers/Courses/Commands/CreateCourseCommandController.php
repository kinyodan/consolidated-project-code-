<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Events\CourseCreatedEvent;
use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Jobs\UploadImageToCDNJob;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helpers\ImmutableOptions;

class CreateCourseCommandController
{
    /**
     * @var CourseController
    */
    protected CourseController $courseController;

    /**
     * Constructor
     * @param CourseController $courseController
     */
    public function __construct(CourseController $courseController){
        $this->courseController = $courseController;
    }

    /**
     * Create a new course
     *
     * @param array $params
     * @param Request|null $request
     *
     * @return JsonResponse
     */
    public function create(array $params, ?Request $request): JsonResponse
    {
        try {
            $validation = (new ValidateCourseDetails())
                ->validate(new ImmutableOptions($params), $request);

            if($validation->status === false){
                return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                    $validation->status,
                    $validation->message
                )));
            }

            if(!isset($validation->data->validated_values)){
                return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('courses.errors.error_updating_course')
                )));
            }

            $linked_course_categories = $request->input('linked_course_categories');

            $params = $validation->data->validated_values;
            $course_code = isset($params['course_code']) && !CraydelHelperFunctions::isNull($params['course_code']) ? trim($params['course_code']) : CraydelHelperFunctions::makeRandomString(10, 'C', false);

            $course_id = false;

            DB::transaction(function () use ($params, $linked_course_categories, &$course_id) {
                $course_id = DB::table((new Course())->getTable())->insertGetId($params);
                LinkCourseToAcademicDisciplineCommandController::link(
                    $course_id,
                    $linked_course_categories
                );
            });

            if($course_id){
                dispatch((new UploadImageToCDNJob($course_code)))->onQueue('upload_images_to_cdn');
                event(new CourseCreatedEvent($course_code));

                return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('courses.success.created')
                )));
            }else{
                return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('courses.errors.error_saving_course')
                )));
            }
        }catch (\Exception $e){
            $this->courseController->logException($e);
            return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('courses.errors.error_saving_course')
            )));
        }
    }
}
