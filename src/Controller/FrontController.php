<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Lesson;
use App\Entity\Course;
use App\Entity\Theme; // ajouté pour Theme
use App\Repository\LessonRepository;
use App\Repository\CourseRepository;
use App\Service\FrontService;
use App\Service\ThemeService; // ajouté pour ThemeService
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller responsible for front-end user actions.
 *
 * Handles:
 *  - Landing page / home redirection
 *  - Displaying the user dashboard
 *  - Showing lessons
 *  - Managing lesson validation and purchases
 *  - Handling certifications
 *  - Displaying available themes/courses
 */
class FrontController extends AbstractController
{
    /**
     * Constructor with dependency injection.
     *
     * @param LessonRepository $lessonRepository
     * @param CourseRepository $courseRepository
     * @param FrontService $frontService
     * @param ThemeService $themeService
     */
    public function __construct(
        private LessonRepository $lessonRepository,
        private CourseRepository $courseRepository,
        private FrontService $frontService,
        private ThemeService $themeService // injection du service ThemeService
    ) {}

    /**
     * Retrieves the currently logged-in user.
     *
     * Ensures the user is of type App\Entity\User.
     *
     * @return User
     * @throws AccessDeniedException if the user is not logged in
     */
    private function getCurrentUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('User must be logged in.');
        }
        return $user;
    }

    /**
     * Landing page / home route.
     *
     * Redirects non-authenticated users to login,
     * or authenticated users to the dashboard.
     *
     * @return Response
     */
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            // Redirect to login if not authenticated
            return $this->redirectToRoute('app_login');
        }

        // Redirect authenticated users to the dashboard
        return $this->redirectToRoute('front_dashboard');
    }

    /**
     * Displays the dashboard for the current user.
     *
     * @return Response
     */
    #[Route('/front/dashboard', name: 'front_dashboard')]
    public function dashboard(): Response
    {
        $user = $this->getCurrentUser();

        $lessons = $this->lessonRepository->findAll();
        $validatedLessons = array_filter(
            $lessons,
            fn(Lesson $lesson) => $user->hasValidatedLesson($lesson)
        );

        $certifications = $user->getFrontCertifications();

        return $this->render('front/dashboard.html.twig', [
            'user' => $user,
            'lessons' => $lessons,
            'validatedLessons' => $validatedLessons,
            'certifications' => $certifications,
        ]);
    }

    /**
     * Shows a single lesson page for the current user.
     *
     * @param Lesson $lesson The lesson entity to display
     * @return Response
     */
    #[Route('/front/lesson/{id}', name: 'front_lesson_show')]
    public function showLesson(Lesson $lesson): Response
    {
        $user = $this->getCurrentUser();
        $hasAccess = $this->frontService->userHasAccessToLesson($user, $lesson);

        return $this->render('front/lessons.html.twig', [
            'lesson' => $lesson,
            'hasValidated' => $user->hasValidatedLesson($lesson),
            'hasAccess' => $hasAccess,
            'isVerified' => $user->isVerified(),
        ]);
    }

    /**
     * Simulates the purchase of a lesson for the current user.
     *
     * @param Lesson $lesson The lesson to purchase
     * @return Response
     */
    #[Route('/front/lesson/{id}/purchase', name: 'front_lesson_purchase')]
    public function purchaseLesson(Lesson $lesson): Response
    {
        $user = $this->getCurrentUser();
        $this->frontService->simulatePurchase($user, $lesson);

        $this->addFlash('success', "Lesson '{$lesson->getTitle()}' has been successfully purchased.");

        return $this->redirectToRoute('front_dashboard');
    }

    /**
     * Validates a lesson for the current user and generates certification if all lessons are completed.
     *
     * @param Lesson $lesson The lesson to validate
     * @return Response
     */
    #[Route('/front/lesson/{id}/validate', name: 'front_lesson_validate', methods: ['POST'])]
    public function validateLesson(Lesson $lesson): Response
    {
        $user = $this->getCurrentUser();
        $this->frontService->validateLesson($user, $lesson);

        $this->addFlash('success', "Lesson '{$lesson->getTitle()}' has been successfully validated.");

        return $this->redirectToRoute('front_dashboard');
    }

    /**
     * Grants a certification to the current user for a completed course.
     *
     * @param int $courseId The ID of the course to certify
     * @return Response
     */
    #[Route('/front/course/{courseId}/certify', name: 'front_course_certify')]
    public function addCertification(int $courseId): Response
    {
        $user = $this->getCurrentUser();

        $course = $this->courseRepository->find($courseId);
        if (!$course) {
            throw $this->createNotFoundException('Course not found.');
        }

        $this->frontService->validateCourseForCertification($user, $course);

        $this->addFlash('success', "Certification for course '{$course->getTitle()}' has been added.");

        return $this->redirectToRoute('front_dashboard');
    }

    /**
     * Lists all certifications the current user has obtained.
     *
     * @return Response
     */
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

    /**
     * Displays the list of available themes or courses.
     *
     * @return Response
     */
    #[Route('/front/themes', name: 'front_themes')]
    public function themes(): Response
    {
        // Utilisation du ThemeService pour récupérer tous les thèmes
        $themes = $this->themeService->getAllThemes();

        return $this->render('front/themes.html.twig', [
            'themes' => $themes,
        ]);
    }

    /**
     * Shows all courses belonging to a specific theme.
     *
     * @param int $themeId The ID of the theme
     * @return Response
     */
    #[Route('/front/theme/{themeId}/courses', name: 'front_theme_courses')]
    public function themeCourses(int $themeId): Response
    {
        // Utilisation du ThemeService pour récupérer le thème avec ses cours
        $theme = $this->themeService->getThemeWithCourses($themeId);

        return $this->render('front/theme_courses.html.twig', [
            'theme' => $theme,
        ]);
    }

    /**
     * Admin route: adds a course to a theme.
     *
     * @param Theme $theme
     * @param Course $course
     * @return Response
     */
    #[Route('/admin/theme/{themeId}/add-course/{courseId}', name: 'admin_theme_add_course')]
    public function addCourseToTheme(Theme $theme, Course $course): Response
    {
        $this->themeService->addCourseToTheme($theme, $course);

        $this->addFlash('success', "Le cours '{$course->getTitle()}' a été ajouté au thème '{$theme->getName()}'.");

        return $this->redirectToRoute('admin_theme_edit', ['id' => $theme->getId()]);
    }

    #[Route('/front/course/{id}/purchase', name: 'front_course_purchase')]
    public function purchaseCourse(Course $course): Response
    {
        $user = $this->getCurrentUser();

        // Appelle ton service pour gérer l'achat
        $this->frontService->simulateCoursePurchase($user, $course);

        $this->addFlash('success', "Le cours '{$course->getTitle()}' a été acheté avec succès !");

        return $this->redirectToRoute('front_dashboard');
    }

    #[Route('/front/course/{id}', name: 'front_course_show')]
    public function showCourse(Course $course): Response
    {
        return $this->render('front/courses.html.twig', [
            'course' => $course,
        ]);
    }

}
