<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    /**
     * @var ContactRepository $contactRepository
     */
    private $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("/dashboard/contacts", name="admin_contacts")
     */
    public function contacts()
    {
        return $this->render("dashboard/contacts/index.html.twig", [
            "contacts" => $this->contactRepository->findAll()
        ]);
    }

    /**
     * @Route(
     * "/dashboard/contacts/reply/{id}", 
     * name="admin_contact_reply",
     * requirements={"id"="\d+"},
     * methods={"GET"}
     * )
     */
    public function reply($id)
    {
        return $this->render("dashboard/contacts/edit.html.twig", [
            "contact" => $this->contactRepository->find($id),
        ]);
    }

}
