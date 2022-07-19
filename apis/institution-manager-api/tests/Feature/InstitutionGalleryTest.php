<?php
namespace Feature;

use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Institution;
use App\Models\InstitutionGallery;
use App\Models\InstitutionReview;
use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use TestCase;
use TestingHelperFunction;

class InstitutionGalleryTest extends TestCase
{
    use TestingHelperFunction;

    /**
     * Test if the user can add an image into the institution gallery
     */
    public function testIfUserCanAddAnImageIntoTheInstitutionGallery(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionReview())->getTable())->truncate();
        DB::table((new InstitutionGallery())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $institution_code = Str::random(10);

        Institution::factory()->count(1)->create([
            'institution_code' => $institution_code,
            'is_featured' => 1
        ]);

        $token = file_get_contents(storage_path('test-token.txt'));

        $payload = [
            'asset_name' => Str::random(20),
            'asset_position' => 1,
            'asset_caption' => Str::random(20),
            'is_featured' => 1,
            'type' => 'VideoLink',
            'asset_url' => Factory::create()->url
        ];

        $this->post('institutions/admin/'.$institution_code.'/gallery/add',
            $payload
            ,[
                'token' => $token,
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.gallery_item_created')
            ])->seeInDatabase(
                (new InstitutionGallery())->getTable(),
                call_user_func(function () use($payload){
                    return [
                        'asset_name' => $payload['asset_name'],
                        'asset_position' => $payload['asset_position'],
                        'asset_description' => $payload['asset_caption'],
                        'is_featured' => $payload['is_featured'],
                        'type' => $payload['type'],
                        'video_url' => $payload['asset_url']
                    ];
                })
            );

        Schema::disableForeignKeyConstraints();
        DB::table((new InstitutionReview())->getTable())->truncate();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionGallery())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if the user can view the gallery items by institution code
    */
    public function testIfUserCanViewTheInstitutionGallery(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionReview())->getTable())->truncate();
        DB::table((new InstitutionGallery())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $institution_code = Str::random(10);

        Institution::factory()->count(1)->create([
            'institution_code' => $institution_code,
            'is_featured' => 1
        ]);

        InstitutionGallery::factory()->count(2)->create([
            'institution_code' => $institution_code
        ]);

        $token = file_get_contents(storage_path('test-token.txt'));

        $response = $this->get('institutions/admin/'.$institution_code.'/gallery',
            [
                'token' => $token,
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.gallery_listed')
            ]);

        $response = $response->response->getContent();
        $this->assertJson($response);
        $response = isset($response) ? json_decode($response) : null;
        $this->assertObjectHasAttribute('data', $response);
        $this->assertIsArray($response->data);

        $single_gallery_item = $response->data[0];
        $this->assertIsObject($single_gallery_item);
        $this->assertObjectHasAttribute('type', $single_gallery_item);
        $this->assertObjectHasAttribute('asset_name', $single_gallery_item);
        $this->assertObjectHasAttribute('asset_description', $single_gallery_item);
        $this->assertObjectHasAttribute('asset_code', $single_gallery_item);

        if($single_gallery_item->type == 'VideoLink'){
            $this->assertObjectHasAttribute('video_url', $single_gallery_item);
        }elseif ($single_gallery_item == 'Image'){
            $this->assertObjectHasAttribute('big_image_url', $single_gallery_item);
        }

        Schema::disableForeignKeyConstraints();
        DB::table((new InstitutionReview())->getTable())->truncate();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionGallery())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if the user can delete an institution asset
    */
    public function testIfUserCanDeleteAnInstitutionGalleryAsset(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionReview())->getTable())->truncate();
        DB::table((new InstitutionGallery())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $institution_code = Str::random(10);
        $asset_code = Str::random(10);

        Institution::factory()->count(1)->create([
            'institution_code' => $institution_code,
            'is_featured' => 1
        ]);

        InstitutionGallery::factory()->count(1)->create([
            'asset_code' => $asset_code,
            'institution_code' => $institution_code
        ]);

        $token = file_get_contents(storage_path('test-token.txt'));

        $this->post('institutions/admin/'.$institution_code.'/gallery/asset/'.$asset_code.'/delete',
            [],
            [
                'token' => $token,
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.gallery_item_deleted')
            ]);
        Schema::disableForeignKeyConstraints();
        DB::table((new InstitutionReview())->getTable())->truncate();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionGallery())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if the user can feature an institution asset
    */
    public function testIfUserCanFeatureAnInstitutionGalleryAsset(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionReview())->getTable())->truncate();
        DB::table((new InstitutionGallery())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $institution_code = Str::random(10);

        Institution::factory()->count(1)->create([
            'institution_code' => $institution_code,
            'is_featured' => 1
        ]);

        InstitutionGallery::factory()->count(5)->create([
            'institution_code' => $institution_code
        ]);

        $asset = InstitutionGallery::all()->first();
        $asset_code = $asset->asset_code ?? null;

        $token = file_get_contents(storage_path('test-token.txt'));

        $this->post('institutions/admin/'.$institution_code.'/gallery/asset/'.$asset_code.'/feature',
            [],
            [
                'token' => $token,
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.gallery_item_featured')
            ]);

        $featured_assets = InstitutionGallery::where('is_featured', 1)->count();

        $this->assertTrue($featured_assets == 1);

        Schema::disableForeignKeyConstraints();
        DB::table((new InstitutionReview())->getTable())->truncate();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionGallery())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
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
