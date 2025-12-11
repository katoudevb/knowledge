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
 * Controller handling front-end actions such as displaying lessons,
 * courses, themes, handling purchases, and Stripe checkouts.
 */
class FrontController extends AbstractController
{
    public function __construct(
        private LessonRepository $lessonRepository,
        private CourseRepository $courseRepository,
        private FrontService $frontService,
        private ThemeService $themeService
    ) {}

    /**
     * Get the currently logged-in user.
     *
     * @throws AccessDeniedException If no user is logged in
     * @return User The current user
     */
    private function getCurrentUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException("L'utilisateur doit être connecté.");
        }
        return $user;
    }

    /**
     * Simulate a sandbox purchase for a lesson.
     *
     * @param Lesson $lesson The lesson to purchase
     * @return Response Redirects to the lesson page
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
     * Simulate a sandbox purchase for a course.
     *
     * @param Course $course The course to purchase
     * @return Response Redirects to the course page
     */
    #[Route('/front/course/{id}/purchase', name: 'front_course_purchase')]
    public function purchaseCourse(Course $course): Response
    {
        $user = $this->getCurrentUser();
        $this->frontService->simulateSandboxPurchase($user, $course);

        $this->addFlash('success', "Cours '{$course->getTitle()}' acheté !");
        return $this->redirectToRoute('front_course_show', ['id' => $course->getId()]);
    }

    /**
     * Create a Stripe checkout session for a lesson purchase.
     *
     * @param Lesson $lesson The lesson to purchase
     * @param Request $request The current request
     * @return Response JSON response for AJAX or redirect to Stripe checkout
     */
    #[Route('/front/lesson/{id}/checkout', name: 'stripe_checkout_lesson', methods: ['POST'])]
    public function stripeCheckoutLesson(Lesson $lesson, Request $request): Response
    {
        $user = $this->getCurrentUser();
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
            'success_url' => $this->generateUrl('front_lesson_show', ['id' => $lesson->getId()], true),
            'cancel_url' => $this->generateUrl('front_lesson_show', ['id' => $lesson->getId()], true),
        ]);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['id' => $session->id]);
        }

        return new RedirectResponse($session->url);
    }

    /**
     * Create a Stripe checkout session for a course purchase.
     *
     * @param Course $course The course to purchase
     * @param Request $request The current request
     * @return Response JSON response for AJAX or redirect to Stripe checkout
     */
    #[Route('/front/course/{id}/checkout', name: 'stripe_checkout_course', methods: ['POST'])]
    public function stripeCheckoutCourse(Course $course, Request $request): Response
    {
        $user = $this->getCurrentUser();
        $stripe = new StripeClient($_ENV['STRIPE_SECRET_KEY']);

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
            'success_url' => $this->generateUrl('front_course_show', ['id' => $course->getId()], true),
            'cancel_url' => $this->generateUrl('front_theme_courses', ['themeId' => $course->getTheme()->getId()], true),
        ]);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['id' => $session->id]);
        }

        return new RedirectResponse($session->url);
    }

    /**
     * Display a lesson page.
     *
     * @param Lesson $lesson The lesson to display
     * @param PurchaseRepository $purchaseRepository Repository to check purchases
     * @return Response Rendered lesson template
     */
    #[Route('/front/lesson/{id}', name: 'front_lesson_show')]
    public function showLesson(Lesson $lesson, PurchaseRepository $purchaseRepository): Response
    {
        $user = $this->getCurrentUser();
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
            'isCoursePurchased' => $isCoursePurchased,
        ]);
    }

    /**
     * Display a course page.
     *
     * @param Course $course The course to display
     * @param PurchaseRepository $purchaseRepository Repository to check purchases
     * @return Response Rendered course template
     */
    #[Route('/front/course/{id}', name: 'front_course_show')]
    public function showCourse(Course $course, PurchaseRepository $purchaseRepository): Response
    {
        $user = $this->getCurrentUser();

        $isCoursePurchased = $purchaseRepository->findOneBy([
            'user' => $user,
            'course' => $course
        ]) !== null;

        $purchasedLessonIds = [];
        foreach ($user->getUserLessons() as $userLesson) {
            $lesson = $userLesson->getLesson();
            if ($lesson->getCourse() === $course) {
                $purchasedLessonIds[] = $lesson->getId();
            }
        }

        return $this->render('front/lessons.html.twig', [
            'course' => $course,
            'isCoursePurchased' => $isCoursePurchased,
            'purchasedLessonIds' => $purchasedLessonIds,
            'stripePublicKey' => $_ENV['STRIPE_PUBLIC_KEY'],
        ]);
    }

    /**
     * Display all available themes.
     *
     * @return Response Rendered themes template
     */
    #[Route('/front/themes', name: 'front_themes')]
    public function themes(): Response
    {
        $themes = $this->themeService->getAllThemes();
        return $this->render('front/themes.html.twig', ['themes' => $themes]);
    }

    /**
     * List the current user's certifications.
     *
     * @return Response Rendered certifications template
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
     * Display all courses for a specific theme.
     *
     * @param int $themeId Theme ID
     * @return Response Rendered theme courses template
     */
    #[Route('/front/theme/{themeId}/courses', name: 'front_theme_courses')]
    public function themeCourses(int $themeId): Response
    {
        $theme = $this->themeService->getThemeWithCourses($themeId);

        return $this->render('front/theme_courses.html.twig', [
            'theme' => $theme,
            'stripePublicKey' => $_ENV['STRIPE_PUBLIC_KEY'],
        ]);
    }

    /**
     * Display the lessons of a course, including purchased lessons.
     *
     * @param Course $course The course to display
     * @param PurchaseRepository $purchaseRepository Repository to check purchases
     * @return Response Rendered lessons template
     */
    #[Route('/front/course/{id}/lessons', name: 'front_course_lessons')]
    public function showCourseLessons(Course $course, PurchaseRepository $purchaseRepository): Response
    {
        $user = $this->getCurrentUser();

        $isCoursePurchased = $purchaseRepository->findOneBy([
            'user' => $user,
            'course' => $course
        ]) !== null;

        $purchasedLessonIds = [];
        foreach ($course->getLessons() as $lesson) {
            $purchase = $purchaseRepository->findOneBy([
                'user' => $user,
                'lesson' => $lesson
            ]);

            if ($purchase) {
                $purchasedLessonIds[] = $lesson->getId();
            }
        }

        return $this->render('front/lessons.html.twig', [
            'course' => $course,
            'isCoursePurchased' => $isCoursePurchased,
            'purchasedLessonIds' => $purchasedLessonIds,
            'stripePublicKey' => $_ENV['STRIPE_PUBLIC_KEY'],
        ]);
    }

    /**
     * Validate a lesson for the current user.
     *
     * @param Lesson $lesson The lesson to validate
     * @return Response Redirects to the lesson page
     */
    #[Route('/front/lesson/{id}/validate', name: 'front_lesson_validate', methods: ['POST'])]
    public function validateLesson(Lesson $lesson): Response
    {
        $user = $this->getCurrentUser();
        $this->frontService->validateLesson($user, $lesson);

        $this->addFlash('success', "Leçon '{$lesson->getTitle()}' validée !");
        return $this->redirectToRoute('front_lesson_show', ['id' => $lesson->getId()]);
    }

    /**
     * Display the user's dashboard with accessible courses and lessons.
     *
     * @return Response Rendered dashboard template
     */
    #[Route('/front/dashboard', name: 'front_dashboard')]
    public function dashboard(): Response
    {
        $user = $this->getCurrentUser();
        $themes = $this->themeService->getAllThemes();

        foreach ($themes as $theme) {
            $accessibleCourses = [];
            foreach ($theme->getCourses() as $course) {
                $hasAccess = $this->frontService->userHasAccessToCourse($user, $course);

                if (!$hasAccess) {
                    continue;
                }

                $lessons = [];
                foreach ($course->getLessons() as $lesson) {
                    $lessons[] = [
                        'entity' => $lesson,
                        'hasAccess' => $this->frontService->userHasAccessToLesson($user, $lesson),
                    ];
                }

                $accessibleCourses[] = [
                    'entity' => $course,
                    'lessons' => $lessons,
                    'hasAccess' => $hasAccess,
                ];
            }

            $theme->setAccessibleCourses($accessibleCourses);
        }

        return $this->render('front/dashboard.html.twig', [
            'user' => $user,
            'themes' => $themes,
        ]);
    }
}
