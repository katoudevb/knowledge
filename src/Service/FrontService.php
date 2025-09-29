<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\Purchase;
use App\Entity\User;
use App\Entity\UserLesson;
use Doctrine\ORM\EntityManagerInterface;

class FrontService
{
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * Check if a user has access to a course via direct purchase.
     */
    public function userHasAccessToCourse(User $user, Course $course): bool
    {
        foreach ($course->getPurchases() as $purchase) {
            if ($purchase->getUser() === $user) return true;
        }
        return false;
    }

    /**
     * Check if a user has access to a lesson either via lesson purchase or course purchase.
     */
    public function userHasAccessToLesson(User $user, Lesson $lesson): bool
    {
        // Direct lesson purchase
        foreach ($lesson->getPurchases() as $purchase) {
            if ($purchase->getUser() === $user) return true;
        }

        // Access via course purchase
        foreach ($lesson->getCourse()->getPurchases() as $purchase) {
            if ($purchase->getUser() === $user) return true;
        }

        return false;
    }

    /**
     * Simulate a purchase for a user (lesson or course).
     */
    public function simulatePurchase(User $user, object $item): Purchase
    {
        $purchase = new Purchase();
        $purchase->sandboxPurchase($user, $item);

        $this->em->persist($purchase);
        $this->em->flush();

        return $purchase;
    }

    /**
     * Simulate the purchase of a course and link it to the user.
     */
    public function simulateCoursePurchase(User $user, Course $course): void
    {
        // Add course to user's purchased courses
        $user->addPurchasedCourse($course);

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Validate a lesson for the user and generate certification if the course is fully completed.
     */
    public function validateLesson(User $user, Lesson $lesson): void
    {
        $userLesson = $this->em->getRepository(UserLesson::class)
                               ->findOneBy(['user' => $user, 'lesson' => $lesson]);

        if (!$userLesson) {
            $userLesson = new UserLesson();
            $userLesson->setUser($user)
                       ->setLesson($lesson);
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
     * Retrieve a theme along with its courses.
     */
    public function getThemeWithCourses(int $themeId): object
    {
        $theme = $this->em->getRepository(\App\Entity\Theme::class)->find($themeId);

        if (!$theme) {
            throw new \Exception('Theme not found.');
        }

        // Load courses for this theme (optional depending on ORM configuration)
        $courses = $this->em->getRepository(Course::class)
                            ->findBy(['theme' => $theme]);

        $theme->setCourses($courses);

        return $theme;
    }
}
