<?php
namespace App\MiddleWares;

use App\Entity\Users;
use App\Services\Auth;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class SessionMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);

        if (Auth::getAuth() instanceof Users) {
            $response = new Response();

            return $response->withStatus(308)->withHeader('location', '/admin/projets');
        }

        return $response;

    }
}
