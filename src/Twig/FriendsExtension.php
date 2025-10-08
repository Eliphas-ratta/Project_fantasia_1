<?php

namespace App\Twig;

use App\Repository\FriendshipRepository;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FriendsExtension extends AbstractExtension
{
    private FriendshipRepository $friendshipRepository;
    private Security $security;

    public function __construct(FriendshipRepository $friendshipRepository, Security $security)
    {
        $this->friendshipRepository = $friendshipRepository;
        $this->security = $security;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_friends', [$this, 'getFriends']),
            new TwigFunction('get_pending_received', [$this, 'getPendingReceived']),
            new TwigFunction('get_pending_sent', [$this, 'getPendingSent']),
        ];
    }

    public function getFriends(): array
    {
        $user = $this->security->getUser();
        if (!$user) {
            return [];
        }

        return $this->friendshipRepository->findFriendsOfUser($user);
    }

    public function getPendingReceived(): array
    {
        $user = $this->security->getUser();
        if (!$user) {
            return [];
        }

        return $this->friendshipRepository->findPendingReceivedByUser($user);
    }

    public function getPendingSent(): array
    {
        $user = $this->security->getUser();
        if (!$user) {
            return [];
        }

        return $this->friendshipRepository->findPendingSentByUser($user);
    }
}
