<?php
namespace App\Services;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * [class AbstractModel]
 */
abstract class AbstractController
{

    public function __construct()
    {
    }

    /**
     * @param string $url
     *
     * @return [type]
     */
    public function redirectTo(string $url)
    {
        return header("location: {$url}");
    }

    /**
     * Redirect to route
     * @param string $route
     * @return void
     */
    public function redirect(string $route = "/")
    {
        return header("Location: {$route}");
    }

    /**
     * Send an Ajax response
     * @param array $data
     * @param integer $code
     * @return Response
     */
    public function json(array $data, Response $response, int $code = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($code);
    }

    /**
     * Return json Message
     * @param string $type
     * @param string $message
     * @return array
     */
    public function setJsonMessage(string $message, string $type = 'danger'): array
    {
        return ['type' => $type, 'content' => $message];
    }
}
