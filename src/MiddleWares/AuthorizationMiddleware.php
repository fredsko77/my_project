<?php
namespace App\MiddleWares;

use App\Entity\Users;
use App\Services\AbstractController;
use App\Services\Auth;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AuthorizationMiddleware extends AbstractController
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
        $authorization = $request->getHeaderLine('Authorization');
        $user = Auth::getAuth();

        if (!array_key_exists('Authorization', $request->getHeaders())) {
            $response = new Response();

            return $this->json([
                'Authorization' => $this->setJsonMessage('Vous n\'avez pas d\'autorisation pour effectuer cette requête !'),
            ], $response, 403);
        }

        if ($authorization) {
            $response = new Response();
            if ($user instanceof Users && $user->getToken() !== $authorization) {

                return $this->json([
                    'Authorization' => $this->setJsonMessage('Vous autorisation n\'est pas valide !'),
                ], $response, 403);
            }

        }

        $response = $handler->handle($request);
        return $response;

    }
}
