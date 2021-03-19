<?php
namespace App\Controllers;

use App\Entity\Contacts;
use App\Helpers\Helpers;
use App\Models\ContactsModel;
use App\Models\UsersModel;
use App\Services\AbstractController;
use App\Services\Auth;
use App\Services\Mailer;
use App\Services\UsersServices;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * [Class HomeController]
 */
class ApiController extends AbstractController
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->userModel = new UsersModel();
        $this->contactModel = new ContactsModel();
        $this->mailer = new Mailer;
    }

    public function register(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        if (is_array(UsersServices::validate($data))) {

            return $this->json([
                'errors' => UsersServices::validate($data),
            ], $response, 200);
        }
        if (UsersServices::validate($data)) {
            if ($this->userModel->mailExists($data['email'])) {

                return $this->json([
                    'errors' => ['email' => 'Cette adresse email existe déjà !'],
                ], $response);
            }

            $data['password'] = encrypt_password($data['password']);
            $data['token'] = Helpers::generateToken(80);
            $data['created_at'] = Helpers::now();
            $this->userModel->insert($data);

            return $this->json([
                'message' => $this->setJsonMessage('Votre compte a bien été enregistré', 'success'),
                'url' => '/connexion',
            ], $response, 201);

        }

        return $this->json([
            'message' => $this->setJsonMessage('Votre requête n\'a pas pu être traitée! ', 'danger'),
        ], $response, 500);

    }

    public function login(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        $token = Helpers::generateToken(80);

        $data['token'] = $token;

        $result = Auth::checkCredentials($data);

        if (is_object($result) && $result->response === "OK") {

            $user = $this->userModel->update(['token' => $data['token']], ['id' => $result->user->getId()], true);

            return $this->json([
                'message' => $this->setJsonMessage("Bonjour {$user->getUsername()} ✋", 'success'),
                'url' => '/admin/projets',
                'token' => $user->getToken(),
            ], $response);
        }

        return $this->json([
            'errors' => $result,
        ], $response, 200);

    }

    public function api_contact(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        if (Helpers::checkFieldsSet($data)) {
            $data['created_at'] = Helpers::now();

            $contact = new Contacts($data);

            $this->contactModel->insert($data);

            $this->mailer->sendContact($contact);
            $this->mailer->alertMe();

            return $this->json([
                'message' => $this->setJsonMessage('Votre demande a bien été pris en compte !', 'success'),
            ], $response, 202);
        }

        return $this->json([
            'message' => $this->setJsonMessage('Votre requête n\'a pas pu être traitée! ', 'danger'),
        ], $response, 500);
    }

}
