<?php

namespace App\Http\Controllers\Helpers;

use Algolia\AlgoliaSearch\SearchClient;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ManageInternalSearchEngineDataHelper
{
  use CanLog, CanRespond;
  
  
  /**
   * @var array|null $data
   */
  protected ?array $data;
  
  /**
   * @var array $searchable_attributes
   */
  protected array $searchable_attributes;
  
  /**
   * @var SearchClient $search_engine_client
   */
  protected SearchClient $search_engine_client;
  
  /**
   * Constructor
   * @param array|null $data
   */
  public function __construct(?array $data = null)
  {
    $this->data = $data;
  }
  
  /**
   * Push data into the search engine
   *
   * @return CraydelInternalResponseHelper
   */
  public function push(): CraydelInternalResponseHelper
  {
    
    try {
      
      if (!isset($this->data) || !is_array($this->data) || count($this->data) <= 0) {
        throw new Exception('The data to index is not an array or the array length is zero');
      }
      $search_engine_indexing_url = config('services.craydel_services.search_engine.search_engine_indexing_url');
      Log::info($search_engine_indexing_url);
      $data = $this->data;
      $data = json_encode($data);
      $result = Http::asForm()->post($search_engine_indexing_url, [
        'data' => $data,
      ]);
      if ($result->status != 200) {
        throw new Exception('Error pushing the data into the search engine.');
      }
      $result = json_decode($result);
      return $this->internalResponse(new CraydelInternalResponseHelper(
        true,
        LanguageTranslationHelper::translate('courses.success.course_indexed'),
        call_user_func(function () use ($result) {
          return (object)[
            'object_ids' => isset($result[0]['objectID']) && !empty($result[0]['objectID']) ? $result[0]['objectID'] : null,
            'task_id' => isset($result[0]['taskID']) && !empty($result[0]['taskID']) ? $result[0]['taskID'] : null,
          ];
        })
      ));
    } catch (Exception $exception) {
      $this->logException($exception);
      
      return $this->internalResponse(new CraydelInternalResponseHelper(
        false,
        $exception->getMessage()
      ));
    }
  }
  
  /**
   * Delete courses from index
   */
  public function delete(?string $objectID = null): CraydelInternalResponseHelper
  {
    try {
      if (empty($this->search_engine_settings)) {
        throw new Exception('Undefined search engine settings provided');
      }
      
      if (empty($this->index_name)) {
        throw new Exception('Undefined index name when deleting data from the search engine.');
      }
      
      if (empty($this->app_id)) {
        throw new Exception('Undefined search engine APP ID, when deleting data from the search engine.');
      }
      
      if (empty($this->app_key)) {
        throw new Exception('Undefined search engine API key, when deleting data from the search engine');
      }
      
      $index = $this->search_engine_client->initIndex($this->index_name);
      
      if (is_null($objectID)) {
        $object_ids = collect($this->data)->map(function ($object) {
          return [
            'objectID' => $object['objectID'] ?? null
          ];
        })->reject(function ($object) {
          return !isset($object['objectID']);
        })->reject(function ($object) {
          return is_null($object['objectID']);
        })->flatten(1);
        
        $result = $index->deleteObjects(
          $object_ids->toArray()
        );
        
        self::logMessage("Check Things FROM algolia : " . print_r($result, true));
        
        if (!$result->valid()) {
          throw new Exception('Error while deleting data from the search engine.');
        }
        
        return $this->internalResponse(new CraydelInternalResponseHelper(
          true,
          'Deleted',
          call_user_func(function () use ($result) {
            $result = $result->getBody();
            return (object)[
              'object_ids' => isset($result[0]['objectIDs']) && !empty($result[0]['objectIDs']) ? $result[0]['objectIDs'] : null,
              'task_id' => isset($result[0]['taskID']) && !empty($result[0]['taskID']) ? $result[0]['taskID'] : null,
            ];
          })
        ));
      } else {
        $result = $index->deleteObject($objectID);
        
        if (!$result->valid()) {
          throw new Exception('Error while deleting data from the search engine.');
        }
        
        self::logMessage("Deleted item from index : " . print_r($result, true));
        
        return $this->internalResponse(new CraydelInternalResponseHelper(
          true,
          'Deleted', [
            'object_ids' => $objectID
          ]
        ));
      }
    } catch (Exception $exception) {
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
    try {
      if (empty($this->search_engine_settings)) {
        throw new Exception('Undefined search engine settings provided');
      }
      
      if (empty($this->index_name)) {
        throw new Exception('Undefined index name when deleting data from the search engine.');
      }
      
      $search_term = $request->get('search_term', '');
      
      $index = $this->search_engine_client->initIndex($this->index_name);
      
      $objects = $index->search($search_term, (new BuildSearchEngineQueryHelper($request))->build());
      
      return $this->internalResponse(new CraydelInternalResponseHelper(
        true,
        'Listed', [
          'result' => $objects
        ]
      ));
      
    } catch (Exception $exception) {
      $this->logException($exception);
      
      return $this->internalResponse(new CraydelInternalResponseHelper(
        false,
        $exception->getMessage()
      ));
    }
  }
  
  /**
   * Clear index
   */
  public function clear()
  {
    try {
      if (empty($this->index_name)) {
        throw new Exception('Undefined index name when deleting data from the search engine.');
      }
      
      $index = $this->search_engine_client->initIndex($this->index_name);
      
      $index->clearObjects();
    } catch (Exception $exception) {
      $this->logException($exception);
    }
  }
  
  /**
   * Get similar results
   *
   * @param string|null $similar_query
   * @param string|null $filters
   * @param int|null $page_limit
   * @param array|null $retry_options
   * @return CraydelInternalResponseHelper
   */
  public function similar(?string $similar_query, ?string $filters, ?int $page_limit = 10, ?array $retry_options = null): CraydelInternalResponseHelper
  {
    try {
      if (empty($this->index_name)) {
        throw new Exception('Undefined index name when getting similar results.');
      }
      
      if (empty($similar_query)) {
        throw new Exception('Undefined similar query when getting similar results.');
      }
      
      $index = $this->search_engine_client
        ->initIndex($this->index_name);
      
      if (!is_null($filters)) {
        $results = $index->search('', [
          'similarQuery' => $similar_query,
          'filters' => $filters,
          'hitsPerPage' => intval($page_limit),
          'removeStopWords' => true
        ]);
      } else {
        $results = $index->search('', [
          'similarQuery' => $similar_query,
          'filters' => 'course_type:Examination OR course_type:Postgraduate OR course_type:Undergraduate',
          'hitsPerPage' => intval($page_limit),
          'removeStopWords' => true
        ]);
      }
      
      $hits = $results->data['hits'] ?? null;
      
      if ($hits == null) {
        $results = $index->search('', $retry_options);
      }
      
      return $this->internalResponse(new CraydelInternalResponseHelper(
        true,
        'listed',
        $results
      ));
    } catch (Exception $exception) {
      $this->logException($exception);
      
      return $this->internalResponse(new CraydelInternalResponseHelper(
        false,
        $exception->getMessage(),
        null,
        $exception
      ));
    }
  }
  
  /**
   * Make filters
   *
   * @param array $filters
   * @param string $operator
   * @return string|null
   */
  public function makeFilters(array $filters, string $operator = "AND"): ?string
  {
    try {
      if (count($filters) <= 0) {
        return null;
      }
      
      $_filter = "";
      
      foreach ($filters as $key => $val) {
        if (!empty($key) && !empty($val)) {
          if (!is_numeric($val)) {
            $_filter .= ' ' . $key . ':"' . $val . '" ' . $operator;
          } else {
            $_filter .= ' ' . $key . ':' . floatval($val) . ' ' . $operator;
          }
        }
      }
      
      return trim(rtrim($_filter, $operator));
    } catch (Exception $exception) {
      $this->logException($exception);
      return null;
    }
  }
}
