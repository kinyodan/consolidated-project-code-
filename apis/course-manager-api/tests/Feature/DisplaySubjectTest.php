<?php
namespace Feature;

use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\DisplaySubjects;
use App\Models\Grade;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use Illuminate\Support\Facades\Event;
use TestingHelperFunction;

class DisplaySubjectTest extends TestCase
{
    use TestingHelperFunction;

    public function testListingDisplaySubjectsRouteExists()
    {
        $response = $this->post('list-display-subjects');
        $response->assertTrue(true);
    }

    public function testIfUserCanUpdateADisplaySubject()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $subject = DisplaySubjects::factory()->count(1)->create();
        $subject = $subject->first();
        $this->post('courses/admin/' . $subject->id . '/update-display-subjects',
            ['subject_title' => 'Subject One', 'education_type_id' => $subject->education_type_id, 'country_id' => $subject->country_id, 'academic_disciplines_id' => $subject->academic_disciplines_id, 'subject_title_description' => $subject->subject_title_description, 'display_order' => $subject->display_order], [
                'token' => $token,
                'locale' => 'en'
            ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('display_subjects.success.is_updated')
        ])->seeInDatabase((new DisplaySubjects())->getTable(), [
            'id' => $subject->id,
            'subject_title' => 'Subject One',
        ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new DisplaySubjects())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    public function testIfUserCanDeleteADisplaySubject()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $subject = DisplaySubjects::factory()->count(2)->create();
        $subject = $subject->first();
        $this->post('courses/admin/' . $subject->id . '/delete-display-subjects', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('display_subjects.success.is_deleted')
        ]);
        static::assertCount(
            0,
            DB::table((new DisplaySubjects())->getTable())->where('id', $subject->id)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new DisplaySubjects())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }
}
