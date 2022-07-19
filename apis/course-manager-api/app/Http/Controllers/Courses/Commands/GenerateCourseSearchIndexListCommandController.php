<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\UnpublishCourseIndexSearchEngineJob;
use App\Models\Course;
use App\Models\CourseSearchIndexList;
use Exception;
use Illuminate\Support\Facades\DB;

class GenerateCourseSearchIndexListCommandController
{
    use CanLog;

    /**
     * Create the course search index list
     *
     * @param string|null $course_code
     * @return void
     * @throws Exception
     */
    public static function generate(?string $course_code): void{
        if(CraydelHelperFunctions::isNull($course_code)){
            throw new Exception('Null course code provided.');
        }

        $course = Course::with(['linkedDisciplines'])->where('course_code', $course_code)->first();

        if(is_null($course->id)){
            throw new Exception('Could not locate the course based on the provided course code.');
        }

        if(!is_null($course->linkedDisciplines) && count($course->linkedDisciplines) > 0){
            self::makeCopies($course);
        }
    }

    /**
     * Make course copies
     * @param object $course
     * @return void
     */
    private static function makeCopies(object $course): void{
        $linked_disciplines = $course->linkedDisciplines ?? null;
        $copies = [];

        foreach ($linked_disciplines as $discipline){
            if(is_callable([$course, 'getAttributes'])){
                $copy = CraydelHelperFunctions::removeMultipleKeysFromArray(
                    $course->getAttributes(), [
                        'indexing_object_id',
                        'id',
                        'discipline_code',
                        'time_taken_to_index',
                        'course_academic_category_is_generated',
                        'batch_no'
                    ]
                );

                $copy['discipline_code'] = $discipline->id;
                $copy['indexing_object_id'] = $course->course_code . $discipline->id;
                $copies[] = $copy;
            }
        }

        if(count($copies) > 0){
            DB::transaction(function () use($copies, $course){
                self::removeStaleCopies($course, $copies);

                DB::table((new CourseSearchIndexList())->getTable())->where('course_code', $course->course_code)->delete();
                DB::table((new CourseSearchIndexList())->getTable())->upsert(
                    $copies,
                    ['indexing_object_id']
                );

                DB::table((new Course())->getTable())
                    ->where('course_code', $course->course_code)
                    ->update([
                        'has_updates' => 0,
                        'is_picked_for_indexing' => 0
                    ]);
            });
        }
    }

    /**
     * Get copies to remove from index
    */
    protected static function removeStaleCopies(object $course, $copies){
        $current_copies_indexing_object_ids = DB::table((new CourseSearchIndexList())->getTable())
            ->where('course_code', $course->course_code)
            ->select([
                'indexing_object_id'
            ])
            ->get(['indexing_object_id']);

        $current_course_indexing_ids = [];

        foreach ($current_copies_indexing_object_ids as $copies_indexing_object){
            if (isset($copies_indexing_object->indexing_object_id) && !empty($copies_indexing_object->indexing_object_id)){
                $current_course_indexing_ids[] = $copies_indexing_object->indexing_object_id;
            }
        }

        $new_copies_indexing_object_ids = [];

        foreach ($copies as $copy){
            if (isset($copy['indexing_object_id']) && !empty($copy['indexing_object_id'])){
                $new_copies_indexing_object_ids[] = $copy['indexing_object_id'];
            }
        }

        $copies_to_unindex = array_diff(array_values($current_course_indexing_ids), array_values($new_copies_indexing_object_ids));

        DB::table((new CourseSearchIndexList())->getTable())
            ->whereIn('indexing_object_id', $copies_to_unindex)
            ->delete();

        (new GenerateCourseSearchIndexListCommandController)->logMessage("Indexes to delete : " . print_r($copies_to_unindex, true));

        foreach ($copies_to_unindex as $key => $val){
            (new GenerateCourseSearchIndexListCommandController)->logMessage("Index to remove " . $val);

            if(!empty($val)){
                dispatch((new UnpublishCourseIndexSearchEngineJob($val)))->onQueue('delete_course_index');
            }
        }
    }
}
