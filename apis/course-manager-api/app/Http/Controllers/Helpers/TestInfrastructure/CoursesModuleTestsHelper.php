<?php
namespace App\Http\Controllers\Helpers\TestInfrastructure;

use App\Models\AcademicDiscipline;
use App\Models\Course;

class CoursesModuleTestsHelper
{
    /**
     * Seed
    */
    public function seed(){
        Course::factory()->count(10)->create([
            'popularity' => null
        ]);
    }

    /**
     * Get a random academic discipline ID
    */
    public static function getRandomAcademicDisciplineIDs(){
        $academic_disciplines = collect(AcademicDiscipline::take(5)->get('id'))->map(function ($item){
            return [
                'id' => $item->id ?? null
            ];
        })->values()->toArray();

        $result = call_user_func(function () use($academic_disciplines){
            $_result = [];

            foreach ($academic_disciplines as $discipline){
                if(isset($discipline['id']) && !empty($discipline['id']) && !in_array($discipline['id'], $_result)){
                    $_result[] = $discipline['id'];
                }
            }

            return $_result;
        });

        return array_values($result);
    }
}
