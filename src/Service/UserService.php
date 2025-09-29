<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    /**
     * Get all users.
     *
     * @return User[]
     */
    public function getAllUsers(): array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    /**
     * Save a user (create or update).
     */
    public function saveUser(User $user, ?string $plainPassword = null): void
    {
        if ($plainPassword) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    /**
     * Register a new user with ROLE_CLIENT and a hashed password.
     */
    public function registerUser(User $user, string $plainPassword): void
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));

        if (!in_array('ROLE_CLIENT', $user->getRoles(), true)) {
            $user->setRoles(array_merge($user->getRoles(), ['ROLE_CLIENT']));
        }

        $this->saveUser($user);
    }
}
