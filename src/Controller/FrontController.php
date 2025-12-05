<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Lesson;
use App\Entity\Course;
use App\Repository\LessonRepository;
use App\Repository\CourseRepository;
use App\Service\FrontService;
use App\Service\ThemeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * FrontController for user actions.
 */
class FrontController extends AbstractController
{
    public function __construct(
        private LessonRepository $lessonRepository,
        private CourseRepository $courseRepository,
        private FrontService $frontService,
        private ThemeService $themeService
    ) {}

    private function getCurrentUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException("L'/utilisateur doit être connecté");
        }
        return $user;
    }

    #[Route('/front/lesson/{id}/purchase', name: 'front_lesson_purchase')]
    public function purchaseLesson(Lesson $lesson): Response
    {
        $user = $this->getCurrentUser();
        $this->frontService->simulateSandboxPurchase($user, $lesson);

        $this->addFlash('success', "Leçon '{$lesson->getTitle()}' a été acheté en mode bac à sable !");

        return $this->redirectToRoute('front_dashboard');
    }

    #[Route('/front/course/{id}/purchase', name: 'front_course_purchase')]
    public function purchaseCourse(Course $course): Response
    {
        $user = $this->getCurrentUser();
        $this->frontService->simulateSandboxPurchase($user, $course);

        $this->addFlash('success', "Cours '{$course->getTitle()}' a été acheté en mode bac à sable !");

        return $this->redirectToRoute('front_course_show', ['id' => $course->getId()]);
    }

    #[Route('/front/lesson/{id}', name: 'front_lesson_show')]
    public function showLesson(Lesson $lesson): Response
    {
        $user = $this->getCurrentUser();
        $hasAccess = $this->frontService->userHasAccessToLesson($user, $lesson);

        return $this->render('front/lessons.html.twig', [
            'lesson' => $lesson,
            'hasAccess' => $hasAccess,
            'hasValidated' => $user->hasValidatedLesson($lesson),
        ]);
    }

    #[Route('/front/course/{id}', name: 'front_course_show')]
    public function showCourse(Course $course): Response
    {
        $user = $this->getCurrentUser();
        $hasAccess = $this->frontService->userHasAccessToCourse($user, $course);

        return $this->render('front/courses.html.twig', [
            'course' => $course,
            'hasAccess' => $hasAccess,
        ]);
    }

    #[Route('/front/themes', name: 'front_themes')]
    public function themes(): Response
    {
        $themes = $this->themeService->getAllThemes(); // récupère tous les thèmes
        return $this->render('front/themes.html.twig', [
            'themes' => $themes,
        ]);
    }

    #[Route('/front/certifications', name: 'front_certifications')]
    public function listCertifications(): Response
    {
        $user = $this->getCurrentUser();
        $certifications = $user->getFrontCertifications();

        return $this->render('front/certifications.html.twig', [
            'user' => $user,
            'certifications' => $certifications,
        ]);
    }

    #[Route('/front/theme/{themeId}/courses', name: 'front_theme_courses')]
    public function themeCourses(int $themeId): Response
    {
        $theme = $this->themeService->getThemeWithCourses($themeId);

        return $this->render('front/theme_courses.html.twig', [
            'theme' => $theme,
        ]);
    }

    #[Route('/front/lesson/{id}/validate', name: 'front_lesson_validate', methods: ['POST'])]
    public function validateLesson(Lesson $lesson): Response
    {
        $user = $this->getCurrentUser();
        $this->frontService->validateLesson($user, $lesson);

        $this->addFlash('success', "Leçon '{$lesson->getTitle()}' a été validée avec succès !");

        return $this->redirectToRoute('front_dashboard');
    }

    #[Route('/front/dashboard', name: 'front_dashboard')]
    public function dashboard(): Response
    {
        $user = $this->getCurrentUser();

        // Récupérer tous les thèmes
        $themes = $this->themeService->getAllThemes();

        // Pour chaque thème, filtrer les cours accessibles à l'utilisateur
        foreach ($themes as $theme) {
            $accessibleCourses = array_filter(
                $theme->getCourses()->toArray(),
                fn($course) => $this->frontService->userHasAccessToCourse($user, $course)
            );
            $theme->setAccessibleCourses($accessibleCourses);
        }

        return $this->render('front/dashboard.html.twig', [
            'user' => $user,
            'themes' => $themes,
        ]);
    }


}

