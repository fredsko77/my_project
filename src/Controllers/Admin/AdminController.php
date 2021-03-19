<?php
namespace App\Controllers\Admin;

use App\Entity\Users;
use App\Services\AbstractController;
use App\Services\Auth;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * [class AdminController]
 */
class AdminController extends AbstractController
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

        if (Auth::getAuth() instanceof Users) {

            return $response->withStatus(308)->withHeader('Location', '/admin/projets');
        }
        return $response->withStatus(308)->withHeader('Location', '/connexion');
    }
}
