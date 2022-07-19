<?php
namespace App\Http\Controllers\Helpers;

use Algolia\AlgoliaSearch\SearchClient;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use Illuminate\Http\Request;

class ManageSearchEngineDataHelper
{
    use CanLog, CanRespond;

    /**
     * @var $search_engine_settings
    */
    protected $search_engine_settings;

    /**
     * @var $replicas
    */
    protected $replicas;

    /**
     * @var string $index_name
    */
    protected $index_name;

    /**
     * @var string $app_id
    */
    protected $app_id;

    /**
     * @var $app_key
    */
    protected $app_key;

    /**
     * @var array $data
    */
    protected $data;

    /**
     * @var array $searchable_attributes
    */
    protected $searchable_attributes;

    /**
     * @var string $object_id
    */
    protected $object_id;

    /**
     * @var SearchClient $search_engine_client
    */
    protected $search_engine_client;

    /**
     * Constructor
     * @param string|null $object_id
     * @param array|null $data
     */
    public function __construct(?string $object_id = null, ?array $data = null)
    {
        $this->search_engine_settings = config('craydle.search_engine.institution.settings');
        $this->index_name = config('craydle.search_engine.institution.index_name', 'craydel_institutions');
        $this->app_id = config('services.search_engine.app_id');
        $this->app_key = config('services.search_engine.api_key');
        $this->data = $data;
        $this->object_id = $object_id;

        $this->search_engine_client = SearchClient::create(
            $this->app_id,
            $this->app_key
        );
    }

    /**
     * Push data into the search engine
     *
     * @return CraydelInternalResponseHelper
    */
    public function push(): CraydelInternalResponseHelper
    {
        try{
            if(!isset($this->search_engine_settings) || empty($this->search_engine_settings)){
                throw new \Exception('Undefined settings provided');
            }

            if(!isset($this->index_name) || empty($this->index_name)){
                throw new \Exception('Undefined index name when pushing data into the search engine.');
            }

            if(!isset($this->app_id) || empty($this->app_id)){
                throw new \Exception('Undefined search engine APP ID.');
            }

            if(!isset($this->app_key) || empty($this->app_key)){
                throw new \Exception('Undefined search engine API key.');
            }

            if(!isset($this->object_id) || empty($this->object_id)){
                throw new \Exception('Undefined object ID when uploading data into the search engine.');
            }

            if(!isset($this->data) || !is_array($this->data) || count($this->data) <= 0){
                throw new \Exception('The data to index is not an array or the array length is zero');
            }

            if(!isset($this->data['number_of_courses']) || empty($this->data['number_of_courses'])){
                throw new \Exception('The institution has no courses.');
            }

            $client = $this->search_engine_client;

            if (array_key_exists('objectID', $this->data)){
                $this->data['objectID'] = $this->object_id;
            }else{
                $this->data['objectID'] = $this->object_id;
            }

            $index = $client->initIndex($this->index_name);
            $index->setSettings($this->search_engine_settings);

            $result = $index->saveObject($this->data);

            if(!$result->valid()){
                throw new \Exception('Error pushing the data into the search engine.');
            }

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                LanguageTranslationHelper::translate('institutions.success.indexed'), [
                    'result' => call_user_func(function () use($result){
                        $result = $result->getBody();

                        return (object)[
                            'object_id' => isset($result[0]['objectIDs'][0]) && !empty($result[0]['objectIDs'][0]) ? $result[0]['objectIDs'][0] : null,
                            'task_id' => isset($result[0]['taskID']) && !empty($result[0]['taskID']) ? $result[0]['taskID'] : null,
                        ];
                    })
                ]
            ));
        }catch (\Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Configure replicas
    */
    public function configureReplica(): CraydelInternalResponseHelper
    {
        try{
            if(!isset($this->replicas) || empty($this->replicas)){
                throw new \Exception('No replicas have been defined');
            }


        }catch (\Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Delete
    */
    public function delete(): CraydelInternalResponseHelper{
        try{
            if(!isset($this->search_engine_settings) || empty($this->search_engine_settings)){
                throw new \Exception('Undefined search engine settings provided');
            }

            if(!isset($this->index_name) || empty($this->index_name)){
                throw new \Exception('Undefined index name when deleting data from the search engine.');
            }

            if(!isset($this->app_id) || empty($this->app_id)){
                throw new \Exception('Undefined search engine APP ID, when deleting data from the search engine.');
            }

            if(!isset($this->app_key) || empty($this->app_key)){
                throw new \Exception('Undefined search engine API key, when deleting data from the search engine');
            }

            if(!isset($this->object_id) || empty($this->object_id)){
                throw new \Exception('Undefined object ID, when deleting data from the search engine.');
            }

            $index = $this->search_engine_client->initIndex($this->index_name);

            $result = $index->deleteObject($this->object_id);

            if(!$result->valid()){
                throw new \Exception('Error while deleting data from the search engine.');
            }

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Deleted',[
                    'result' => [
                        'object_id' => $this->object_id
                    ]
                ]
            ));
        }catch (\Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Search data
     *
     * @param Request $request
     *
     * @return CraydelInternalResponseHelper
     */
    public function search(Request $request): CraydelInternalResponseHelper
    {
        /*try{
            if(!isset($this->search_engine_settings) || empty($this->search_engine_settings)){
                throw new \Exception('Undefined search engine settings provided');
            }

            if(!isset($this->index_name) || empty($this->index_name)){
                throw new \Exception('Undefined index name when deleting data from the search engine.');
            }

            $search_term = $request->get('search_term', '');

            $index = $this->search_engine_client->initIndex($this->index_name);

            $objects = $index->search($search_term, (new BuildSearchEngineQueryHelper($request))->build());

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Listed',[
                    'result' => $objects
                ]
            ));

        }catch (\Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage()
            ));
        }*/
    }

    /**
     * Clear index
    */
    public function clear(){
        try{
            if(!isset($this->index_name) || empty($this->index_name)){
                throw new \Exception('Undefined index name when deleting data from the search engine.');
            }

            $index = $this->search_engine_client->initIndex($this->index_name);

            $index->clearObjects();
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
