<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user) : void
    {
        if (!$user instanceof \App\Entity\User) return;

        if (!$user->getIsVerified()) {
            throw new CustomUserMessageAccountStatusException(
                'Veuillez verifier votre email avant de vous connecter.'
            );
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {

    }
}
