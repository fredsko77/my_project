<?php

namespace App\Controller;

use App\Services\Helpers;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    public function __construct()
    {
        $this->helpers = new Helpers;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        dd($this->helpers->generateToken(30));
        return $this->render('home/index.html.twig');
    }
}
