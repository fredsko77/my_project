<?php

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