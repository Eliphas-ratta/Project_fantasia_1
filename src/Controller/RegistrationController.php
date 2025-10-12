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

        // ✅ Image de profil par défaut
        $user->setProfileImage('default.png');

        // Génère un token unique pour vérification e-mail
        $token = Uuid::v4()->toRfc4122();
        $user->setEmailVerificationToken($token);
        $user->setIsVerified(false);

        // Sauvegarde en base
        $entityManager->persist($user);
        $entityManager->flush();

            // Sauvegarde en base
            $entityManager->persist($user);
            $entityManager->flush();

                        // Send verification email
            $verificationUrl = $this->generateUrl(
                'app_verify_email',
                ['token' => $token],
                \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL
            );

            $email = (new Email())
                ->from('projetfantasia@gmail.com')
                ->to($user->getEmail())
                ->subject('Verify your email - Project Fantasia')
                ->html(<<<HTML
                    <div style="font-family: Arial, sans-serif; background-color:#f8f9fa; padding:20px;">
                        <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:8px; 
                                    box-shadow:0 2px 8px rgba(0,0,0,0.1); padding:20px;">
                            
                            <h2 style="color:#d9534f; text-align:center;">✨ Welcome to Project Fantasia!</h2>
                            
                            <p>Hello <strong>{$user->getUsername()}</strong>,</p>
                            
                            <p>Thank you for signing up to <strong>Project Fantasia</strong> 🎉</p>
                            <p>To activate your account and access all features, please confirm your email address:</p>
                            
                            <div style="text-align:center; margin:30px 0;">
                                <a href="{$verificationUrl}" 
                                style="background:#d9534f; color:#fff; padding:12px 20px; text-decoration:none; 
                                        border-radius:6px; font-weight:bold; display:inline-block;">
                                    ✅ Verify my email
                                </a>
                            </div>
                            
                            <p style="font-size:14px; color:#555;">
                                If you did not create this account, you can safely ignore this email.
                            </p>
                            
                            <hr style="margin:20px 0; border:none; border-top:1px solid #eee;">
                            
                            <p style="font-size:12px; color:#999; text-align:center;">
                                © Project Fantasia — Please do not reply to this automated email.
                            </p>
                        </div>
                    </div>
                HTML);

            $mailer->send($email);



            $this->addFlash('success', 'Registration successful! Please check your email to verify your account.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
