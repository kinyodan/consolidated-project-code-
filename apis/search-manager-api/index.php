<?php
use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

<<<<<<< HEAD
<<<<<<< HEAD
$server = new Swoole\HTTP\Server("0.0.0.0", 9501);

$server->on("start", function (Server $server) {
  echo "Swoole http server is started at http://0.0.0.0:9501\n";
=======
$server = new Swoole\HTTP\Server("127.0.0.1", 9501);

$server->on("start", function (Server $server) {
  echo "Swoole http server is started at http://127.0.0.1:9501\n";
>>>>>>> 7bc9789... KeyPhrases Extractor Merge
=======
$server = new Swoole\HTTP\Server("0.0.0.0", 9501);

$server->on("start", function (Server $server) {
  echo "Swoole http server is started at http://0.0.0.0:9501\n";
>>>>>>> a487bfd... Included the Autocomplete End-point
});

$server->on("request", function (Request $request, Response $response) {
  $response->header("Content-Type", "text/plain");
  $response->end("Hello \n");
});
$server->on("message", function (Request $request, Response $response) {
  $response->header("Content-Type", "text/plain");
  $response->end("Hello Test\n");
});
$server->start();