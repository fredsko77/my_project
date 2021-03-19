<?php

<<<<<<< HEAD
use App\Kernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/config/bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
=======
declare (strict_types = 1);

/**
 * Start Session
 */
session_start();

use DI\Bridge\Slim\Bridge as SlimAppFactory;
use DI\Container;

require __DIR__ . '../../vendor/autoload.php';
require __DIR__ . '../../app/functions.php';

$container = new Container();

// App settings
$settings = require __DIR__ . '/../app/settings.php';
$settings($container);

// App logger
$logger = require __DIR__ . '/../app/logger.php';
$logger($container);

// Create App
$app = SlimAppFactory::create($container);

// Views
$views = require __DIR__ . '/../app/views.php';
$views($app);

$app->addBodyParsingMiddleware();

// Use middleware
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

// Use routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

// Run app
$app->run();
>>>>>>> a7dcd2d9eb1b764cabc37232f5ca1c9156d4d917
