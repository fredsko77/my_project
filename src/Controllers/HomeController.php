<?php
namespace App\Controllers;

use App\Models\ProjectsModel;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Stream;

/**
 * [Class HomeController]
 */
class HomeController
{
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->projectModel = new ProjectsModel;
    }

    public function index(Request $request, Response $response, $name)
    {
        return $this->container->get('view')->render($response, 'example.twig', compact('name'));
    }

    public function home(Request $request, Response $response)
    {
        $projects = $this->projectModel->completed();
        return $this->container->get('view')->render($response, 'home/home.twig', compact('projects'));
    }

    public function resume(Request $request, Response $response): Response
    {
        $file = './pdf/CV_AGATHE_Frederick.pdf';
        $fh = fopen($file, 'rb');

        $file_stream = new Stream($fh);
        return $response->withHeader('Cache-Control', 'private')
            ->withHeader('Content-Type', mime_content_type($file))
            ->withHeader('Content-Length', filesize($file))
            ->withHeader('Content-Disposition', 'attachment; filename=' . basename($file))
            ->withHeader('Accept-Ranges', filesize($file))
            ->withHeader('Expires', '0')
            ->withBody($file_stream);
    }

}
