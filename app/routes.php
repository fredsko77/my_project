<?php

declare (strict_types = 1);

use App\Controllers\Admin\AdminApiProjectController;
use App\Controllers\Admin\AdminContactController;
use App\Controllers\Admin\AdminController;
use App\Controllers\Admin\AdminProjectController;
use App\Controllers\ApiController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\NotFoundController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    // Define app routes
    $app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
        $name = $args['name'];
        $response->getBody()->write("Hello {$name}");
        return $response;
    });

    $container = $app->getContainer();

    $app->group('', function (RouteCollectorProxy $router) {
        $router->get('/', [HomeController::class, 'home']);
        $router->get('/mon-cv', [HomeController::class, 'resume']);
        $router->get('/404', [NotFoundController::class, 'index']);
    })->add($container->get('viewMiddleware'));

    $app->group('', function (RouteCollectorProxy $router) {
        $router->get('/inscription', [AuthController::class, 'register']);
        $router->get('/connexion', [AuthController::class, 'login']);
    })->add($container->get('viewMiddleware'))->add($container->get('session'));

    $app->group('/admin', function (RouteCollectorProxy $router) {
        $router->get('/contacts', [AdminContactController::class, 'index']);
        $router->get('/projets', [AdminProjectController::class, 'index']);
        $router->get('/projet/nouveau', [AdminProjectController::class, 'new']);
        $router->get('/projet/{id}/edit', [AdminProjectController::class, 'edit']);
    })->add($container->get('viewMiddleware'))->add($container->get('admin'));

    $app->get('/admin', [AdminController::class, 'index']);

    $app->group('/admin/api', function (RouteCollectorProxy $router) {
        $router->post('/projects/new', [AdminApiProjectController::class, 'new']);
        $router->post('/projects/{id}/edit', [AdminApiProjectController::class, 'edit']);
        $router->delete('/projects/{id}/delete', [AdminApiProjectController::class, 'delete']);
    })->add($container->get('ajax'))->add($container->get('authorization'));

    $app->get('/logout', [AuthController::class, 'logout']);

    $app->group('/api', function (RouteCollectorProxy $router) {
        $router->post('/register', [ApiController::class, 'register']);
        $router->post('/login', [ApiController::class, 'login']);
        $router->post('/contact', [ApiController::class, 'api_contact']);
    })->add($container->get('ajax'));

};
