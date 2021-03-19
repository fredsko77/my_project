<?php

declare (strict_types = 1);

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Twig\Loader\FilesystemLoader;

return function (App $app) {

    $container = $app->getContainer();

    $container->set('view', function () use ($container) {
        $twig = Twig::create('../templates', ['debug' => true, 'cache_enbaled' => false]);
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        return $twig;
    });

    $container->set('viewMiddleware', function () use ($app, $container) {
        return new TwigMiddleware($container->get('view'), $app->getRouteCollector()->getRouteParser());
    });
};
