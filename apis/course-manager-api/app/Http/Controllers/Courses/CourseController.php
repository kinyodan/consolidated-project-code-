<?php
namespace App\Http\Controllers\Courses;

use App\Events\CourseUpdatedEvent;
use App\Http\Controllers\Courses\Commands\BuildNewCourseCommandController;
use App\Http\Controllers\Courses\Commands\BulkDeleteCourseCommandController;
use App\Http\Controllers\Courses\Commands\BulkPublishCourseCommandController;
use App\Http\Controllers\Courses\Commands\BulkUnPublishCourseCommandController;
use App\Http\Controllers\Courses\Commands\CalculateFirstYearFeeCommandController;
use App\Http\Controllers\Courses\Commands\CreateCourseCommandController;
use App\Http\Controllers\Courses\Commands\DeleteCourseCommandController;
use App\Http\Controllers\Courses\Commands\FeatureCourseCommandController;
use App\Http\Controllers\Courses\Commands\ImportCourseCommandController;
use App\Http\Controllers\Courses\Commands\PublishCourseCommandController;
use App\Http\Controllers\Courses\Commands\UnPublishCourseCommandController;
use App\Http\Controllers\Courses\Commands\UpdateCourseCommandController;
use App\Http\Controllers\Courses\Commands\UpdateCourseStaticsCommandController;
use App\Http\Controllers\Courses\Queries\ListCourseQueryController;
use App\Http\Controllers\CraydelTypes\CraydelCourseType;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
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

class CourseController
{
    use CanUploadImage, CanLog, CanCache, CanPaginate, CanRespond;

