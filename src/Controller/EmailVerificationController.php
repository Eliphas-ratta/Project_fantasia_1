<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class EmailVerificationController extends AbstractController
{
    #[Route('/verify/email/{token}', name: 'app_verify_email')]
    public function verifyEmail(string $token, UserRepository $userRepo, EntityManagerInterface $em): RedirectResponse
    {
        dump('Token dans URL:', $token);

        $cleanToken = trim($token);
        $user = $userRepo->findOneBy(['emailVerificationToken' => $cleanToken]);

        dump('User trouvé:', $user);

        if (!$user) {
            $this->addFlash('danger', 'Invalid or expired verification token.');
            return $this->redirectToRoute('app_login');
        }

        $user->setIsVerified(true);
        $user->setEmailVerificationToken(null);
        $em->flush();

        $this->addFlash('success', 'Your email has been successfully verified!');
        return $this->redirectToRoute('app_login');
    }
}
