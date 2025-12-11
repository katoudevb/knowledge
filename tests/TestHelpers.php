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
 * Provides utility methods for functional tests:
 * - Initialization of Symfony client and EntityManager
 * - Purging entities for a clean database state
 * - Creation of users, purchases, lessons, courses, and themes
 */
trait TestHelpers
{
    /** @var KernelBrowser Symfony client to make requests */
    protected KernelBrowser $client;

    /** @var EntityManagerInterface EntityManager to interact with the database */
    protected EntityManagerInterface $em;

    /**
     * Initializes the Symfony client and EntityManager.
     * ⚠️ Call this in the setUp() of each test.
     */
    protected function initTest(): void
    {
        $this->client = static::createClient();
        $this->em = static::getContainer()->get('doctrine')->getManager();
    }

    /**
     * Purges User, Purchase, UserLesson, and Certification entities.
     * Ensures a clean database state before each test.
     */
    protected function purgeEntities(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\Certification')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\UserLesson')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Purchase')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
        $this->em->flush();
    }

    /**
     * Creates a User and persists it to the database.
     *
     * @param string|null $email The email for the user. If null, a unique email is generated.
     * @param bool $verified Whether the user is verified. Defaults to true.
     * @return User
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
     * Creates a Purchase entity for a lesson.
     *
     * @param User $user
     * @param Lesson $lesson
     * @param int|null $amount
     * @return Purchase
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
     * Creates a Purchase entity for a course.
     *
     * @param User $user
     * @param Course $course
     * @param int|null $amount
     * @return Purchase
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
     * Creates a UserLesson entity to track lesson validation.
     *
     * @param User $user
     * @param Lesson $lesson
     * @param bool $validated
     * @return UserLesson
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
     * Creates a lesson with an associated theme and course.
     * ⚠️ Avoids errors like "theme_id cannot be null".
     *
     * @param string $lessonTitle
     * @param float $price
     * @param string $themeName
     * @param string $courseTitle
     * @return Lesson
     */
    protected function createLessonWithTheme(
        string $lessonTitle = 'Test Lesson',
        float $price = 50,
        string $themeName = 'Test Theme',
        string $courseTitle = 'Test Course'
    ): Lesson
    {
        // Create a theme
        $theme = new Theme();
        $theme->setName($themeName);
        $this->em->persist($theme);

        // Create a course
        $course = new Course();
        $course->setTitle($courseTitle)
               ->setPrice($price)
               ->setTheme($theme);
        $this->em->persist($course);

        
        // Create the lesson
        $lesson = new Lesson();
        $lesson->setTitle($lessonTitle)
               ->setPrice($price)
               ->setCourse($course)
               ->setTheme($theme);
        $this->em->persist($lesson);

        $this->em->flush();

        return $lesson;
    }

    /**
     * Creates a Theme entity.
     *
     * @param string $name
     * @return Theme
     */
    protected function createTheme(string $name = 'Test Theme'): Theme
    {
        $theme = new Theme();
        $theme->setName($name);
        $this->em->persist($theme);
        $this->em->flush();
        return $theme;
    }

    /**
     * Creates a Course entity linked to a Theme.
     *
     * @param string $title
     * @param float $price
     * @param Theme $theme
     * @return Course
     */
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

    /**
     * Creates a Lesson entity linked to a Course and a Theme.
     *
     * @param string $title
     * @param float $price
     * @param Course $course
     * @param Theme $theme
     * @return Lesson
     */
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

    /**
     * Validates all lessons of a course for a user to generate a certification.
     *
     * @param User $user
     * @param Course $course
     */
    protected function validateCourse(User $user, Course $course): void
    {
        foreach ($course->getLessons() as $lesson) {
            // If the UserLesson already exists, retrieve it; otherwise, create it.
            $userLesson = $this->em->getRepository(UserLesson::class)
                                ->findOneBy(['user' => $user, 'lesson' => $lesson]);

            if (!$userLesson) {
                $userLesson = $this->createUserLesson($user, $lesson, true);
            } else {
                $userLesson->setValidated(true);
            }

            $this->em->persist($userLesson);
        }

        
        // Create the certification for this course
        $certification = new \App\Entity\Certification();
        $certification->setUser($user)
                      ->setCourse($course)
                      ->setObtainedAt(new \DateTimeImmutable());

        $this->em->persist($certification);
        $this->em->flush();
    }
}
