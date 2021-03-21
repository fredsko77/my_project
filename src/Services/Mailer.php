<?php
namespace App\Services;

use App\Entity\Contacts;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Mailer
{
    private $smtp = 'smtp.gmail.com';
    private $port = 587;
    private $username = '';
    private $password = '';

    public function __construct()
    {
        $this->mailer = new Swift_Mailer($this->getTransport());
    }

    public function getTransport()
    {
        return (new Swift_SmtpTransport($this->smtp, $this->port, 'tls'))
            ->setUsername($this->username)
            ->setPassword($this->password);
    }

    public function sendContact(Contacts $contact)
    {
        $content = "
            <p>Bonjour {$contact->getName()}, </p>
            <p>Votre demande a bien été pris en compte. </p>
            <p>Je vous répondrez dans les plus brefs délais</p>
            <p>Cordialement. </p>
            <p>Frédérick AGATHE </p>
            <i>
                <p>Récapitulatif de votre demande:</p>
                <p> - Nom : {$contact->getName()} </p>
                <p> - Email : {$contact->getEmail()} </p>
                <p> - Objet : {$contact->getAbout()} </p>
                <p> - Message : {$contact->getMessage()} </p>
            </i>
        ";

        $message = (new Swift_Message())
            ->setFrom($this->username)
            ->setTo($contact->getEmail())
            ->setBody($content)
        ;

        return $this->mailer->send($message);
    }

    public function alertMe()
    {
        $content = "
            <p>Vous avez eu un nouveau contact</p>
        ";

        $message = (new Swift_Message())
            ->setFrom($this->username)
            ->setTo('fagathe77@gmail.com')
            ->setBody($content)
        ;
        $message->setContentType("text/html");

        return $this->mailer->send($message);
    }
}
