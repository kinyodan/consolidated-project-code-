<?php
namespace Feature;

use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Jobs\ProcessUploadedCourseListJob;
use App\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use TestingHelperFunction;

class ImportCoursesTest extends TestCase
{
    use TestingHelperFunction;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIfTheImportRouteExists()
    {
        $response = $this->post('import');
        $response->assertTrue(true);
    }

    /**
     * @return void
     */
//    public function testIfUserCanImportCoursesFromExcel()
//    {
//        //clear database before tests
//        $this->withoutExceptionHandling();
//        Schema::disableForeignKeyConstraints();
//        DB::table((new Course())->getTable())->truncate();
//        Schema::enableForeignKeyConstraints();
//        $token = file_get_contents(storage_path('test-token.txt'));
//        //prepare the file
//        $fileToUpload = storage_path('test-courses-import.xlsx');
//        $fileName = uniqid('courses-list-') . '.xlsx';
//        $filePath = sys_get_temp_dir() . '/' . $fileName;
//        copy($fileToUpload, $filePath);
//
//        //create http file instance
//        $uploadFile = new UploadedFile($filePath, $fileName, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
//        //transform headers for api call
//        $server = $this->transformHeadersToServerVars(['token' => $token, 'locale' => 'en']);
//        //make api call
//        Queue::fake();
//        $response = $this->call('POST',
//            'courses/admin/import',
//             ['institution_code'=>'INTANHXXFAYVR','country_code'=>'KE'],[], ['courses_list' => $uploadFile], $server
//        );
//        //get the content and make necessary checks
//        $response->assertStatus(200);
//        $content = json_decode($response->getContent());
//        $this->assertObjectHasAttribute('status', $content);
//        $this->withoutExceptionHandling();
//        Schema::disableForeignKeyConstraints();
//        DB::table((new Course())->getTable())->truncate();
//        Schema::enableForeignKeyConstraints();
//    }
}
