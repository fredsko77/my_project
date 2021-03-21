<?php
<<<<<<< HEAD
<<<<<<< HEAD

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}
=======
require __DIR__ . '/../vendor/autoload.php';

>>>>>>> a7dcd2d9eb1b764cabc37232f5ca1c9156d4d917
=======
require __DIR__ . '/../vendor/autoload.php';

>>>>>>> a7dcd2d9eb1b764cabc37232f5ca1c9156d4d917
