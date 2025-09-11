<?php
namespace App\Controller;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\Theme;
use App\Entity\Purchase;
use App\Entity\User;
use App\Entity\UserLesson;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/', name: 'front_')]
/**
 * Front controller of the application.
 *
 * Manages the display of themes, courses, and lessons for end-users.
 * Also handles purchase simulation, lesson validation, and certification summaries.
 */
class FrontController extends AbstractController
{
    /**
     * Homepage displaying the list of themes.
     *
     * Accessible to all users, including guests.
     *
     * @param EntityManagerInterface $em Doctrine entity manager
     * @return Response HTTP response rendering the themes
     */
    #[Route('', name: 'home')]
    #[Route('themes', name: 'themes')]
    public function themes(EntityManagerInterface $em): Response
    {
        $themes = $em->getRepository(Theme::class)->findAll();
        return $this->render('front/themes.html.twig', compact('themes'));
    }

    /**
     * Displays a course and its lessons.
     *
     * Accessible only to logged-in users who purchased the course or one of its lessons.
     *
     * @param Course $course Course entity to display
     * @param User|null $user Current logged-in user (injected by security)
     * @return Response Redirects if access denied, otherwise displays the course
     */
    #[Route('courses/{id}', name: 'courses')]
    #[IsGranted('ROLE_USER')]
    public function courses(Course $course, ?User $user = null): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $hasAccess = false;
        foreach ($course->getPurchases() as $purchase) {
            if ($purchase->getUser() === $user) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            $this->addFlash('error', 'You must purchase this course to access it.');
            return $this->redirectToRoute('front_themes');
        }

        return $this->render('front/courses.html.twig', compact('course'));
    }

    /**
     * Displays a lesson.
     *
     * Accessible only to logged-in users who purchased the lesson or the full course.
     *
     * @param Lesson $lesson Lesson entity to display
     * @return Response Redirects if access denied, otherwise displays the lesson
     */
    #[Route('lessons/{id}', name: 'lessons')]
    #[IsGranted('ROLE_USER')]
    public function lessons(Lesson $lesson): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $hasAccess = false;

        // Check direct lesson purchase
        foreach ($lesson->getPurchases() as $purchase) {
            if ($purchase->getUser() === $user) {
                $hasAccess = true;
                break;
            }
        }

        // Check purchase of the full course
        foreach ($lesson->getCourse()->getPurchases() as $purchase) {
            if ($purchase->getUser() === $user) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            $this->addFlash('error', 'You do not have access to this lesson.');
            return $this->redirectToRoute('front_courses', ['id' => $lesson->getCourse()->getId()]);
        }

        $validated = $user->hasValidatedLesson($lesson);

        return $this->render('front/lessons.html.twig', [
            'lesson' => $lesson,
            'validated' => $validated,
        ]);
    }

    /**
     * Simulates a purchase of a course or lesson.
     *
     * Accessible only to logged-in users with verified email.
     *
     * @param EntityManagerInterface $em Doctrine entity manager
     * @param string $type Type of item to purchase ("course" or "lesson")
     * @param int $id Item identifier
     * @return Response Redirects after simulated purchase
     */
    #[Route('purchase/{type}/{id}', name: 'purchase')]
    #[IsGranted('ROLE_USER')]
    public function purchase(EntityManagerInterface $em, string $type, int $id): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user->isVerified()) {
            $this->addFlash('error', 'You must verify your email to make a purchase.');
            return $this->redirectToRoute('front_themes');
        }

        $item = match($type) {
            'course' => $em->getRepository(Course::class)->find($id),
            'lesson' => $em->getRepository(Lesson::class)->find($id),
            default => null,
        };

        if (!$item) {
            $this->addFlash('error', 'Item not found.');
            return $this->redirectToRoute('front_themes');
        }

        $purchase = new Purchase();
        $purchase->sandboxPurchase($user, $item);
        $em->persist($purchase);
        $em->flush();

        $this->addFlash('success', 'Simulated purchase successful!');
        return $this->redirectToRoute('front_themes');
    }

    /**
     * Marks a lesson as validated by the user.
     *
     * If all lessons of a course are validated, a certification is automatically granted.
     *
     * @param EntityManagerInterface $em Doctrine entity manager
     * @param Lesson $lesson Lesson entity to validate
     * @return Response Redirects to the course after validation
     */
    #[Route('validate-lesson/{id}', name: 'validate_lesson')]
    #[IsGranted('ROLE_USER')]
    public function validateLesson(EntityManagerInterface $em, Lesson $lesson): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $userLesson = $em->getRepository(UserLesson::class)
                         ->findOneBy(['user' => $user, 'lesson' => $lesson]);

        if (!$userLesson) {
            $userLesson = new UserLesson();
            $userLesson->setUser($user)
                       ->setLesson($lesson);
        }

        $userLesson->setValidated(true);
                   
        $em->persist($userLesson);
        $em->flush();

        // Check if all lessons of the course are validated
        $allValidated = true;
        foreach ($lesson->getCourse()->getLessons() as $l) {
            if (!$user->hasValidatedLesson($l)) {
                $allValidated = false;
                break;
            }
        }

        if ($allValidated) {
            $user->addCertificationFromCourse($lesson->getCourse());
            $em->flush();
        }

        $this->addFlash('success', 'Lesson validated!');
        return $this->redirectToRoute('front_courses', ['id' => $lesson->getCourse()->getId()]);
    }

    /**
     * Displays the summary of certifications obtained by the user.
     *
     * Accessible only to logged-in users.
     *
     * @return Response HTTP response rendering certifications
     */
    #[Route('certifications', name: 'certifications')]
    #[IsGranted('ROLE_USER')]
    public function certifications(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $certifications = $user->getFrontCertifications();

        return $this->render('front/certifications.html.twig', compact('certifications'));
    }
}
