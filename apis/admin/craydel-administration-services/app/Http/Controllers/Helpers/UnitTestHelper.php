<?php
namespace App\Http\Controllers\Helpers;

use App\Models\GraduationYear;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\SchoolStream;
use App\Models\UniversalSchoolClass;
use Illuminate\Support\Facades\DB;

class UnitTestHelper
{
    /**
     * Get a list of school ids
     *
     * @return array
    */
    public static function schoolIds(): array
    {
        $schools = DB::table((new School())->getTable())
            ->get(['id'])
            ->values();

        $_ids = [];

        foreach ($schools as $school){
            if(isset($school->id) && !in_array($school->id, $_ids)){
                $_ids[] = $school->id;
            }
        }

        return $_ids;
    }

    /**
     * Get a list of school classes
    */
    public static function schoolClassIds($curriculum_id = 1): array
    {
        $classes = DB::table((new UniversalSchoolClass())->getTable())
            ->where('curriculum_id', $curriculum_id)
            ->get(['id'])
            ->values();

        $_ids = [];

        foreach ($classes as $class){
            if(isset($class->id) && !in_array($class->id, $_ids)){
                $_ids[] = $class->id;
            }
        }

        return $_ids;
    }

    /**
     * Get a list of school streams
     */
    public static function schoolStreamIds($school_id = 1): array
    {
        $streams = DB::table((new SchoolStream())->getTable())
            ->where('school_id', $school_id)
            ->get(['id'])
            ->values();

        $_ids = [];

        foreach ($streams as $stream){
            if(isset($stream->id) && !in_array($stream->id, $_ids)){
                $_ids[] = $stream->id;
            }
        }

        return $_ids;
    }

    /**
     * Get possible curriculum
     */
    public static function graduationYearsIds(): array
    {
        $years = DB::table((new GraduationYear())->getTable())
            ->where('is_active', 1)
            ->get(['id'])
            ->values();

        $_ids = [];

        foreach ($years as $year){
            if(isset($year->id) && !in_array($year->id, $_ids)){
                $_ids[] = $year->id;
            }
        }

        return $_ids;
    }
}
