<?php

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

$server = new Swoole\HTTP\Server("0.0.0.0", 8425);

$server->on("start", function (Server $server) {
  echo "Swoole http server is started at http://0.0.0.0:8425\n";
});

$server->on("request", callback: function (Request $request, Response $response) {
  $db = new Co\MySQL();
  $database = [
    'host' => '127.0.0.1',
    'user' => 'root',
    'password' => '',
    'database' => 'craydel_search_manager'
  ];
  $db->connect($database);
  try {
    $search_term = $request->post['search_term'];
<!--     $country = $request->post['country']; -->
<!--     $result = $db->query("SELECT * FROM extracted_key_phrases_index_list WHERE course_name  LIKE  '%" . $search_term . "%' AND country  LIKE  '%" . $country . "%' "); -->
<!--     $discipline = $db->query("SELECT discipline,COUNT(id) as count FROM extracted_key_phrases_index_list WHERE course_name  LIKE  '%" . $search_term . "%' AND country  LIKE  '%" . $country . "%' GROUP BY discipline "); -->
<!--     $attendance_type = $db->query("SELECT attendance_type,COUNT(id) as count FROM extracted_key_phrases_index_list WHERE course_name  LIKE  '%" . $search_term . "%' AND country  LIKE  '%" . $country . "%' GROUP BY attendance_type "); -->
<!--     $course_type = $db->query("SELECT course_type,COUNT(id) as count FROM extracted_key_phrases_index_list WHERE course_name  LIKE  '%" . $search_term . "%' AND country  LIKE  '%" . $country . "%' GROUP BY course_type"); -->
<!--     $graduate_level = $db->query("SELECT graduate_level,COUNT(id) as count FROM extracted_key_phrases_index_list WHERE course_name  LIKE  '%" . $search_term . "%' AND country  LIKE  '%" . $country . "%' GROUP BY graduate_level "); -->
<!--     $learning_mode = $db->query("SELECT learning_mode,COUNT(id) as count FROM extracted_key_phrases_index_list WHERE course_name  LIKE  '%" . $search_term . "%' AND country  LIKE  '%" . $country . "%'  GROUP BY learning_mode "); -->
<!--     $standard_fee_payable_usd = $db->query("SELECT standard_fee_payable_usd,COUNT(id)as count FROM extracted_key_phrases_index_list WHERE course_name  LIKE  '%" . $search_term . "%' AND country  LIKE  '%" . $country . "%' GROUP BY standard_fee_payable_usd"); -->
<!--     $country = $db->query("SELECT country,COUNT(id) as count FROM extracted_key_phrases_index_list WHERE course_name  LIKE  '%" . $search_term . "%' AND country  LIKE  '%" . $country . "%' GROUP BY country "); -->
<!--      -->

    class workerThread extends Thread {
    public function __construct($i){
      $this->i=$i;
    }

    public function run(){
      while(true){
       echo $this->i;
       sleep(1);
      }
    }
    }

    for($i=0;$i<50;$i++){
    $workers[$i]=new workerThread($i);
    $workers[$i]->start();
    }

    $results[] = (object)[
      'hits' => $result,
      'nbHits' => COUNT($result),
      'page' => 0,
      'nbPages' => 1,
      'hitsPerPage' => 10,
      'facets' => (object)[
        'attendance_type' => $attendance_type,
        'country' => $country,
        'course_type' => $course_type,
        'discipline' => $discipline,
        'graduate_level' => $graduate_level,
        'learning_mode' => $learning_mode,
        'standard_fee_payable_usd' => $standard_fee_payable_usd],
      'facets_stats' => (object)[
        'standard_fee_payable_usd' => (object)[
          'min' => 20000,
          'max' => 27804,
          'avg' => 23902,
          'sum' => 47804,
        
        ],
      ],
      'exhaustiveFacetsCount' => '',
      'exhaustiveNbHits' => '',
      'exhaustiveTypo' => '',
      'query' => '',
      'params' => '',
      'renderingContent' => (object)[
      ],
      'processingTimeMS' => 2
    ];
    
    $hits = json_encode($results);
    $response->header("Content-Type", "application/json");
    $response->end($hits);
    
  } catch (\Throwable $e) {
    $response->end($e);
  }
  
});
$server->start();