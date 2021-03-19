<?php
namespace App\Controllers\Admin;

use App\Services\AbstractController;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * [Class HomeController]
 */
class AdminContactController extends AbstractController
{
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Route = /inscription
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function index(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'admin/contacts/index.twig');
    }
}
