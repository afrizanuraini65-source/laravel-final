<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function testRoute($uri) {
    global $app;
    $request = Illuminate\Http\Request::create($uri, 'GET');
    $response = $app->handle($request);
    echo $uri . ' -> STATUS: ' . $response->getStatusCode() . PHP_EOL;
}

testRoute('/feature/Peserta');
testRoute('/feature/Peserta/1');
testRoute('/feature/Event');
testRoute('/feature/Manajemen Event');
