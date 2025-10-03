<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    /**
     * Vérifications exécutées avant l'authentification.
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        // Empêche la connexion si le compte n'est pas encore vérifié
        if (!$user->isVerified()) {
            throw new CustomUserMessageAccountStatusException(
                'Ton compte n’est pas encore activé. Vérifie ta boîte mail pour confirmer ton e-mail.'
            );
        }
    }

    /**
     * Vérifications exécutées après l'authentification.
     */
    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        // Ici tu peux ajouter d'autres vérifications post-login si besoin
    }
}
