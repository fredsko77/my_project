<?php

declare (strict_types = 1);

use DI\Container;
use Monolog\Logger;

return function (Container $container) {
    $container->set('settings', function () {
        return [
            'name' => 'Example Slim Application',
            'displayErrorDetails' => true,
            'logErrorDetails' => true,
            'logErrors' => true,
            'logger' => [
                'name' => 'slim-app',
                'path' => __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
            'view' => [
                'path' => __DIR__ . '/../templates',
                'settings' => ['cache' => false],
            ],
            'db' => [
                'host' => 'localhost',
                'name' => 'slim',
                'user' => 'root',
                'pass' => '',
            ],
        ];
    });
};
