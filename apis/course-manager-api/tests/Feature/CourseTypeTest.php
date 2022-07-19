<?php
namespace Feature;

use App\Http\Controllers\Helpers\CourseTypesHelper;
use Illuminate\Support\Facades\Schema;

class CourseTypeTest extends \TestCase
{
    use \TestingHelperFunction;

    /**
     * Test that course types has data
    */
    public function testIfCourseTypeHelperTypesFunctionReturnsAnObjectArray(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        $this->artisan('db:seed');
        Schema::enableForeignKeyConstraints();

        $course_types = CourseTypesHelper::types();
        $this->assertIsArray($course_types);
        $this->assertTrue(count($course_types) > 0);
        $this->assertIsObject($course_types[0]);
        $this->assertObjectHasAttribute('id', $course_types[0]);
        $this->assertObjectHasAttribute('name', $course_types[0]);
    }
}
