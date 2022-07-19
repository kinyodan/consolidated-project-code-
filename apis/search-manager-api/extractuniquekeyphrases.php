<?php
declare(strict_types=1);

use Swoole\HTTP\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;


$server = new Server("0.0.0.0", 8100);
$server->on("workerStart",
  function (Server $server, int $worker_id) {
    if ($worker_id === 0) {
      $server->tick(1000, function () {
        $db = new Co\MySQL();
        $database = [
          'host' => '127.0.0.1',
          'user' => 'root',
          'password' => '',
          'database' => 'craydel_search_manager'
        ];
        $db->connect($database);
        try {
          
          $phrases = $db->query(sql: "SELECT * FROM extracted_key_phrases");
          foreach ($phrases as $phrase) {
            $phrase_id = $phrase["id"];
            $phrase_content = $phrase["phrases"];
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $phrase_content), '-'));;
            $db->query(sql: "INSERT IGNORE INTO unique_extracted_key_phrases(selected_key_phrases_id, phrases_slug,phrases) VALUES('$phrase_id','$slug','$phrase_content')");
          }
          echo "extracted key_phrases saved.\n";
        } catch (\Throwable $e) {
          echo ".$e";
        }
      });
    }
  }
);
$server->on("request", function (Request $request, Response $response) {
  $response->header("Content-Type", "text/plain");
  $response->end("Hello \n");
});


$server->start();