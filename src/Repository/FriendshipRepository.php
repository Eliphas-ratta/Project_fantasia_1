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
     * Find all accepted friends of a user
     * (both sent and received requests)
     *
     * @return Friendship[]
     */
    public function findFriends(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.user = :user OR f.friend = :user')
            ->andWhere('f.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', 'accepted')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all pending friend requests received by a user
     *
     * @return Friendship[]
     */
    public function findPendingRequestsReceived(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.friend = :user')
            ->andWhere('f.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', 'pending')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find all pending friend requests sent by a user
     *
     * @return Friendship[]
     */
    public function findPendingRequestsSent(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.user = :user')
            ->andWhere('f.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', 'pending')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie si une relation existe déjà entre deux utilisateurs
     * (dans les deux sens)
     */
    public function findExistingRelation(User $user1, User $user2): ?Friendship
    {
        return $this->createQueryBuilder('f')
            ->where('(f.user = :user1 AND f.friend = :user2)')
            ->orWhere('(f.user = :user2 AND f.friend = :user1)')
            ->setParameter('user1', $user1)
            ->setParameter('user2', $user2)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
