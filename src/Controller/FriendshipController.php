<?php

namespace App\Controller;

use App\Entity\Friendship;
use App\Entity\User;
use App\Repository\FriendshipRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/friend')]
#[IsGranted('ROLE_USER')]
class FriendshipController extends AbstractController
{
    #[Route('/', name: 'app_friends')]
    public function index(FriendshipRepository $friendshipRepository): Response
    {
        $user = $this->getUser();

        $friends = $friendshipRepository->findFriends($user);
        $pendingReceived = $friendshipRepository->findPendingRequestsReceived($user);
        $pendingSent = $friendshipRepository->findPendingRequestsSent($user);

        return $this->render('friendship/index.html.twig', [
            'friends' => $friends,
            'pendingReceived' => $pendingReceived,
            'pendingSent' => $pendingSent,
        ]);
    }

    #[Route('/add', name: 'app_friend_add', methods: ['POST'])]
    public function add(
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        FriendshipRepository $friendshipRepository
    ): Response {
        $user = $this->getUser();

        // ✅ Vérification CSRF
        if (!$this->isCsrfTokenValid('add_friend', $request->request->get('_token'))) {
            $this->addFlash('danger', 'Invalid CSRF token.');
            return $this->redirectToRoute('app_friends');
        }

        $username = $request->request->get('username');

        if (!$username) {
            $this->addFlash('danger', 'Please enter a username.');
            return $this->redirectToRoute('app_friends');
        }

        $friend = $userRepository->findOneBy(['username' => $username]);

        if (!$friend) {
            $this->addFlash('danger', 'User not found.');
            return $this->redirectToRoute('app_friends');
        }

        if ($friend === $user) {
            $this->addFlash('warning', 'You cannot add yourself.');
            return $this->redirectToRoute('app_friends');
        }

        // ✅ Vérifie relation avec le repo custom
        $existing = $friendshipRepository->findExistingRelation($user, $friend);

        if ($existing) {
            if ($existing->getStatus() === 'accepted') {
                $this->addFlash('info', 'You are already friends with this user.');
            } elseif ($existing->getStatus() === 'pending') {
                $this->addFlash('warning', 'A friend request already exists.');
            }
            return $this->redirectToRoute('app_friends');
        }

        $friendship = new Friendship();
        $friendship->setUser($user);
        $friendship->setFriend($friend);
        $friendship->setStatus('pending');

        $em->persist($friendship);
        $em->flush();

        $this->addFlash('success', 'Friend request sent!');
        return $this->redirectToRoute('app_friends');
    }

    #[Route('/accept/{id}', name: 'app_friend_accept')]
    public function accept(Friendship $friendship, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($friendship->getFriend() !== $user) {
            $this->addFlash('danger', 'You cannot accept this request.');
            return $this->redirectToRoute('app_friends');
        }

        $friendship->setStatus('accepted');
        $em->flush();

        $this->addFlash('success', 'Friend request accepted!');
        return $this->redirectToRoute('app_friends');
    }

    #[Route('/decline/{id}', name: 'app_friend_decline')]
    public function decline(Friendship $friendship, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($friendship->getFriend() !== $user) {
            $this->addFlash('danger', 'You cannot decline this request.');
            return $this->redirectToRoute('app_friends');
        }

        $em->remove($friendship);
        $em->flush();

        $this->addFlash('info', 'Friend request declined.');
        return $this->redirectToRoute('app_friends');
    }

    #[Route('/remove/{id}', name: 'app_friend_remove')]
    public function remove(Friendship $friendship, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($friendship->getUser() !== $user && $friendship->getFriend() !== $user) {
            $this->addFlash('danger', 'You cannot remove this friendship.');
            return $this->redirectToRoute('app_friends');
        }

        $em->remove($friendship);
        $em->flush();

        $this->addFlash('info', 'Friend removed.');
        return $this->redirectToRoute('app_friends');
    }
}
