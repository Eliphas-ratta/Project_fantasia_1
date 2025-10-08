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
use Symfony\Component\Security\Core\Security;

#[Route('/friend')]
#[IsGranted('ROLE_USER')]
class FriendshipController extends AbstractController
{
    #[Route('/', name: 'app_friends')]
    public function index(FriendshipRepository $friendshipRepository): Response
    {
        $user = $this->getUser();

        $friends = $friendshipRepository->findFriendsOfUser($user);
        $pendingReceived = $friendshipRepository->findPendingReceivedByUser($user);
        $pendingSent = $friendshipRepository->findPendingSentByUser($user);

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
        $username = trim($request->request->get('username', ''));

        if (!$username) {
            return $this->handleResponse($request, 'danger', 'Please enter a username.', 400);
        }

        $friend = $userRepository->findOneBy(['username' => $username]);
        if (!$friend) {
            return $this->handleResponse($request, 'danger', 'User not found.', 404);
        }

        if ($friend === $user) {
            return $this->handleResponse($request, 'warning', 'You cannot add yourself.');
        }

        $existing = $friendshipRepository->findExistingRelation($user, $friend);
        if ($existing) {
            $message = $existing->getStatus() === 'accepted'
                ? 'You are already friends with this user.'
                : 'A friend request already exists.';
            return $this->handleResponse($request, 'warning', $message);
        }

        $friendship = new Friendship();
        $friendship->setUser($user);
        $friendship->setFriend($friend);
        $friendship->setStatus('pending');

        $em->persist($friendship);
        $em->flush();

        return $this->handleResponse($request, 'success', 'Friend request sent!');
    }

    #[Route('/accept/{id}', name: 'app_friend_accept', methods: ['GET', 'POST'])]
    public function accept(Friendship $friendship, EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();

        if ($friendship->getFriend() !== $user) {
            return $this->handleResponse($request, 'danger', 'You cannot accept this request.', 403);
        }

        $friendship->setStatus('accepted');
        $em->flush();

        return $this->handleResponse($request, 'success', 'Friend request accepted!');
    }

    #[Route('/decline/{id}', name: 'app_friend_decline', methods: ['GET', 'POST'])]
    public function decline(Friendship $friendship, EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();

        if ($friendship->getFriend() !== $user && $friendship->getUser() !== $user) {
            return $this->handleResponse($request, 'danger', 'You cannot decline this request.', 403);
        }

        $em->remove($friendship);
        $em->flush();

        return $this->handleResponse($request, 'info', 'Friend request declined.');
    }

    #[Route('/remove/{id}', name: 'app_friend_remove', methods: ['GET', 'POST'])]
    public function remove(
        User $user,
        FriendshipRepository $friendshipRepository,
        EntityManagerInterface $em,
        Security $security,
        Request $request
    ): Response {
        $currentUser = $security->getUser();

        // Trouver la relation entre les deux utilisateurs
        $friendship = $friendshipRepository->createQueryBuilder('f')
            ->where('(f.user = :u1 AND f.friend = :u2) OR (f.user = :u2 AND f.friend = :u1)')
            ->setParameter('u1', $currentUser)
            ->setParameter('u2', $user)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$friendship) {
            return $this->handleResponse($request, 'danger', 'Friendship not found.', 404);
        }

        $em->remove($friendship);
        $em->flush();

        return $this->handleResponse($request, 'info', 'Friend removed.');
    }

    /**
     * ✅ Méthode utilitaire pour renvoyer une réponse JSON ou Flash + redirection.
     */
    private function handleResponse(Request $request, string $status, string $message, int $code = 200): Response
    {
        if ($request->isXmlHttpRequest()) {
            return $this->json(['status' => $status, 'message' => $message], $code);
        }

        $this->addFlash($status, $message);
        return $this->redirectToRoute('app_friends');
    }

    /**
     * ✅ Nouveau endpoint pour recharger uniquement la sidebar AJAX.
     */
    #[Route('/sidebar', name: 'app_friend_sidebar', methods: ['GET'])]
    public function sidebar(FriendshipRepository $friendshipRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            // pas connecté = pas de sidebar
            return new Response('', 204);
        }

        $friends = $friendshipRepository->findFriendsOfUser($user);
        $pendingReceived = $friendshipRepository->findPendingReceivedByUser($user);
        $pendingSent = $friendshipRepository->findPendingSentByUser($user);

        return $this->render('friendship/_sidebar.html.twig', [
            'friends' => $friends,
            'pendingReceived' => $pendingReceived,
            'pendingSent' => $pendingSent,
        ]);
    }
}
