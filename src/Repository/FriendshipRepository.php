<?php

namespace App\Repository;

use App\Entity\Friendship;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FriendshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Friendship::class);
    }

    /**
     * Retourne la liste des amis confirmés (status = 'accepted') d’un utilisateur.
     *
     * @param User $user
     * @return User[]
     */
    public function getFriends(User $user): array
    {
        $qb = $this->createQueryBuilder('f')
            ->where('(f.user = :user OR f.friend = :user)')
            ->andWhere('f.status = :accepted')
            ->setParameter('user', $user)
            ->setParameter('accepted', 'accepted');

        $friendships = $qb->getQuery()->getResult();

        $friends = [];
        foreach ($friendships as $friendship) {
            // Si l’utilisateur est le demandeur, on retourne le destinataire
            if ($friendship->getUser() === $user) {
                $friends[] = $friendship->getFriend();
            }
            // Sinon, on retourne l’expéditeur
            else {
                $friends[] = $friendship->getUser();
            }
        }

        return $friends;
    }
}
