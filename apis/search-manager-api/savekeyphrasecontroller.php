<?php
use Swoole\HTTP\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
$server = new Swoole\HTTP\Server("0.0.0.0", 8000);
$user = 'World';
$server->on("start", function (Swoole\Http\Server $server) {
  echo "Swoole http server is started at http://0.0.0.0:8000\n";
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
    $course_code = $request->post['course_code'];
    $phrases = $request->post['phrases'];
    if (!empty($course_code && $phrases)) {
      $result = $db->query(sql: "INSERT INTO extracted_key_phrases(course_code, phrases) VALUES('$course_code','$phrases')");
      $response->header("Content-Type", "application/json");
      $response->end('Records Inserted Successful');
    }
    $response->header("Content-Type", "application/json");
    $response->end("course_code/phrases can't be empty");
  } catch (\Throwable $e) {
    $response->end($e);
  }
  
});
$server->start();
