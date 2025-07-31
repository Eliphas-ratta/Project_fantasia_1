<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Uid\Uuid;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode le mot de passe
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Date d'inscription
            $user->setCreateAt(new \DateTime());

            // Génère un token unique pour vérification e-mail
            $token = Uuid::v4()->toRfc4122();
            $user->setEmailVerificationToken($token);
            $user->setIsVerified(false);

            // Sauvegarde en base
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoie un e-mail de vérification
            $verificationUrl = $this->generateUrl('app_verify_email', ['token' => $token], \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL);

            $email = (new Email())
                ->from('projetfantasia@gmail.com')
                ->to($user->getEmail())
                ->subject('Please verify your email')
                ->html("
                    <p>Welcome to <strong>Project Fantasia</strong>, {$user->getUsername()}!</p>
                    <p>Click the link below to verify your account:</p>
                    <p><a href=\"$verificationUrl\">Verify my email</a></p>
                    <p>If you did not create this account, you can safely ignore this message.</p>
                ");

            $mailer->send($email);

            $this->addFlash('success', 'Registration successful! Please check your email to verify your account.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
