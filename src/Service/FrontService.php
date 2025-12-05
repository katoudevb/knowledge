<?php

namespace App\Service;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\Purchase;
use App\Entity\User;
use App\Entity\UserLesson;
use App\Entity\Theme;
use Doctrine\ORM\EntityManagerInterface;

class FrontService
{
    public function __construct(private EntityManagerInterface $em) {}

    /**
     * Check if a user has access to a course via purchase.
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
     * Check if a user has access to a lesson via purchase or course purchase.
     */
    public function userHasAccessToLesson(User $user, Lesson $lesson): bool
    {
        foreach ($lesson->getPurchases() as $purchase) {
            if ($purchase->getUser() === $user) return true;
        }

        if ($this->userHasAccessToCourse($user, $lesson->getCourse())) {
            return true;
        }

        foreach ($user->getUserLessons() as $userLesson) {
            if ($userLesson->getLesson() === $lesson) return true;
        }

        return false;
    }

    /**
     * Simulate a sandbox purchase for a course or a lesson.
     * Grants access to the user in sandbox mode.
     */
    public function simulateSandboxPurchase(User $user, object $item): Purchase
    {
        $purchase = new Purchase();
        $purchase->sandboxPurchase($user, $item); // link user and item

        $this->em->persist($purchase);

        if ($item instanceof Course) {
            // Grant course access
            $user->addPurchasedCourse($item);
            $this->em->persist($user);

            // Grant access to all lessons in the course
            foreach ($item->getLessons() as $lesson) {
                $userLesson = $this->em->getRepository(UserLesson::class)
                                       ->findOneBy(['user' => $user, 'lesson' => $lesson]);
                if (!$userLesson) {
                    $userLesson = new UserLesson();
                    $userLesson->setUser($user)->setLesson($lesson);
                    $this->em->persist($userLesson);
                }
            }
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
     * Validate a lesson for the user and generate certification if all lessons are validated.
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
     * Validate an entire course for certification.
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
    public function getThemeWithCourses(int $themeId): Theme
    {
        $theme = $this->em->getRepository(Theme::class)->find($themeId);
        if (!$theme) throw new \Exception('Theme not found.');
        return $theme;
    }
}
