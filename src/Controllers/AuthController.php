<?php
namespace App\Controllers;

use App\Services\AbstractController;
use App\Services\Auth;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * [Class HomeController]
 */
class AuthController extends AbstractController
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
    public function register(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'auth/register.twig');
    }

    /**
     * Route = /inscription
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function login(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'auth/login.twig');
    }

    public function logout(Request $request, Response $response)
    {
        Auth::logout();
        return $response->withStatus(302)->withHeader('location', '/');
    }

}
