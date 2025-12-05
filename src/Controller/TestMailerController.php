<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class TestMailerController extends AbstractController
{
    #[Route('/test-mail', name: 'test_mail')]
    public function index(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('bricotteaux@alwaysdata.net')
            ->to('katoudevb@gmail.com') // ton email réel
            ->subject('Test SMTP Alwaysdata')
            ->text('Ceci est un test depuis Symfony sur Alwaysdata.');

        try {
            $mailer->send($email);
            return new Response('Email envoyé !');
        } catch (\Exception $e) {
            return new Response('Erreur SMTP : ' . $e->getMessage());
        }
    }
}
