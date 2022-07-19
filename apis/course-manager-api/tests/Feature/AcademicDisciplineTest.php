<?php
namespace Feature;

use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Models\AcademicDiscipline;
use App\Models\Course;
use App\Models\CourseAcademicDiscipline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use TestCase;
use TestingHelperFunction;

class AcademicDisciplineTest extends TestCase
{
    use TestingHelperFunction;

    /**
     * Setup tests
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        $db_name = DB::connection()->getDatabaseName();
        $tables = DB::select('SHOW TABLES');

        foreach($tables as $table){
            if ($table == 'migrations') {
                continue;
            }

            $table_name = $table->{"Tables_in_{$db_name}"};
            DB::table($table_name)->truncate();
        }

        Schema::enableForeignKeyConstraints();
        $this->artisan('db:seed');
    }

    /**
     * Test that course types has data
     */
    public function testIfAcademicDisciplineHelperDisciplinesFunctionReturnsAnObjectArray()
    {
        $academic_disciplines = AcademicDisciplineHelper::disciplines();
        $this->assertIsArray($academic_disciplines);
        $this->assertNotEmpty($academic_disciplines);
        $this->assertIsObject($academic_disciplines[0]);
        $this->assertObjectHasAttribute('id', $academic_disciplines[0]);
        $this->assertObjectHasAttribute('discipline_code', $academic_disciplines[0]);
        $this->assertObjectHasAttribute('discipline_name', $academic_disciplines[0]);
    }

    /**
     * Test if user can request for academic disciplines
     */
    public function testIfUserCanRequestForAcademicDisciplines()
    {
        Course::factory()->count(3)->create();
        CourseAcademicDiscipline::factory()->count(2)->create();

        $response = $this->get('courses/academic-disciplines', [
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => 'Loaded'
        ])->seeJsonStructure([
            'data' => [
                'academic_disciplines'
            ]
        ]);

        $result = json_decode($response->response->getContent());
        $result = $result->data->academic_disciplines[0];

        $this->assertTrue(isset($result->id) && !empty($result->id));
        $this->assertTrue(isset($result->discipline_code) && !empty($result->discipline_code));
        $this->assertTrue(isset($result->discipline_name) && !empty($result->discipline_name));
        $this->assertTrue(isset($result->course_count));
        $this->assertTrue(isset($result->discipline_small_icon) && !empty($result->discipline_small_icon));
        $this->assertTrue(isset($result->discipline_large_icon) && !empty($result->discipline_large_icon));
        $this->assertTrue(isset($result->course_count) && intval($result->course_count) > 0);
        $this->assertTrue(isset($result->is_deleted));
        $this->assertObjectHasAttribute('seo_page_title', $result);
        $this->assertObjectHasAttribute('seo_page_description', $result);
        $this->assertObjectHasAttribute('seo_page_h1_title', $result);
        $this->assertObjectHasAttribute('seo_page_keywords', $result);
    }

    /**
     * Test if user can request for active academic disciplines
     */
    public function testIfUserCanRequestForActiveAcademicDisciplines()
    {
        Course::factory()->count(5)->create();
        $response = $this->get('/courses/rpc/academic-disciplines', [
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => 'Loaded'
        ])->seeJsonStructure([
            'data' => [
                'academic_disciplines'
            ]
        ]);
        $result = json_decode($response->response->getContent());
        $result = $result->data->academic_disciplines[0];
        $this->assertTrue(isset($result->id) && !empty($result->id));
        $this->assertTrue(isset($result->discipline_code) && !empty($result->discipline_code));
        $this->assertTrue(isset($result->discipline_name) && !empty($result->discipline_name));
        $this->assertTrue(isset($result->discipline_small_icon) && !empty($result->discipline_small_icon));
        $this->assertTrue(isset($result->discipline_large_icon) && !empty($result->discipline_large_icon));
        $this->assertTrue(isset($result->is_deleted));
        $this->assertObjectHasAttribute('seo_page_title', $result);
        $this->assertObjectHasAttribute('seo_page_description', $result);
        $this->assertObjectHasAttribute('seo_page_h1_title', $result);
        $this->assertObjectHasAttribute('seo_page_keywords', $result);
    }

    /**
     * Test if user can get the academic discipline courses
     */
    public function testIfUserCanGetTheAcademicDisciplineCourses()
    {
        Course::factory()->count(3)->create([
            'is_active' => 1
        ]);

        CourseAcademicDiscipline::factory()->count(2)->create();
        $data = AcademicDiscipline::hasPublishedCourses()
            ->where('is_deleted', 0)
            ->orderBY('discipline_name')
            ->get();
        $this->assertNotEmpty($data);
    }
}
