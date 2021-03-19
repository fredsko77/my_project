<?php
namespace App\Controllers\Admin;

use App\Entity\Projects;
use App\Models\ProjectsModel;
use App\Services\AbstractController;
use App\Services\Uploads;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * [class HomeController]
 */
class AdminApiProjectController extends AbstractController
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @var $projectModel
     */
    private $projectModel;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->projectModel = new ProjectsModel();
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response $response
     */
    function new (Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $image = $request->getUploadedFiles();
        $uploads = null;
        $errors = [];

        if (array_key_exists('techno', $data)) {
            $data['techno'] = serialize($data['techno']);
        }
        if (array_key_exists('tasks', $data)) {
            $data['tasks'] = serialize($data['tasks']);
        }
        if ($image['image']->getFilePath() !== "") {
            $uploads = (new Uploads($image['image']))->upload();
        }
        if (is_array($uploads)) {
            $errors['image'] = $uploads;
        }
        if (is_string($uploads)) {
            $data['image'] = $uploads;
        }
        if (count($errors) > 0) {

            return $this->json([
                'errors' => $errors,
            ], $response, 500);
        }
        if ($this->projectModel->insert($data)) {

            return $this->json([
                'message' => $this->setJsonMessage('Le projet a bien été enregistré !', 'success'),
                'url' => "/admin/projets",
            ], $response, 202);
        }

        return $this->json([
            'message' => $this->setJsonMessage('Votre requête n\'a pas pu être traitée! ', 'danger'),
        ], $response, 500);

    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response $response
     */
    public function edit(Request $request, Response $response, int $id): Response
    {
        $data = $request->getParsedBody();
        $image = $request->getUploadedFiles();
        $uploads = null;
        $errors = [];

        $project = $this->projectModel->find($id, Projects::class);

        if ($project instanceof Projects) {
            if (array_key_exists('techno', $data)) {
                $data['techno'] = serialize($data['techno']);
            }
            if (array_key_exists('tasks', $data)) {
                $data['tasks'] = serialize($data['tasks']);
            }
            if ($image['image']->getFilePath() !== "") {
                $uploads = (new Uploads($image['image']))->upload();
            }
            if (is_array($uploads)) {
                $errors['image'] = $uploads;
            }
            if (is_string($uploads)) {
                $data['image'] = $uploads;
                unlink($project->getImage());
            }
            if (count($errors) > 0) {

                return $this->json([
                    'errors' => $errors,
                ], $response, 500);
            }
            if ($this->projectModel->update($data, ['id' => $id])) {

                return $this->json([
                    'message' => $this->setJsonMessage('Le projet a bien été modifié !', 'success'),
                ], $response, 202);
            }
        }

        return $this->json([
            'message' => $this->setJsonMessage('Votre requête n\'a pas pu être traitée! ', 'danger'),
        ], $response, 500);

    }

    public function delete(Request $request, Response $response, int $id): Response
    {
        if ($this->projectModel->delete($id) === true) {

            return $this->json([
                'message' => $this->setJsonMessage('Ce projet a bien été modifié', 'success'),
                'deleted' => $id,
            ], $response);
        }

        return $this->json([
            'message' => $this->setJsonMessage('Votre requête n\'a pas pu être traitée! ', 'danger'),
        ], $response, 500);
    }

}
