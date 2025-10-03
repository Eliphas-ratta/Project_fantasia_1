<?php

namespace App\Controller;

use App\Form\PasswordResetFormType;
use App\Form\PasswordResetRequestFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;

class PasswordResetController extends AbstractController
{
    #[Route('/password/reset', name: 'app_password_reset')]
    public function request(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        $form = $this->createForm(PasswordResetRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailInput = $form->get('email')->getData();
            $user = $userRepository->findOneBy(['email' => $emailInput]);

            if ($user) {
                $token = Uuid::v4()->toRfc4122();
                $user->setResetToken($token);
                $user->setResetTokenExpiresAt((new \DateTimeImmutable())->modify('+1 hour'));
                $em->flush();

                $resetUrl = $this->generateUrl(
                    'app_password_reset_confirm',
                    ['token' => $token],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                try {
                    $emailMessage = (new Email())
                        ->from('projetfantasia@gmail.com')
                        ->to($user->getEmail())
                        ->subject('Password reset - Project Fantasia')
                        ->html(<<<HTML
                            <div style="font-family: Arial, sans-serif; background-color:#f8f9fa; padding:20px;">
                                <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1); padding:20px;">
                                    
                                    <h2 style="color:#d9534f; text-align:center;">🔒 Password Reset</h2>
                                    
                                    <p>Hello <strong>{$user->getUsername()}</strong>,</p>
                                    
                                    <p>You requested to reset your password for <strong>Project Fantasia</strong>.</p>
                                    
                                    <div style="text-align:center; margin:30px 0;">
                                        <a href="{$resetUrl}" 
                                        style="background:#d9534f; color:#fff; padding:12px 20px; text-decoration:none; border-radius:6px; font-weight:bold; display:inline-block;">
                                            🔑 Reset my password
                                        </a>
                                    </div>
                                    
                                    <p style="font-size:14px; color:#555;">
                                        This link will expire in <strong>1 hour</strong>.  
                                        If you did not request a password reset, you can safely ignore this email.
                                    </p>
                                    
                                    <hr style="margin:20px 0; border:none; border-top:1px solid #eee;">
                                    
                                    <p style="font-size:12px; color:#999; text-align:center;">
                                        © Project Fantasia — Please do not reply to this automated email.
                                    </p>
                                </div>
                            </div>
                        HTML);

                    $mailer->send($emailMessage);
                } catch (\Throwable $e) {
                    // Silent fail: do not reveal if the email exists
                }
            }

            $this->addFlash('success', 'If an account exists, a password reset email has been sent.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('password_reset/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    #[Route('/password/reset/{token}', name: 'app_password_reset_confirm')]
    public function confirm(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response {
        $user = $userRepository->findOneBy(['resetToken' => $token]);

        $now = new \DateTimeImmutable();
        if (!$user || !$user->getResetTokenExpiresAt() || $user->getResetTokenExpiresAt() < $now) {
            $this->addFlash('danger', 'The reset link is invalid or has expired.');
            return $this->redirectToRoute('app_password_reset');
        }

        $form = $this->createForm(PasswordResetFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $newPassword */
            $newPassword = $form->get('newPassword')->getData();

            $user->setPassword($hasher->hashPassword($user, $newPassword));
            $user->setResetToken(null);
            $user->setResetTokenExpiresAt(null);
            $em->flush();

            $this->addFlash('success', 'Your password has been successfully updated.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('password_reset/confirm.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
