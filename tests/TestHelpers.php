<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Lesson;
use App\Entity\Course;
use App\Entity\Theme;
use App\Entity\Purchase;
use App\Entity\UserLesson;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * Trait TestHelpers
 * 
 * Fournit des méthodes utilitaires pour les tests fonctionnels :
 * - Initialisation du client et de l'EntityManager
 * - Purge des entités pour un état propre avant chaque test
 * - Création d'utilisateurs, d'achats et de leçons avec thème
 */
trait TestHelpers
{
    /** @var KernelBrowser Client Symfony pour faire des requêtes */
    protected KernelBrowser $client;

    /** @var EntityManagerInterface EntityManager pour interagir avec la BDD */
    protected EntityManagerInterface $em;

    /**
     * Initialise le client et l'EntityManager.
     * ⚠️ Appeler dans le setUp() de chaque test.
     */
    protected function initTest(): void
    {
        $this->client = static::createClient();
        $this->em = static::getContainer()->get('doctrine')->getManager();
    }

    /**
     * Supprime toutes les entités User, Purchase et UserLesson.
     * Permet d'avoir une BDD propre avant chaque test.
     */
    protected function purgeEntities(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\UserLesson')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Purchase')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
        $this->em->flush();
    }

    /**
     * Crée un utilisateur et le persiste.
     */
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

    /**
     * Crée un achat pour une leçon.
     */
    protected function purchaseLesson(User $user, Lesson $lesson, ?int $amount = null): Purchase
    {
        $existing = $this->em->getRepository(Purchase::class)
                       ->findOneBy(['user' => $user, 'lesson' => $lesson]);

        if ($existing) return $existing;

        $purchase = new Purchase();
        $purchase->setUser($user)
                 ->setLesson($lesson)
                 ->setAmount($amount ?? $lesson->getPrice())
                 ->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($purchase);
        $this->em->flush();

        return $purchase;
    }

    /**
     * Crée un achat pour un cours.
     */
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

    /**
     * Crée un UserLesson pour suivre la validation d'une leçon.
     */
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

    /**
     * Crée une leçon avec un thème et un cours associés.
     * ⚠️ Permet d'éviter les erreurs theme_id cannot be null.
     */
    protected function createLessonWithTheme(
        string $lessonTitle = 'Test Lesson',
        float $price = 50,
        string $themeName = 'Test Theme',
        string $courseTitle = 'Test Course'
    ): Lesson
    {
        // Créer un thème
        $theme = new Theme();
        $theme->setName($themeName);
        $this->em->persist($theme);

        // Créer un cours
        $course = new Course();
        $course->setTitle($courseTitle)
               ->setPrice($price)
               ->setTheme($theme);
        $this->em->persist($course);

        // Créer la leçon
        $lesson = new Lesson();
        $lesson->setTitle($lessonTitle)
               ->setPrice($price)
               ->setCourse($course)
               ->setTheme($theme);
        $this->em->persist($lesson);

        $this->em->flush();

        return $lesson;
    }

        protected function createTheme(string $name = 'Test Theme'): Theme
    {
        $theme = new Theme();
        $theme->setName($name);
        $this->em->persist($theme);
        $this->em->flush();
        return $theme;
    }

    protected function createCourse(string $title, float $price, Theme $theme): Course
    {
        $course = new Course();
        $course->setTitle($title)
            ->setPrice($price)
            ->setTheme($theme);
        $this->em->persist($course);
        $this->em->flush();
        return $course;
    }

    protected function createLesson(string $title, float $price, Course $course, Theme $theme): Lesson
    {
        $lesson = new Lesson();
        $lesson->setTitle($title)
            ->setPrice($price)
            ->setCourse($course)
            ->setTheme($theme);
        $this->em->persist($lesson);
        $this->em->flush();
        return $lesson;
    }

}
