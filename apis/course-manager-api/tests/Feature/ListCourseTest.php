<?php
namespace Feature;

use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Course;
use TestCase;
use TestingHelperFunction;

class ListCourseTest extends TestCase
{
    use TestingHelperFunction;
	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testExample()
	{
		$this->assertTrue(true);
	}

    public  function  testIfTheRightCourseAreSentToUsers(){
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        Course::factory()->count(1)->create();
        $response=$this->get('courses/admin/',[
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true
        ])->seeJsonStructure([
            'data' => [
                'items' => [
                    '*' => [
                        'country_code',
                        'institution_code',
                        'course_name',
                        'course_rating',
                    ]
                ]
            ]
        ]);
        $response = json_decode($response->response->getContent());
        $course=$response->data->items[0];
        $this->assertEquals($course->is_deleted,0);
        $this->assertEquals($course->is_flagged_deletion,0);

    }
}
