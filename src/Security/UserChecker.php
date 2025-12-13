<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

/**
 * Checks the user's status before and after authentication.
 *
 * Implements UserCheckerInterface to add custom checks,
 * for example verifying that the user has confirmed their email before logging in.
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * Checks performed before authentication (pre-auth).
     *
     * @param UserInterface $user The current user
     *
     * @throws CustomUserMessageAccountStatusException If the user's email is not verified
     */
    public function checkPreAuth(UserInterface $user): void
    {
        // Ensure the user is an instance of our User entity
        if (!$user instanceof \App\Entity\User) {
            return;
        }

        // Check if the email is verified
        if (!$user->isVerified()) {
            throw new CustomUserMessageAccountStatusException(
                'Please verify your email before logging in.'
            );
        }
    }

    /**
     * Checks performed after authentication (post-auth).
     *
     * @param UserInterface $user The current user
     */
    public function checkPostAuth(UserInterface $user): void
    {
        // No post-auth checks for now
    }
}
