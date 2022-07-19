<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Events\CourseUpdatedEvent;
use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Jobs\DeleteImageCDNJob;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helpers\ImmutableOptions;

class UpdateCourseCommandController
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
     * @param string $course_code
     *
     * @return JsonResponse
     */
    public function update(array $params, ?Request $request, string $course_code): JsonResponse
    {
        try{
            $validation = (new ValidateCourseDetails())->validate(
                new ImmutableOptions($params),
                $request,
                $course_code,
                ValidateCourseDetails::ON_UPDATE
            );

            if($validation->status === false){
                return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                    $validation->status,
                    $validation->message
                )));
            }

            $linked_course_categories = $request->get('linked_course_categories');

            if(!isset($validation->data->validated_values)){
                return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('courses.errors.error_updating_course')
                )));
            }

            $params = $validation->data->validated_values;
            $update = false;

            DB::transaction(function () use($params, $course_code, $linked_course_categories, &$update){
                $update = DB::table((new Course())->getTable())
                    ->where('course_code', $course_code)
                    ->update($params);

                LinkCourseToAcademicDisciplineCommandController::link(
                    DB::table((new Course())->getTable())->where('course_code', $course_code)->value('id'),
                    $linked_course_categories
                );
            });

            if($update){
                if($request->file('course_image')){
                    dispatch((new DeleteImageCDNJob($course_code)))->onQueue('delete_images_to_cdn');
                }

                event(new CourseUpdatedEvent($course_code));

                return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('courses.success.updated')
                )));
            }else{
                return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('courses.success.updated')
                )));
            }
        }catch (\Exception $exception){
            $this->courseController->logException($exception);
            return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            )));
        }
    }
}
