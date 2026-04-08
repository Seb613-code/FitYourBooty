<?php

// Correction de PATH_INFO pour les serveurs CGI mal configurés
if (php_sapi_name() === 'cgi-fcgi' && empty($_SERVER['PATH_INFO']) && isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['PATH_INFO'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

/** @var \Illuminate\Contracts\Http\Kernel $kernel */
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
