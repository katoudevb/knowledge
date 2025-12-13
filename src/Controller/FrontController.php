<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Lesson;
use App\Entity\Course;
use App\Repository\LessonRepository;
use App\Repository\CourseRepository;
use App\Repository\PurchaseRepository;
use App\Service\FrontService;
use App\Service\ThemeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\StripeClient;

/**
 * Controller handling all front-end user interactions
 * including courses, lessons, purchases, dashboard, and Stripe integration.
 */
class FrontController extends AbstractController
{
    /**
     * Constructor for dependency injection
     *
     * @param LessonRepository $lessonRepository Repository for Lesson entities
     * @param CourseRepository $courseRepository Repository for Course entities
     * @param FrontService $frontService Service for front-end operations
     * @param ThemeService $themeService Service to handle themes
     */
    public function __construct(
        private LessonRepository $lessonRepository,
        private CourseRepository $courseRepository,
        private FrontService $frontService,
        private ThemeService $themeService
    ) {}

    /**
     * Returns the currently authenticated user
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException if the user is not logged in
     * @return User
     */
    private function getCurrentUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException("L'utilisateur doit être connecté.");
        }
        return $user;
    }

    // -----------------------------
    /**
     * Simulate a sandbox purchase for a lesson
     *
     * @param Lesson $lesson Lesson entity to purchase
     * @return Response Redirects to the lesson page with a success flash message
     */
    #[Route('/front/lesson/{id}/purchase', name: 'front_lesson_purchase')]
    public function purchaseLesson(Lesson $lesson): Response
    {
        $user = $this->getCurrentUser();
        $this->frontService->simulateSandboxPurchase($user, $lesson);
        $this->addFlash('success', "Leçon '{$lesson->getTitle()}' achetée !");
        return $this->redirectToRoute('front_lesson_show', ['id' => $lesson->getId()]);
    }

    /**
     * Simulate a sandbox purchase for a course
     *
     * @param Course $course Course entity to purchase
     * @return Response Redirects to the course page with a success flash message
     */
    #[Route('/front/course/{id}/purchase', name: 'front_course_purchase')]
    public function purchaseCourse(Course $course): Response
    {
        $user = $this->getCurrentUser();
        $this->frontService->simulateSandboxPurchase($user, $course);
        $this->addFlash('success', "Cours '{$course->getTitle()}' acheté !");
        return $this->redirectToRoute('front_course_show', ['id' => $course->getId()]);
    }

    // -----------------------------
    /**
     * Create a Stripe checkout session for a lesson
     *
     * @param Lesson $lesson Lesson entity to pay for
     * @param Request $request HTTP request
     * @return Response JSON response if AJAX, otherwise redirects to Stripe
     */
    #[Route('/front/lesson/{id}/checkout', name: 'stripe_checkout_lesson', methods: ['POST'])]
    public function stripeCheckoutLesson(Lesson $lesson, Request $request): Response
    {
        $stripe = new StripeClient($_ENV['STRIPE_SECRET_KEY']);
        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $lesson->getTitle()],
                    'unit_amount' => $lesson->getPrice() * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('front_lesson_show', ['id' => $lesson->getId(), 'paid' => 1], true),
            'cancel_url' => $this->generateUrl('front_lesson_show', ['id' => $lesson->getId()], true),
        ]);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['id' => $session->id]);
        }
        return new RedirectResponse($session->url);
    }

    /**
     * Create a Stripe checkout session for a course
     *
     * @param Course $course Course entity to pay for
     * @param Request $request HTTP request
     * @return Response JSON response if AJAX, otherwise redirects to Stripe
     */
    #[Route('/front/course/{id}/checkout', name: 'stripe_checkout_course', methods: ['POST'])]
    public function stripeCheckoutCourse(Course $course, Request $request): Response
    {
        $stripe = new StripeClient($_ENV['STRIPE_SECRET_KEY']);
        $user = $this->getCurrentUser();

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $course->getTitle()],
                    'unit_amount' => $course->getPrice() * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('front_course_show', ['id' => $course->getId(), 'paid' => 1], true),
            'cancel_url' => $this->generateUrl('front_course_show', ['id' => $course->getId()], true),
        ]);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['id' => $session->id]);
        }

        return new RedirectResponse($session->url);
    }

    // -----------------------------
    /**
     * Display a lesson page
     *
     * @param Lesson $lesson Lesson entity to display
     * @param Request $request HTTP request
     * @param PurchaseRepository $purchaseRepository Repository to check purchases
     * @return Response Rendered lesson page with access info
     */
    #[Route('/front/lesson/{id}', name: 'front_lesson_show')]
    public function showLesson(Lesson $lesson, Request $request, PurchaseRepository $purchaseRepository): Response
    {
        $user = $this->getCurrentUser();

        if ($request->query->get('paid') == 1) {
            $this->frontService->simulateSandboxPurchase($user, $lesson);
        }

        $hasAccess = $this->frontService->userHasAccessToLesson($user, $lesson);
        $isCoursePurchased = $purchaseRepository->findOneBy([
            'user' => $user,
            'course' => $lesson->getCourse(),
        ]) !== null;

        return $this->render('front/lessons.html.twig', [
            'lesson' => $lesson,
            'course' => $lesson->getCourse(),
            'hasAccess' => $hasAccess,
            'hasValidated' => $user->hasValidatedLesson($lesson),
            'purchasedLessonIds' => $hasAccess ? [$lesson->getId()] : [],
            'purchasedCourses' => $isCoursePurchased ? [$lesson->getCourse()->getId()] : [],
            'isCoursePurchased' => $isCoursePurchased,
            'stripePublicKey' => $_ENV['STRIPE_PUBLIC_KEY'],
        ]);
    }

    /**
     * Display a course page with lessons
     *
     * @param Course $course Course entity to display
     * @param Request $request HTTP request
     * @param PurchaseRepository $purchaseRepository Repository to check purchases
     * @return Response Rendered course page with lessons and access info
     */
    #[Route('/front/course/{id}', name: 'front_course_show')]
    public function showCourse(Course $course, Request $request, PurchaseRepository $purchaseRepository): Response
    {
        $user = $this->getCurrentUser();

        if ($request->query->get('paid') == 1) {
            $this->frontService->simulateSandboxPurchase($user, $course);
            return $this->redirectToRoute('front_course_show', ['id' => $course->getId()]);
        }

        $isCoursePurchased = $purchaseRepository->findOneBy([
            'user' => $user,
            'course' => $course
        ]) !== null;

        $purchasedLessonIds = [];
        foreach ($course->getLessons() as $lesson) {
            if ($isCoursePurchased || $this->frontService->userHasAccessToLesson($user, $lesson)) {
                $purchasedLessonIds[] = $lesson->getId();
            }
        }

        $purchasedLessonIdsByCourse = [$course->getId() => $purchasedLessonIds];
        $purchasedCourses = $isCoursePurchased ? [$course->getId()] : [];
        $allLessonsPurchased = count($purchasedLessonIds) === count($course->getLessons());

        $theme = $course->getTheme();

        $lessonsData = [];
        foreach ($course->getLessons() as $lesson) {
            $lessonsData[] = [
                'entity' => $lesson,
                'purchased' => in_array($lesson->getId(), $purchasedLessonIds),
            ];
        }

        return $this->render('front/courses.html.twig', [
            'course' => $course,
            'theme' => $theme,
            'purchasedLessonIdsByCourse' => $purchasedLessonIdsByCourse,
            'purchasedCourses' => $purchasedCourses,
            'isCoursePurchased' => $isCoursePurchased,
            'allLessonsPurchased' => $allLessonsPurchased,
            'lessonsData' => $lessonsData,
            'stripePublicKey' => $_ENV['STRIPE_PUBLIC_KEY'],
        ]);
    }

    // -----------------------------
    /**
     * Validate a lesson for the current user
     *
     * @param Lesson $lesson Lesson entity to validate
     * @return Response Redirect to the lesson page after validation
     */
    #[Route('/validate-lesson/{id}', name: 'front_lesson_validate', methods: ['POST'])]
    public function validateLesson(Lesson $lesson): Response
    {
        $user = $this->getCurrentUser();
        $this->frontService->validateLesson($user, $lesson);
        $this->addFlash('success', "Leçon '{$lesson->getTitle()}' validée !");
        return $this->redirectToRoute('front_lesson_show', ['id' => $lesson->getId()]);
    }

    // -----------------------------
    /**
     * Display the user dashboard with themes and accessible courses
     *
     * @param PurchaseRepository $purchaseRepository Repository to check purchases
     * @return Response Rendered dashboard page
     */
    #[Route('/front/dashboard', name: 'front_dashboard')]
    public function dashboard(PurchaseRepository $purchaseRepository): Response
    {
        $user = $this->getCurrentUser();
        $themes = $this->themeService->getAllThemes();

        $dashboardData = [];
        foreach ($themes as $theme) {
            $themeData = [
                'name' => $theme->getName(),
                'accessibleCourses' => [],
            ];

            foreach ($theme->getCourses() as $course) {
                $isCoursePurchased = $this->frontService->userHasAccessToCourse($user, $course);

                $accessibleLessons = [];
                foreach ($course->getLessons() as $lesson) {
                    if ($isCoursePurchased || $this->frontService->userHasAccessToLesson($user, $lesson)) {
                        $accessibleLessons[] = [
                            'entity' => $lesson,
                            'hasAccess' => true,
                        ];
                    }
                }

                if ($isCoursePurchased || count($accessibleLessons) > 0) {
                    $themeData['accessibleCourses'][] = [
                        'entity' => $course,
                        'hasAccess' => $isCoursePurchased,
                        'lessons' => $accessibleLessons,
                    ];
                }
            }

            if (count($themeData['accessibleCourses']) > 0) {
                $dashboardData[] = $themeData;
            }
        }

        return $this->render('front/dashboard.html.twig', [
            'user' => $user,
            'themes' => $dashboardData,
        ]);
    }

    // -----------------------------
    /**
     * Display all themes
     *
     * @return Response Rendered themes page
     */
    #[Route('/front/themes', name: 'front_themes')]
    public function themes(): Response
    {
        $themes = $this->themeService->getAllThemes();
        return $this->render('front/themes.html.twig', [
            'themes' => $themes,
        ]);
    }

    // -----------------------------
    /**
     * Display courses of a specific theme with purchased lessons info
     *
     * @param int $themeId Theme ID
     * @param PurchaseRepository $purchaseRepository Repository to check purchases
     * @return Response Rendered courses page for the theme
     */
    #[Route('/front/theme/{themeId}/courses', name: 'front_theme_courses')]
    public function themeCourses(int $themeId, PurchaseRepository $purchaseRepository): Response
    {
        $user = $this->getCurrentUser();
        $theme = $this->themeService->getThemeWithCourses($themeId);

        $purchasedCourses = [];
        $purchasedLessonIdsByCourse = [];

        foreach ($theme->getCourses() as $course) {
            $isCoursePurchased = $purchaseRepository->findOneBy([
                'user' => $user,
                'course' => $course
            ]) !== null;

            if ($isCoursePurchased) {
                $purchasedCourses[] = $course->getId();
                $purchasedLessonIdsByCourse[$course->getId()] = array_map(
                    fn($lesson) => $lesson->getId(),
                    $course->getLessons()->toArray()
                );
            } else {
                $lessonIds = [];
                foreach ($course->getLessons() as $lesson) {
                    if ($this->frontService->userHasAccessToLesson($user, $lesson)) {
                        $lessonIds[] = $lesson->getId();
                    }
                }
                $purchasedLessonIdsByCourse[$course->getId()] = $lessonIds;
            }
        }

        return $this->render('front/theme_courses.html.twig', [
            'theme' => $theme,
            'purchasedCourses' => $purchasedCourses,
            'purchasedLessonIdsByCourse' => $purchasedLessonIdsByCourse,
            'stripePublicKey' => $_ENV['STRIPE_PUBLIC_KEY'],
        ]);
    }

    // -----------------------------
    /**
     * Handle Stripe success redirect and simulate purchase
     *
     * @param Request $request HTTP request with query parameters
     * @return Response Redirects to the purchased lesson or course
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException if no lesson or course found
     */
    #[Route('/stripe/success', name: 'stripe_success')]
    public function stripeSuccess(Request $request): Response
    {
        $user = $this->getCurrentUser();

        if ($lessonId = $request->query->get('lesson')) {
            $lesson = $this->lessonRepository->find($lessonId);
            if ($lesson) {
                $this->frontService->simulateSandboxPurchase($user, $lesson);
                return $this->redirectToRoute('front_lesson_show', ['id' => $lesson->getId()]);
            }
        }

        if ($courseId = $request->query->get('course')) {
            $course = $this->courseRepository->find($courseId);
            if ($course) {
                $this->frontService->simulateSandboxPurchase($user, $course);
                return $this->redirectToRoute('front_course_show', ['id' => $course->getId()]);
            }
        }

        throw $this->createNotFoundException();
    }

    // -----------------------------
    /**
     * List all certifications for the current user
     *
     * @return Response Rendered certifications page
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
}
