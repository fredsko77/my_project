<?php
namespace App\Controllers\Admin;

use App\Entity\Projects;
use App\Models\ProjectsModel;
use App\Services\AbstractController;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * [Class HomeController]
 */
class AdminProjectController extends AbstractController
{

    /**
     * @var ProjectsModel $projectsModel
     */
    private $projectsModel;

    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->projectsModel = new ProjectsModel;
        $this->technos = Projects::TECHNOS;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function index(Request $request, Response $response)
    {
        $projects = $this->projectsModel->findAll(Projects::class);
        return $this->container->get('view')->render($response, 'admin/projects/index.twig', compact('projects'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    function new (Request $request, Response $response) {
        $action = '/admin/api/projects/new';
        $handler = 'handleProject';
        $technos = $this->technos;
        return $this->container->get('view')->render($response, 'admin/projects/new.twig', compact('action', 'handler', 'technos'));
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function edit(Request $request, Response $response, int $id)
    {
        $action = "/admin/api/projects/{$id}/edit";
        $handler = 'handleProject';
        $project = $this->projectsModel->find($id, Projects::class);
        $technos = $this->technos;
        return $this->container->get('view')->render($response, 'admin/projects/edit.twig', compact('action', 'handler', 'project', 'technos'));
    }

}
