<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{

    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    public function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(
     *      "/api_contact", 
     *      name="api_contact",
     *      methods={"POST"}
     * )
     */
    public function api_contact(Request $request, MailerInterface $mailer)
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
}
