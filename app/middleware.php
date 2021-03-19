<?php

declare (strict_types = 1);

use App\MiddleWares\AdminMiddleware;
use App\MiddleWares\AuthorizationMiddleware;
use App\MiddleWares\JsonBodyParserMiddleware;
use App\MiddleWares\SessionMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    $settings = $container->get('settings');

    // Add Global Middelware
    $app->addErrorMiddleware(
        $settings['displayErrorDetails'],
        $settings['logErrors'],
        $settings['logErrorDetails']
    );

    $app->getContainer()->set('ajax', function () {
        return new JsonBodyParserMiddleware();
    });

    $app->getContainer()->set('session', function () {
        return new SessionMiddleware();
    });

    $app->getContainer()->set('admin', function () {
        return new AdminMiddleware();
    });

    $app->getContainer()->set('authorization', function () {
        return new AuthorizationMiddleware();
    });

    $app->add(function (Request $request, RequestHandler $handler) {
        $response = $handler->handle($request);
        if ($response->getStatusCode() === 404) {
            return $response->withStatus(303)->withHeader('Location', '/404');
        }
        return $response;
    });

};
