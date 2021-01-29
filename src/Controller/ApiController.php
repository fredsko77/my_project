<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Services\Helpers;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;
    
    /**
     * @var ContactRepository $contactRepository
     */
    private $contactRepository;

    public function __construct( EntityManagerInterface $entityManager, ContactRepository $contactRepository)
    {
        $this->entityManager = $entityManager;
        $this->contactRepository = $contactRepository;
        $this->helpers = new Helpers;
    }

    /**
     * @Route(
     *      "/api_contact", 
     *      name="api_contact",
     *      methods={"POST"}
     * )
     */
    public function api_contact(Request $request, MailerInterface $mailer) :JsonResponse
    {
        $data = json_decode( $request->getContent(), true);

        $response = new JsonResponse();
        $response->setData(['message' => [
                'type'=> 'success',
                'content' => 'Votre demande a bien été pris en compte 👍', 
            ]
        ]);
        $response->setStatusCode(Response::HTTP_ACCEPTED);

        $contact = new Contact();
        $contact->_hydrate($data);
        $contact->setStatus("pending");

        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        $email = (new TemplatedEmail())
            ->from('contact@agathefrederick.fr')
            ->to($contact->getEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Votre demande de contact')
            ->htmlTemplate('emails/contact.html.twig')
            ->context([
                'contact' => $contact,
                'website' => $request->getHost(),
            ])
        ;
        $mailer->send($email);

        return $response;
    }
    
    /**
     * @Route(
     *      "/api/cotacts/delete/{id}", 
     *      name="api_contact_delete",
     *      requirements={"id"="\d+"},
     *      methods={"DELETE"}
     * )
     */
    public function api_contact_delete(int $id)
    {
        $contact = $this->contactRepository->find($id);
        
        if ($contact instanceof Contact) {
            $this->entityManager->remove($contact);
            $this->entityManager->flush();

            return $this->helpers->jsonResponse([
                'message' => $this->helpers->setJsonMessage([
                    'content' => 'Le contact a bien été supprimé',
                    'type' => 'success',
                ])
            ]);
        }

        return $this->helpers->jsonResponse([
            'message' => $this->helpers->setJsonMessage([
                'content' => 'Une erreur est survenue',
            ])
        ], Response::HTTP_NOT_FOUND);
    }

}
