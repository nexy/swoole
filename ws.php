<?php 

use Swoole\Websocket\Server;
use Swoole\Websocket\Frame;
use Swoole\Http\Request;
use Swoole\Http\Response;

$server = new Server('0.0.0.0', 80);
$clients = new Swoole\Table(1024);
$clients->column('id', Swoole\Table::TYPE_INT, 8);
$clients->column('name', Swoole\Table::TYPE_STRING, 70);
$clients->create();
$server->clients = $clients;

$server->on('start', function (Server $server) {
    echo "Websocket server started.\n";
});
$server->on('request', function (Request $request, Response $response) {
    $response->end("<!DOCTYPE html><p>This is a Websocket endpoint. Please upgrade your connection.</p>");
});
$server->on('open', function (Server $server, Request $request) {
    echo "Client #{$request->fd} connected.\n";
    $server->clients->set($request->fd, [
        'id' => $request->fd,
        'name' => 'Client #' . $request->fd,
    ]);
});
$server->on('message', function (Server $server, Frame $frame) {
    $message = $frame->data;
    echo "Received message '{$message}' from client #{$frame->fd}\n";
    $server->push($frame->fd, "I've got your message: '{$message}'");
    foreach ($server->clients as $id => $item) {
        if ($frame->fd != $id) {
            $server->push($id, "Client #{$frame->fd}: {$message}");    
        }
    }
});
$server->on('close', function (Server $server, int $client) {
    echo "Client #{$client} disconnected.\n";
});

$server->start();