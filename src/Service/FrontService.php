<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\Purchase;
use App\Entity\User;
use App\Entity\UserLesson;
use App\Entity\Theme;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service handling front-end user actions related to courses, lessons, purchases, and certifications.
 */
class FrontService
{
    /**
     * @param EntityManagerInterface $em The Doctrine entity manager
     */
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * Check if a user has access to a specific course.
     *
     * @param User $user The user
     * @param Course $course The course to check
     * @return bool True if the user has access, false otherwise
     */
    public function userHasAccessToCourse(User $user, Course $course): bool
    {
        foreach ($user->getPurchasedCourses() as $userCourse) {
            if ($userCourse === $course) return true;
        }

        foreach ($course->getPurchases() as $purchase) {
            if ($purchase->getUser() === $user) return true;
        }

        return false;
    }

    /**
     * Check if a user has access to a specific lesson.
     *
     * @param User $user The user
     * @param Lesson $lesson The lesson to check
     * @return bool True if the user has access, false otherwise
     */
    public function userHasAccessToLesson(User $user, Lesson $lesson): bool
    {
        foreach ($lesson->getPurchases() as $purchase) {
            if ($purchase->getUser() === $user) return true;
        }

        // Access to lesson only if the user has purchased the lesson
        foreach ($user->getUserLessons() as $userLesson) {
            if ($userLesson->getLesson() === $lesson) return true;
        }

        return false;
    }

    /**
     * Simulate a sandbox purchase for a course or lesson.
     * Grants the user access in sandbox mode without real payment.
     *
     * @param User $user The user performing the purchase
     * @param object $item The Course or Lesson object
     * @return Purchase The created Purchase entity
     * @throws \Exception
     */
    public function simulateSandboxPurchase(User $user, object $item): Purchase
    {
        $purchase = new Purchase();
        $purchase->sandboxPurchase($user, $item); // Link user and item
        $this->em->persist($purchase);

        if ($item instanceof Course) {
            // Grant course access
            $user->addPurchasedCourse($item);
            $this->em->persist($user);

            // ❌ Do NOT automatically give access to individual lessons
        } elseif ($item instanceof Lesson) {
            // Grant access to single lesson
            $userLesson = $this->em->getRepository(UserLesson::class)
                                   ->findOneBy(['user' => $user, 'lesson' => $item]);
            if (!$userLesson) {
                $userLesson = new UserLesson();
                $userLesson->setUser($user)->setLesson($item);
                $this->em->persist($userLesson);
            }
        }

        $this->em->flush();

        return $purchase;
    }

    /**
     * Validate a lesson for the user and generate certification if all lessons in the course are validated.
     *
     * @param User $user The user
     * @param Lesson $lesson The lesson to validate
     * @return void
     */
    public function validateLesson(User $user, Lesson $lesson): void
    {
        $userLesson = $this->em->getRepository(UserLesson::class)
                               ->findOneBy(['user' => $user, 'lesson' => $lesson]);

        if (!$userLesson) {
            $userLesson = new UserLesson();
            $userLesson->setUser($user)->setLesson($lesson);
        }

        $userLesson->setValidated(true);
        $this->em->persist($userLesson);
        $this->em->flush();

        // Check if all lessons in the course are validated
        $allValidated = true;
        foreach ($lesson->getCourse()->getLessons() as $l) {
            if (!$user->hasValidatedLesson($l)) {
                $allValidated = false;
                break;
            }
        }

        if ($allValidated) {
            $user->addCertificationFromCourse($lesson->getCourse());
            $this->em->flush();
        }
    }

    /**
     * Validate an entire course for certification if all lessons are validated.
     *
     * @param User $user The user
     * @param Course $course The course to validate
     * @return void
     */
    public function validateCourseForCertification(User $user, Course $course): void
    {
        $allValidated = true;
        foreach ($course->getLessons() as $lesson) {
            if (!$user->hasValidatedLesson($lesson)) {
                $allValidated = false;
                break;
            }
        }

        if ($allValidated) {
            $user->addCertificationFromCourse($course);
            $this->em->flush();
        }
    }

    /**
     * Retrieve a theme along with its associated courses.
     *
     * @param int $themeId The ID of the theme
     * @return Theme The requested Theme entity
     * @throws \Exception If theme not found
     */
    public function getThemeWithCourses(int $themeId): Theme
    {
        $theme = $this->em->getRepository(Theme::class)->find($themeId);
        if (!$theme) throw new \Exception('Theme not found.');
        return $theme;
    }
}
