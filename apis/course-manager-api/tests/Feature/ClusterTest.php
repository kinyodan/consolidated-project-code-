<?php

namespace Feature;

use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Cluster;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use Illuminate\Support\Facades\Event;
use TestingHelperFunction;

class ClusterTest extends TestCase
{
    use TestingHelperFunction;

    public function testListingClustersRouteExists()
    {
        $response = $this->post('list-clusters');
        $response->assertTrue(true);
    }

    public function testIfUserCanGetListOfClusters()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $cluster = Cluster::factory()->count(2)->create();
        $cluster = $cluster->first();
        $this->post('courses/admin/list-clusters/', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('clusters.success.listed')
        ])->seeInDatabase((new Cluster())->getTable(), [
            'id' => $cluster->id,
            'is_published' => 1,
        ]);

        static::assertCount(
            1,
            DB::table((new Cluster())->getTable())->where('id', $cluster->id)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new Cluster())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    public function testIfUserCanUpdateACluster()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $cluster = Cluster::factory()->count(1)->create();
        $cluster = $cluster->first();
        $this->post('courses/admin/' . $cluster->id . '/update-clusters', ['cluster_name' => 'English','country_id' => $cluster->country_id], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('clusters.success.is_updated')
        ])->seeInDatabase((new Cluster())->getTable(), [
            'id' => $cluster->id,
            'cluster_name' => 'English',
        ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new Cluster())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

    }

    public function testIfUserCanDeleteACluster()
    {

        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $cluster = Cluster::factory()->count(2)->create();
        $cluster = $cluster->first();
        $this->post('courses/admin/' . $cluster->id . '/delete-clusters', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('clusters.success.is_deleted')
        ]);
        static::assertCount(
            0,
            DB::table((new Cluster())->getTable())->where('id', $cluster->id)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new Cluster())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

    }


}