    /**
     * @var BuildNewCourseCommandController $buildNewCourseCommandController
     */
    private BuildNewCourseCommandController $buildNewCourseCommandController;
    /**
     * @var CreateCourseCommandController $createCourseCommandController
     */
    private CreateCourseCommandController $createCourseCommandController;
    /**
     * @var UpdateCourseCommandController $updateCourseCommandController
     */
    private UpdateCourseCommandController $updateCourseCommandController;
    /**
     * @var ListCourseQueryController $listCourseQueryController
     */
    private ListCourseQueryController $listCourseQueryController;
    /**
     * @var ImportCourseCommandController $importCourseCommandController
     */
    private ImportCourseCommandController $importCourseCommandController;
    /**
     * @var FeatureCourseCommandController $featureCourseCommandController
     */
    private FeatureCourseCommandController $featureCourseCommandController;
    /**
     * @var UpdateCourseStaticsCommandController $updateCourseStaticsCommandController
     */
    private UpdateCourseStaticsCommandController $updateCourseStaticsCommandController;
    private DeleteCourseCommandController $deleteCourseCommandController;
    private UnPublishCourseCommandController $unpublishCourseCommandController;
    private PublishCourseCommandController $publishCourseCommandController;
    private BulkDeleteCourseCommandController $bulkDeleteCourseCommandController;
    private BulkPublishCourseCommandController $bulkPublishCourseCommandController;
    private BulkUnPublishCourseCommandController $bulkUnPublishCourseCommandController;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->buildNewCourseCommandController = new BuildNewCourseCommandController($this);
        $this->createCourseCommandController = new CreateCourseCommandController($this);
        $this->updateCourseCommandController = new UpdateCourseCommandController($this);
        $this->listCourseQueryController = new ListCourseQueryController($this);
        $this->importCourseCommandController = new ImportCourseCommandController($this);
        $this->featureCourseCommandController = new FeatureCourseCommandController($this);
        $this->updateCourseStaticsCommandController = new UpdateCourseStaticsCommandController($this);
        $this->deleteCourseCommandController = new DeleteCourseCommandController($this);
        $this->unpublishCourseCommandController = new UnPublishCourseCommandController($this);
        $this->publishCourseCommandController = new PublishCourseCommandController($this);
        $this->bulkDeleteCourseCommandController = new BulkDeleteCourseCommandController($this);
        $this->bulkPublishCourseCommandController = new BulkPublishCourseCommandController($this);
        $this->bulkUnPublishCourseCommandController = new BulkUnPublishCourseCommandController($this);
    }

    /**
     * Upload the logo to CDN
     *
     * @param string $course_code
     * @param string $image_path
     *
     * @return void
     * @throws Exception
     */
    public static function uploadCourseImage(string $course_code, string $image_path)
    {
        if (empty($course_code)) {
            throw new Exception('Invalid course Code');
        }
        self::uploadImage(
            $image_path,
            Course::where('course_code', $course_code)->first(), [
                [
                    'column' => 'course_image',
                    'width' => 860,
                    'height' => 367
                ],
                [
                    'column' => 'course_small_image',
                    'width' => 400,
                    'height' => 300
                ]
            ]
        );
        Course::where('course_code', $course_code)->update([
            'temp_course_image_url' => null
        ]);
        event(new CourseUpdatedEvent($course_code));
    }

    /**
     * Upload the logo to CDN
     *
     * @param string $course_code
     *
     * @return void
     * @throws Exception
     */
    public static function deleteInstitutionLogoImages(string $course_code)
    {
        if (empty($course_code)) {
            throw new Exception('Invalid course Code');
        }
        $course = DB::table((new Course())->getTable())
            ->where('course_code', trim($course_code))
            ->first([
                'course_small_image',
                'course_image',
                'temp_course_image_url'
            ]);
        if (isset($course->temp_course_image_url) && !empty($course->temp_course_image_url)) {
            if (isset($course->course_small_image) && !empty($course->course_small_image)) {
                self::_deleteFromCDN($course->course_small_image);
            }
            if (isset($course->course_image) && !empty($course->course_image)) {
                self::_deleteFromCDN($course->course_image);
            }
            dispatch((new UploadImageToCDNJob($course_code)))
                ->onQueue('upload_images_to_cdn');
        }
    }

    /**
     * Update the course standard fee payable in USD
     *
     * @param string|null $course_code
     * @param string|null $local_currency_code
     * @param float|null $standard_fee_payable
     *
     * @return void
     */
    public static function updateCourseStandardFeePayableUSD(?string $course_code, ?string $local_currency_code, ?float $standard_fee_payable)
    {
        try {
            if (empty($course_code)) {
                throw new Exception("Missing course code");
            }
            if (empty($local_currency_code)) {
                throw new Exception("Missing local currency current code.");
            }
            if (empty($standard_fee_payable)) {
                throw new Exception("Missing standard fees payable.");
            }
            $standard_fee_payable = floatval(CraydelHelperFunctions::toNumbers($standard_fee_payable));
            if (empty($standard_fee_payable)) {
                throw new Exception("Invalid standard fees payable value");
            }
            $convert = new ForexController(
                $local_currency_code,
                'USD',
                $standard_fee_payable
            );
            $result = $convert->convert();
            if (!$result->status) {
                throw new Exception($result->message);
            }
            if (!isset($result->data->converted_value) || empty($result->data->converted_value)) {
                throw new Exception("Unable to get forex converted value");
            }
            $standard_first_year_fee_payable = CalculateFirstYearFeeCommandController::calculate(
                DB::table((new Course())->getTable())->where('course_code', $course_code)->first(),
                $result->data->converted_value
            );
            $course_details = DB::table((new Course())->getTable())
                ->where('course_code', $course_code)
                ->first();
            DB::transaction(function () use ($result, $course_code, $course_details, $standard_first_year_fee_payable) {
                DB::table((new Course())->getTable())
                    ->where('course_code', $course_code)
                    ->update([
                        'standard_fee_payable_usd' => floatval($result->data->converted_value),
                        'standard_first_year_fee_payable_usd' => call_user_func(function () use ($course_details, $standard_first_year_fee_payable) {
                            if (isset($course_details->ignore_first_year_fees_compute_based_on_total) && intval($course_details->ignore_first_year_fees_compute_based_on_total) == 1) {
                                if (is_null($course_details->standard_first_year_fee_payable_usd) || empty($course_details->standard_first_year_fee_payable_usd)) {
                                    return $standard_first_year_fee_payable;
                                } else {
                                    return $course_details->standard_first_year_fee_payable_usd;
                                }
                            } else {
                                return $standard_first_year_fee_payable;
                            }
                        }),
                        'has_updates' => 1,
                        'is_picked_for_indexing' => 0,
                        'time_picked_for_indexing' => null,
                        'time_taken_to_index' => null,
                        'indexing_error' => null
                    ]);
            });
        } catch (Exception $exception) {
            (new self())->logException($exception);
        }
    }

    /**
     * Update the course standard fee payable in USD
     *
     * @param string|null $course_code
     * @param string|null $local_currency_code
     * @param float|null $foreign_student_fee_payable
     *
     * @return void
     */
    public static function updateCourseForeignStudentFeePayableUSD(?string $course_code, ?string $local_currency_code, ?float $foreign_student_fee_payable)
    {
        try {
            if (empty($course_code)) {
                throw new Exception("Missing course code");
            }
            if (empty($local_currency_code)) {
                throw new Exception("Missing local currency current code.");
            }
            if (empty($foreign_student_fee_payable)) {
                throw new Exception("Missing foreign student fees payable.");
            }
            $foreign_student_fee_payable = floatval(CraydelHelperFunctions::toNumbers($foreign_student_fee_payable));
            if (empty($foreign_student_fee_payable)) {
                throw new Exception("Invalid foreign fees payable value");
            }
            $convert = new ForexController(
                $local_currency_code,
                'USD',
                $foreign_student_fee_payable
            );
            $result = $convert->convert();
            if (!$result->status) {
                throw new Exception($result->message);
            }
            if (!isset($result->data->converted_value) || empty($result->data->converted_value)) {
                throw new Exception("Unable to get forex converted value");
            }
            $foreign_student_first_year_fee_payable = CalculateFirstYearFeeCommandController::calculate(
                DB::table((new Course())->getTable())->where('course_code', $course_code)->first(),
                $result->data->converted_value
            );
            $course_details = DB::table((new Course())->getTable())
                ->where('course_code', $course_code)
                ->first();
            DB::transaction(function () use ($result, $course_code, $course_details, $foreign_student_first_year_fee_payable) {
                DB::table((new Course())->getTable())
                    ->where('course_code', $course_code)
                    ->update([
                        'foreign_student_fee_payable_usd' => floatval($result->data->converted_value),
                        'foreign_student_first_year_fee_payable_usd' => call_user_func(function () use ($course_details, $foreign_student_first_year_fee_payable) {
                            if (isset($course_details->ignore_first_year_fees_compute_based_on_total) && intval($course_details->ignore_first_year_fees_compute_based_on_total) == 1) {
                                if (is_null($course_details->foreign_student_first_year_fee_payable_usd) || empty($course_details->foreign_student_first_year_fee_payable_usd)) {
                                    return $foreign_student_first_year_fee_payable;
                                } else {
                                    return $course_details->foreign_student_first_year_fee_payable_usd;
                                }
                            } else {
                                return $foreign_student_first_year_fee_payable;
                            }
                        }),
                        'has_updates' => 1,
                        'is_picked_for_indexing' => 0,
                        'time_picked_for_indexing' => null,
                        'time_taken_to_index' => null,
                        'indexing_error' => null
                    ]);
            });
        } catch (Exception $exception) {
            (new self())->logException($exception);
        }
    }

    /**
     * Get related courses
     *
     * @param string|null $course_code
     *
     * @return array
     */
    public static function getRelatedCourse(?string $course_code): ?array
    {
        try {
            if (empty($course_code)) {
                throw new Exception("Invalid course code when getting similar courses.");
            }
            $related_courses = self::cache(CraydelHelperFunctions::slugifyString($course_code . "_related_courses"));
            if (!is_null($related_courses)) {
                return $related_courses;
            }
            $course_type = new CraydelCourseType($course_code);
            $search = new ManageSearchEngineDataHelper();
            $filter = $search->makeFilters([
                'discipline' => $course_type->getDiscipline(),
                'course_type' => $course_type->getCourseType(),
                'graduate_level' => $course_type->getGraduateLevel(),
            ]);
            $course_description = $course_type->getDescription();
            $course_name = $course_type->getCourseName();
            $similar_query = !empty($course_description) ? $course_description : $course_name;
            $result = $search->similar(
                substr($similar_query, 0, 500),
                $filter,
                3, [
                    'similarQuery' => $course_name,
                    'filters' => $search->makeFilters([
                        'discipline' => $course_type->getDiscipline(),
                        'graduate_level' => $course_type->getGraduateLevel()
                    ]),
                    'hitsPerPage' => 3,
                    'removeStopWords' => true
                ]
            );
            $related_courses = $result->data['hits'] ?? null;
            if (is_null($related_courses)) {
                self::clearCache(CraydelHelperFunctions::slugifyString($course_code . "_related_courses"));
            } else {
                self::cache(CraydelHelperFunctions::slugifyString($course_code . "_related_courses"), $related_courses);
            }
            return $related_courses;
        } catch (Exception $exception) {
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Build a new course request
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function build(Request $request): JsonResponse
    {
        try {
            return $this->buildNewCourseCommandController->build();
        } catch (Exception $exception) {
            return $this->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            )));
        }
    }

    /**
     * List courses
     * @param Request $request
     * @return JsonResponse
     */
    public function courses(Request $request): JsonResponse
    {
        return $this->listCourseQueryController->get($request);
    }

    /**
     * Create course
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            return $this->createCourseCommandController->create($request->all(), $request);
        } catch (Exception $exception) {
            return $this->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            )));
        }
    }

    /**
     * Update course
     *
     * @param string $course_code
     * @param $request
     *
     * @return JsonResponse
     */
    public function update(string $course_code, Request $request): JsonResponse
    {
        try {
            return $this->updateCourseCommandController->update(
                $request->all(),
                $request,
                $course_code
            );
        } catch (Exception $exception) {
            return $this->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            )));
        }
    }

    /**
     * Edit the institution details
     *
     * @param string $course_code
     * @return JsonResponse
     */
    public function edit(string $course_code): JsonResponse
    {
        try {
            if (empty($course_code)) {
                return $this->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('courses.errors.invalid_course_code'),
                )));
            }
            return $this->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('courses.success.listed'), [
                    'course' => call_user_func(function () use ($course_code) {
                        $course = Course::with(['linkedDisciplines'])->where('course_code', trim($course_code))->first()->toArray();
                        $linked_disciplines = $course['linked_disciplines'];
                        unset($course['linked_disciplines']);
                        $course['linked_disciplines'] = json_encode($linked_disciplines);
                        return $course;
                    }),
                    'build' => $this->buildNewCourseCommandController->build()
                ]
            )));
        } catch (Exception $exception) {
            return $this->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            )));
        }
    }

    /**
     * Import courses
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function import(Request $request): JsonResponse
    {
        try {
            return $this->importCourseCommandController->import($request);
        } catch (Exception $exception) {
            $this->logException($exception);
            return $this->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            )));
        }
    }

    /**
     * Get the course count based institution code
     * @param $institution_code
     *
     * @return JsonResponse
     */
    public function count($institution_code): JsonResponse
    {
        try {
            if (empty($institution_code)) {
                throw new Exception('Missing institution code while fetch the course count.');
            }
            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                "Counted", [
                    'course_count' => DB::table((new CourseSearchIndexList())->getTable())
                        ->select(['course_code'])
                        ->distinct()
                        ->where('institution_code', $institution_code)
                        ->whereNotNull('indexing_object_id')
                        ->count()
                ]
            ));
        } catch (Exception $exception) {
            $this->logException($exception);
            return $this->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage(), [
                    'course_count' => 0
                ]
            )));
        }
    }

    /**
     * Get the institution's course categories
     */
    public function getInstitutionCourseCategories($institution_code): JsonResponse
    {
        try {
            if (empty($institution_code)) {
                throw new Exception('Missing institution code while fetch the course count.');
            }
            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                "Listed", [
                    'academic_disciplines' => call_user_func(function () use ($institution_code) {
                        $academic_discipline_table = (new AcademicDiscipline())->getTable();
                        $course_table = (new Course())->getTable();
                        $academic_disciplines = DB::table((new Course())->getTable())
                            ->leftJoin((new AcademicDiscipline())->getTable(), $course_table . '.discipline_code', '=', $academic_discipline_table . '.id')
                            ->where('institution_code', $institution_code)
                            ->whereNotNull('indexing_object_id')
                            ->select($academic_discipline_table . '.discipline_name')
                            ->get();
                        $Disciplines = [];
                        foreach ($academic_disciplines as $item) {
                            if (isset($item->discipline_name) && !in_array($item->discipline_name, $Disciplines)) {
                                array_push($Disciplines, $item->discipline_name);
                            }
                        }
                        return collect($Disciplines)->unique()->values()->all();
                    })
                ]
            ));
        } catch (Exception $exception) {
            $this->logException($exception);
            return $this->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage(), [
                    'course_count' => 0
                ]
            )));
        }
    }

    /**
     * Process imported courses
     *
     * @return void
     */
    public function processImportedCourses()
    {
        try {
            $this->importCourseCommandController->process();
        } catch (Exception $exception) {
            $this->logException($exception);
        }
    }

    /**
     * Feature a course
     *
     * @param string|null $course_code
     * @param Request $request
     * @return JsonResponse
     */
    public function feature(?string $course_code, Request $request): JsonResponse
    {
        return $this->featureCourseCommandController->feature($request, $course_code);
    }

    public function delete(?string $course_code, Request $request): JsonResponse
    {
        return $this->deleteCourseCommandController->delete($request, $course_code);
    }

    public function unpublish(?string $course_code, Request $request): JsonResponse
    {
        return $this->unpublishCourseCommandController->unpublish($request, $course_code);
    }

    public function publish(?string $course_code, Request $request): JsonResponse
    {
        return $this->publishCourseCommandController->publish($request, $course_code);
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        return $this->bulkDeleteCourseCommandController->bulkDelete($request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkPublish(Request $request): JsonResponse
    {
        return $this->bulkPublishCourseCommandController->bulkPublish($request);
    }

    public function bulkUnpublish(Request $request): JsonResponse
    {
        return $this->bulkUnPublishCourseCommandController->bulkUnpublish($request);
    }

    /**
     * @param Request $request
     * @param $institution_code
     * @return JsonResponse
     */
    public function publishRelatedCourseToInstitution(Request $request): JsonResponse
    {
        try {
            $institution_code=$request->institution_code;
            if (empty($institution_code)) {
                throw new Exception('Missing institution code while publishing the related course.');
            }
            $user = GetLoggedIUserHelper::getUser($request);
            $username = $user->username ?? 'Course Admin';
            DB::table((new Course())->getTable())->where('institution_code', $institution_code)->update([
                'is_published' => 1,
                'is_active' => 1,
                'should_unpublish'=>0,
                'deleted_by' => $username,
                'deleted_at' => Carbon::now()->toDateTimeString(),
            ]);
            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('courses.success.published')
            ));
        } catch (Exception $exception) {
            $this->logException($exception);
            return $this->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            )));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function unPublishRelatedCourseToInstitution(Request $request): JsonResponse
    {
        try {
            $institution_code = $request->input('institution_code');

            if (empty($institution_code)) {
                throw new Exception('Missing institution code while publishing the related course.');
            }
            $user = GetLoggedIUserHelper::getUser($request);
            $username = $user->username ?? 'Course Admin';
            DB::table((new Course())->getTable())->where('institution_code', $institution_code)->update([
                'is_published' => 0,
                'is_active' => 0,
                'should_unpublish' => 1,
                'updated_by' => $username,
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('courses.success.unpublished')
            ));
        } catch (Exception $exception) {
            $this->logException($exception);
            return $this->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            )));
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteRelatedCourseToInstitution(Request $request): JsonResponse
    {
        try {
            $institution_code = $request->input('institution_code');

            if (empty($institution_code)) {
                throw new Exception('Missing institution code while publishing the related course.');
            }
            $user = GetLoggedIUserHelper::getUser($request);
            $username = $user->username ?? 'Course Admin';
            DB::table((new Course())->getTable())->where('institution_code', $institution_code)->update([
                'is_published' => 0,
                'is_active' => 0,
                'is_deleted' => 1,
                'should_unpublish'=>1,
                'deleted_by' => $username,
                'deleted_at' => Carbon::now()->toDateTimeString(),
            ]);
            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('courses.success.deleted')
            ));
        } catch (Exception $exception) {
            $this->logException($exception);
            return $this->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            )));
        }
    }

    /**
     * Get active academic disciplines
     */
    public function getActiveAcademicDisciplines(): JsonResponse
    {
        try {
            $with_courses = self::cache('active_academic_disciplines');
            if (is_null($with_courses)) {
                self::cache(
                    'active_academic_disciplines',
                    AcademicDiscipline::active()->get()
                );
            }
            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                "Loaded",
                (object)[
                    'academic_disciplines' => self::cache('active_academic_disciplines')
                ]
            ));
        } catch (Exception $exception) {
            $this->logException($exception);
            return $this->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            )));
        }
    }
}
