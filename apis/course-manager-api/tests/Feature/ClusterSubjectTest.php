<?php

namespace Feature;

use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\ClusterSubject;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use TestCase;
use Illuminate\Support\Facades\Event;
use TestingHelperFunction;

class ClusterSubjectTest extends TestCase
{
    use TestingHelperFunction;

    public function testListingClustersSubjectsRouteExists()
    {
        $response = $this->post('list-clusters-subject');
        $response->assertTrue(true);
    }

    public function testIfUserCanCreateAClusterSubject()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $cluster = ClusterSubject::factory()->count(2)->create();
        $cluster = $cluster->first();
        $this->post('courses/admin/list-clusters-subject/', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('clusters.success.listed')
        ])->seeInDatabase((new ClusterSubject())->getTable(), [
            'id' => $cluster->id,
            'is_published' => 1,
        ]);

        static::assertCount(
            1,
            DB::table((new ClusterSubject())->getTable())->where('id', $cluster->id)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new ClusterSubject())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * @throws Exception
     */
    public function testIfUserCanUpdateAClusterSubject()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $cluster = ClusterSubject::factory()->count(1)->create();
        $cluster = $cluster->first();
        $this->post('courses/admin/' . $cluster->id . '/update-cluster-subject',
            ['cluster_id' => $cluster->cluster_id, 'subject_id' => $cluster->subject_id, 'education_type_id' => $cluster->education_type_id,
                'country_id' => $cluster->country_id, 'is_primary' => 1, 'career_pathway_id' => $cluster->career_pathway_id, 'course_code' => $cluster->course_code], [
                'token' => $token,
                'locale' => 'en'
            ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('clusters.success.is_updated')
        ])->seeInDatabase((new ClusterSubject())->getTable(), [
            'id' => $cluster->id,
            'is_primary' => 1
        ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new ClusterSubject())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

    }


    public function testIfUserCanDeleteACluster()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $cluster = ClusterSubject::factory()->count(2)->create();
        $cluster = $cluster->first();
        $this->post('courses/admin/' . $cluster->id . '/delete-cluster-subject', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('clusters.success.is_deleted')
        ]);
        static::assertCount(
            0,
            DB::table((new ClusterSubject())->getTable())->where('id', $cluster->id)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new ClusterSubject())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

}
