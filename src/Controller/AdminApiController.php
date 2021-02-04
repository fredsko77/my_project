<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContactRepository;
use App\Entity\Contact;
use App\Services\Helpers;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminApiController extends AbstractController
{
    
    /**
     * @var ContactRepository $contactRepository
     */
    private $contactRepository;

    /**
     * @var EntityManagerInterface $manager
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager, ContactRepository $contactRepository) 
    {
        $this->manager = $manager;
        $this->contactRepository = $contactRepository;
        $this->helpers = new Helpers;
    }

    /**
     * @Route("/admin/api/get_contact/{id}", 
     * name="admin_api_get_contact",
     * requirements={"id"="\d+"},
     * methods={"GET"}
     * )
     */
    public function get_contact(int $id)
    {
        $contact = $this->contactRepository->find($id);

        if ($contact instanceof Contact) {
            if ($contact->getStatus() === "pending") {
                $contact->setStatus("read");

                $this->manager->persist($contact);
                $this->manager->flush();
            }
        }

        return $this->helpers->jsonResponse([
            'contact' => $this->helpers->transformKeys($contact, Contact::class),
        ], Response::HTTP_OK);
    }
}
