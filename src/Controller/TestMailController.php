<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class TestMailController extends AbstractController
{
    #[Route('/test-mail', name: 'app_test_mail')]
public function testMail(MailerInterface $mailer, LoggerInterface $logger): Response
{
    // Log la valeur du MAILER_DSN pour débogage
    $logger->info('MAILER_DSN utilisé : ' . ($_ENV['MAILER_DSN'] ?? 'non défini'));

    try {
        $email = (new Email())
            ->from('test@projectfantasia.dev')
            ->to('test@localhost') // important pour MailDev
            ->subject('Test MailDev')
            ->text('Ceci est un test avec MailDev !');

        $mailer->send($email);
        $logger->info('Email envoyé avec succès.');

        return new Response('✅ Email envoyé ! Vérifie ta boîte MailDev.');
    } catch (\Exception $e) {
        $logger->error('Erreur envoi email : ' . $e->getMessage());

        return new Response('❌ Erreur : ' . $e->getMessage());
    }
}
}