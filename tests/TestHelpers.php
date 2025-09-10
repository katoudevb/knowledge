<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Lesson;
use App\Entity\Course;
use App\Entity\Purchase;
use App\Entity\UserLesson;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait TestHelpers
{
    protected KernelBrowser $client;
    protected EntityManagerInterface $em;

    /**
     * ⚠️ Cette méthode doit être appelée dans setUp() de chaque test
     */
    protected function initTest(): void
    {
        // Crée le client
        $this->client = static::createClient();

        // Récupère l'EntityManager
        $this->em = static::getContainer()->get('doctrine')->getManager();
    }

    protected function purgeEntities(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\UserLesson')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Purchase')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
        $this->em->flush();
    }

    protected function createUser(?string $email = null, bool $verified = true): User
    {
        $this->purgeEntities();

        $email = $email ?? 'user_' . uniqid('', true) . '@example.com';
        $user = new User();
        $user->setEmail($email)
             ->setPassword(password_hash('password', PASSWORD_BCRYPT))
             ->setRoles(['ROLE_USER'])
             ->setIsVerified($verified);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    protected function purchaseLesson(User $user, Lesson $lesson, ?int $amount = null): Purchase
    {
        $existing = $this->em->getRepository(Purchase::class)
                       ->findOneBy(['user' => $user, 'lesson' => $lesson]);

        if ($existing) {
            return $existing;
        }

        $purchase = new Purchase();
        $purchase->setUser($user)
                 ->setLesson($lesson)
                 ->setAmount($amount ?? $lesson->getPrice())
                 ->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($purchase);
        $this->em->flush();

        return $purchase;
    }

    protected function purchaseCourse(User $user, Course $course, ?int $amount = null): Purchase
    {
        $purchase = new Purchase();
        $purchase->setUser($user)
                 ->setCourse($course)
                 ->setAmount($amount ?? $course->getPrice())
                 ->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($purchase);
        $this->em->flush();

        return $purchase;
    }

    protected function createUserLesson(User $user, Lesson $lesson, bool $validated = false): UserLesson
    {
        $userLesson = new UserLesson();
        $userLesson->setUser($user)
                   ->setLesson($lesson)
                   ->setValidated($validated);

        $this->em->persist($userLesson);
        $this->em->flush();

        return $userLesson;
    }
}
