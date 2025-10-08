<?php

namespace App\Repository;

use App\Entity\Friendship;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Friendship>
 */
class FriendshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Friendship::class);
    }

    /**
     * Retourne la liste des amis confirmés (status = 'accepted') d’un utilisateur.
     */
    public function findFriendsOfUser(User $user): array
    {
        $qb = $this->createQueryBuilder('f')
            ->where('(f.user = :user OR f.friend = :user)')
            ->andWhere('f.status = :accepted')
            ->setParameter('user', $user)
            ->setParameter('accepted', 'accepted');

        $friendships = $qb->getQuery()->getResult();

        $friends = [];
        foreach ($friendships as $friendship) {
            if ($friendship->getUser() === $user) {
                $friends[] = $friendship->getFriend();
            } else {
                $friends[] = $friendship->getUser();
            }
        }

        return $friends;
    }

    /**
     * Retourne les demandes d’amis reçues non encore acceptées (status = 'pending').
     */
    public function findPendingReceivedByUser(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.friend = :user')
            ->andWhere('f.status = :pending')
            ->setParameter('user', $user)
            ->setParameter('pending', 'pending')
            ->orderBy('f.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les demandes d’amis envoyées non encore acceptées (status = 'pending').
     */
    public function findPendingSentByUser(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.user = :user')
            ->andWhere('f.status = :pending')
            ->setParameter('user', $user)
            ->setParameter('pending', 'pending')
            ->orderBy('f.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie si une relation d’amitié (dans un sens ou l’autre) existe déjà entre deux utilisateurs.
     */
    public function findExistingRelation(User $user1, User $user2): ?Friendship
    {
        return $this->createQueryBuilder('f')
            ->where('(f.user = :u1 AND f.friend = :u2) OR (f.user = :u2 AND f.friend = :u1)')
            ->setParameter('u1', $user1)
            ->setParameter('u2', $user2)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
