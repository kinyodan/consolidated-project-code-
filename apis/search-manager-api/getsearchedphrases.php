<?php

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

$server = new Swoole\HTTP\Server("0.0.0.0", 8500);

$server->on("start", function (Server $server) {
  echo "Swoole http server is started at http://0.0.0.0:8500\n";
});

$server->on("request", callback: function (Request $request, Response $response) {
  $db = new Co\MySQL();
  $database = [
    'host' => 'localhost',
    'user' => 'craydel_search_manager',
    'password' => 'Flyby@089701',
    'database' => 'craydel_search_manager'
  ];
  $db->connect($database);
  try {
    $search_term = $request->post['search_term'];
    $query = "SELECT extracted_key_phrases.course_code,extracted_key_phrases.phrases FROM extracted_key_phrases INNER JOIN unique_extracted_key_phrases ON unique_extracted_key_phrases.selected_key_phrases_id = extracted_key_phrases.id WHERE unique_extracted_key_phrases.phrases LIKE  '%" . $search_term . "%'";
    $result = $db->query($query);
    $result = json_encode($result);
    $response->header("Content-Type", "application/json");
    $response->end($result);
    
  } catch (\Throwable $e) {
    $response->end($e);
  }
  
});
$server->start();