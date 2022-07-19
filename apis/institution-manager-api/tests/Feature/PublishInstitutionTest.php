<?php
namespace Feature;

use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Jobs\PushInstitutionDataToSearchEngineJob;
use App\Jobs\RemoveInstitutionDataFromSearchEngineJob;
use App\Models\Institution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use TestingHelperFunction;

class PublishInstitutionTest extends TestCase
{
    use TestingHelperFunction;

    /**
     * Test if the user access the institutions list endpoint.
     */
    public function testIfThePublishInstitutionsExistsRouteExists()
    {
        $this->withoutExceptionHandling();
        $this->post('institutions/admin/{institution_code}/publish');
        $this->assertResponseStatus(200);
    }

    public function testIfUserCanPublishInstitution()
    {
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
        $token = file_get_contents(storage_path('test-token.txt'));
        $institution = Institution::factory()->count(1)->create([
            'country_code' => 'KE',
        ]);
        $institution = $institution->first();
        Queue::fake();
        $this->post('institutions/admin/' . $institution->institution_code . '/publish', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.published')
        ])->seeInDatabase((new Institution())->getTable(), [
            'institution_code' => $institution->institution_code,
            'is_published' => 1,
            'is_featured' => 1,
            'is_active' => 1,
             'has_updates' =>1,
        ]);
    }
}
