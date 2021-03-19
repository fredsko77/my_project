<?php
namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * [class NotFoundController]
 */
class NotFoundController
{
    public function __construct(ContainerInterface $containerInterface)
    {
        $this->container = $containerInterface;
    }

    public function index(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'errors/404.twig');
    }

}
