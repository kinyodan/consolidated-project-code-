<?php
namespace Feature;

use App\Http\Controllers\Helpers\JWTHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Institution;
use App\Models\InstitutionHighlight;
use Faker\Factory;
use TestCase;
use TestingHelperFunction;

class InstitutionHighlightTest extends TestCase
{
    use TestingHelperFunction;

    /**
     * @var
     */
    protected $institution, $token, $user;

    /**
     * Set up the test
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
        $this->clearDB();
        $this->artisan('db:seed');

        $institutions = Institution::factory()->count(1)->create([
            'institution_code' => $this->default_institution_code,
            'is_featured' => 1
        ]);

        $this->institution = $institutions->first();
        $this->token = trim(file_get_contents(storage_path('test-token.txt')));
        $this->user = JWTHelper::decode($this->token);
    }

    /**
     * Test if user can get the institution's highlights
    */
    public function testIfUserCanListTheInstitutionHighlights(){
        InstitutionHighlight::factory()->count(10)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        $this->get('institutions/admin/'.$this->institution->institution_code.'/highlights', [
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.highlights_listed')
        ])->seeJsonStructure([
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'key_highlight',
                        'key_highlight_description',
                        'display_order'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Test if user can get the details of a single institution highlight
    */
    public function testIfUserCanGetTheDetailsOfASingleInstitution(){
        $highlights = InstitutionHighlight::factory()->count(10)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        $highlight = $highlights->random(1)->first();

        $this->get('institutions/admin/'.$this->institution->institution_code.'/highlights/'.$highlight->id, [
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.highlight_shown')
        ])->seeJsonStructure([
            'data' => [
                'highlight' => [
                    'key_highlight',
                    'key_highlight_description',
                    'display_order'
                ]
            ]
        ]);
    }

    /**
     * Test is user can add an new institution highlight
    */
    public function testIfUserCanAddAnInstitutionHighlight(){
        $key_highlight = Factory::create()->company;
        $key_highlight_description = Factory::create()->text;
        $display_order = Factory::create()->randomNumber(1);

        $this->post('institutions/admin/'.$this->institution->institution_code.'/highlights/add', [
            'institution_code' => $this->institution->institution_code,
            'key_highlight' => $key_highlight,
            'key_highlight_description' => $key_highlight_description,
            'display_order' => $display_order
        ],[
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.highlight_saved')
        ])->seeInDatabase((new InstitutionHighlight())->getTable(),[
            'institution_code' => $this->institution->institution_code,
            'key_highlight' => $key_highlight,
            'key_highlight_description' => $key_highlight_description,
            'display_order' => $display_order,
            'is_active' => 1,
            'is_deleted' => 0,
            'created_by' => $this->user->email
        ]);
    }

    /**
     * Test if user can update an institution highlight
    */
    public function testIfUserCanUpdateAnInstitutionHighlight(){
        $highlights = InstitutionHighlight::factory()->count(10)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        $highlight = $highlights->random(1)->first();
        $key_highlight = Factory::create()->unique()->company;
        $key_highlight_description = Factory::create()->text;
        $display_order = 2;

        $this->post('institutions/admin/'.$this->institution->institution_code.'/highlights/'.$highlight->id.'/update', [
            'institution_code' => $this->institution->institution_code,
            'key_highlight' => $key_highlight,
            'key_highlight_description' => $key_highlight_description,
            'display_order' => $display_order
        ],[
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.highlight_updated')
        ])->seeInDatabase((new InstitutionHighlight())->getTable(),[
            'institution_code' => $this->institution->institution_code,
            'key_highlight' => $key_highlight,
            'key_highlight_description' => $key_highlight_description,
            'display_order' => $display_order,
            'updated_by' => $this->user->email
        ]);
    }

    /**
     * Test if the user can delete the institution highlight
    */
    public function testIfUserCanDeleteTheInstitutionHighlight(){
        $highlights = InstitutionHighlight::factory()->count(10)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        $highlight = $highlights->random(1)->first();

        $this->post('institutions/admin/'.$this->institution->institution_code.'/highlights/'.$highlight->id.'/delete', [],[
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.highlight_deleted')
        ])->notSeeInDatabase((new InstitutionHighlight())->getTable(),[
            'id' => $highlight->id
        ]);
    }

    /**
     * Reset the properties to save memory after each test.
     * @see https://kriswallsmith.net/post/18029585104/faster-phpunit
     */
    protected function tearDown(): void
    {
        (function () {
            foreach ((new \ReflectionObject($this))->getProperties() as $prop) {
                if ($prop->isStatic() || strpos($prop->getDeclaringClass()->getName(), 'PHPUnit\\') === 0) {
                    continue;
                }

                unset($this->{$prop->getName()});
            }
        })->call($this);
    }
}
