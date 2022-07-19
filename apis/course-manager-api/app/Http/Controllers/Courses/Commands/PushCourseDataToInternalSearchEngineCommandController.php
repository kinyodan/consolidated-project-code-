<?php

namespace App\Http\Controllers\Courses\Commands;

use App\Http\Controllers\CraydelTypes\CraydelCourseIndexListItemType;
use App\Http\Controllers\CraydelTypes\CraydelCourseIndexListItemTypeCollection;
use App\Http\Controllers\Helpers\ManageInternalSearchEngineDataHelper;
use App\Http\Controllers\Helpers\ManageSearchEngineDataHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Models\CourseSearchIndexList;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PushCourseDataToInternalSearchEngineCommandController
{
  use CanLog;
  
  /**
   * @var array $search_engine_objects
   */
  public array $search_engine_objects = [];
  /**
   * @var string|null $course_code
   */
  protected ?string $course_code;
  /**
   * @var CraydelCourseIndexListItemTypeCollection|null $course_copies_collection
   */
  protected ?CraydelCourseIndexListItemTypeCollection $course_copies_collection;
  
  /**
   * Constructor
   * @param string|null $course_code
   * @throws Exception
   */
  public function __construct(?string $course_code)
  {
    $this->course_code = $course_code;
    $course_copies = DB::table((new CourseSearchIndexList())->getTable())
      ->where('course_code', $course_code)
      ->get(['id']);
    if (!empty($course_copies)) {
      $this->course_copies_collection = new CraydelCourseIndexListItemTypeCollection();
      foreach ($course_copies as $course_copy) {
        if (isset($course_copy->id)) {
          $this->course_copies_collection->push(new CraydelCourseIndexListItemType($course_copy->id));
        }
      }
    } else {
      $this->course_copies_collection = null;
    }
  }
  
  /**
   * Make search engine object
   *
   * @return PushCourseDataToInternalSearchEngineCommandController
   */
  public function make(): PushCourseDataToInternalSearchEngineCommandController
  {
    try {
      $this->search_engine_objects = call_user_func(function () {
        $list = [];
        foreach ($this->course_copies_collection as $course) {
          $list[] = [
            "objectID" => $course->getIndexingObjectId(),
            "url_course_slug" => $course->getUrlCourseSlug(),
            "course_name" => $course->getCourseName(),
            "course_name_slug" => $course->getCourseNameSlug(),
            "course_rating" => $course->getCourseRating(),
            "institution_code" => $course->getInstitutionCode(),
            "institution_name" => $course->getInstitution(),
            "institution_ranking" => $course->getInstitutionRanking(),
            "institution_continent" => $course->getInstitutionContinent(),
            "description" => $course->getDescription(),
            "country" => $course->getCountry(),
            "course_overview" => $course->getCourseOverview(),
            "discipline" => $course->getDiscipline(),
            "course_type" => $course->getCourseType(),
            "graduate_level" => $course->getGraduateLevel(),
            "attendance_type" => $course->getAttendanceType(),
            "learning_mode" => $course->getLearningMode(),
            "enrollment_details" => $course->getEnrollmentDetails(),
            "course_requirements" => $course->getCourseRequirements(),
            "currency" => $course->getCurrency(),
            "standard_fee_payable" => $course->getStandardFeePayable(),
            "course_small_image" => $course->getCourseSmallImage(),
            "course_image" => $course->getCourseImage(),
            "course_structure_breakdown" => $course->getCourseStructureBreakdown(),
            "course_duration" => $course->getCourseDuration(),
            "course_duration_category" => $course->getCourseDurationCategory(),
            "standard_fee_payable_usd" => ceil($course->getStandardFeePayableUsd()),
            "foreign_student_fee_payable_usd" => ceil($course->getForeignStudentFeePayableUsd()),
            "course_code" => $course->getCourseCode(),
            "accredited_by" => $course->getAccreditedBy(),
            "accredited_by_acronym" => $course->getAccreditedByAcronym(),
            "accreditation_organization_url" => $course->getAccreditationOrganizationUrl(),
            "maximum_scholarship_available" => $course->getMaximumScholarshipAvailable(),
            "is_featured" => $course->getIsFeatured(),
            "standard_fee_billing_type" => $course->getStandardFeeBillingType(),
            "popularity" => $course->getPopularity(),
            "standard_first_year_fee_payable_usd" => $course->getStandardFirstYearFeePayableUsd(),
            "foreign_student_first_year_fee_payable_usd" => $course->getForeignStudentFirstYearFeePayableUsd()
          ];
        }
        return $list;
      });
      return $this;
    } catch (Exception $exception) {
      $this->logException($exception);
      return $this;
    }
  }
  
  /**
   * Push data to the search engine
   *
   * @return void
   */
  public function push()
  {
    try {
      
      $result = (new ManageInternalSearchEngineDataHelper(
        $this->search_engine_objects
      ))->push();
      if ($result->status) {
        $this->update($result->data->object_ids);
      } else {
        $object_ids = collect($this->search_engine_objects)->map(function ($object) {
          return [
            'objectID' => $object['objectID'] ?? null
          ];
        })->reject(function ($object) {
          return !isset($object['objectID']);
        })->reject(function ($object) {
          return is_null($object['objectID']);
        })->flatten(1)->toArray();
        $this->update($object_ids, $result->message);
      }
    } catch (Exception $exception) {
      $this->logException($exception);
    }
  }
  
  /**
   * Make the course as indexed
   *
   * @param array $object_ids
   * @param string|null $error
   *
   * @return void
   */
  private function update(array $object_ids, ?string $error = null)
  {
    try {
      DB::transaction(function () use ($object_ids, $error) {
        foreach ($object_ids as $key => $object_id) {
          if (!is_null($object_id)) {
            DB::table((new CourseSearchIndexList())->getTable())
              ->where('indexing_object_id', $object_id)
              ->update([
                'is_active' => 1,
                'is_published' => 1,
                'has_updates' => 0,
                'is_picked_for_indexing' => 0,
                'time_taken_to_index' => DB::raw("timediff('" . Carbon::now()->toDateTimeString() . "', time_picked_for_indexing)"),
                'is_picked_for_unpublishing' => 0
              ]);
          }
        }
      });
    } catch (Exception $exception) {
      $this->logException($exception);
    }
  }
  
  /**
   * Delete data from the search engine
   *
   * @return void
   */
  public function delete(?bool $should_permanently_delete = false)
  {
    try {
      $result = (new ManageSearchEngineDataHelper(
        $this->search_engine_objects
      ))->delete();
      if ($result->status) {
        if ($should_permanently_delete) {
          $this->permanentlyDeleteCourseSearchIndex();
        } else {
          $this->updateAfterUnpublishing();
        }
      } else {
        $this->updateAfterUnpublishing($result->message);
      }
    } catch (Exception $exception) {
      $this->logException($exception);
    }
  }
  
  /**
   * Permanently delete course search index
   */
  private function permanentlyDeleteCourseSearchIndex()
  {
    try {
      DB::transaction(function () {
        DB::table((new CourseSearchIndexList())->getTable())
          ->where('course_code', $this->course_code)
          ->delete();
      });
    } catch (Exception $exception) {
      $this->logException($exception);
    }
  }
  
  /**
   * Update the course details after unpublishing the course
   *
   * @param string|null $error
   * @return void
   */
  private function updateAfterUnpublishing(?string $error = null)
  {
    try {
      DB::transaction(function () use ($error) {
        DB::table((new CourseSearchIndexList())->getTable())
          ->where('course_code', $this->course_code)
          ->update([
            'is_active' => 0,
            'is_published' => 0,
            'should_unpublish' => 0,
            'is_picked_for_unpublishing' => 0,
            'updated_at' => Carbon::now()->toDateTime(),
            'indexing_error' => !empty($error) ? trim($error) : null
          ]);
      });
    } catch (Exception $exception) {
      $this->logException($exception);
    }
  }
}
