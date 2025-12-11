<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\User;
use App\Service\FrontService;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    public function __construct(private FrontService $frontService) {}

    // ------------------ CHECKOUT COURSE ------------------
    #[Route('/stripe/checkout/course/{id}', name: 'stripe_checkout_course', methods: ['POST'])]
    public function checkoutCourse(Course $course): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->json(['error' => 'Utilisateur non connecté'], 403);
        }

        if (!$course->getPrice() || $course->getPrice() <= 0) {
            return $this->json(['error' => "Ce cours n'a pas de prix défini.."], 400);
        }

        try {
            Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
            $amount = (int) round($course->getPrice() * 100);

            $session = Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'customer_email' => $user->getEmail(),
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $amount,
                        'product_data' => ['name' => $course->getTitle()],
                    ],
                    'quantity' => 1,
                ]],
                'success_url' => $this->generateUrl('stripe_success_course', ['id' => $course->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('front_course_show', ['id' => $course->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            return $this->json(['id' => $session->id]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur de bande : ' . $e->getMessage()], 500);
        }
    }

    // ------------------ CHECKOUT LESSON ------------------
    #[Route('/stripe/checkout/lesson/{id}', name: 'stripe_checkout_lesson', methods: ['POST'])]
    public function checkoutLesson(Lesson $lesson): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->json(['error' => 'Utilisateur non connecté'], 403);
        }

        if (!$lesson->getPrice() || $lesson->getPrice() <= 0) {
            return $this->json(['error' => "Ce cours n'a pas de prix défini."], 400);
        }

        try {
            Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
            $amount = (int) round($lesson->getPrice() * 100);

            $session = Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'customer_email' => $user->getEmail(),
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $amount,
                        'product_data' => ['name' => $lesson->getTitle()],
                    ],
                    'quantity' => 1,
                ]],
                'success_url' => $this->generateUrl('stripe_success_lesson', ['id' => $lesson->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('front_lesson_show', ['id' => $lesson->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            return $this->json(['id' => $session->id]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur de bande : ' . $e->getMessage()], 500);
        }
    }

    // ------------------ SUCCESS COURSE ------------------
    #[Route('/stripe/success/course/{id}', name: 'stripe_success_course')]
    public function successCourse(Course $course): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $this->frontService->simulateSandboxPurchase($user, $course);
        $this->addFlash('success', "Achat du cours confirmé !");
        return $this->redirectToRoute('front_course_show', ['id' => $course->getId()]);
    }

    // ------------------ SUCCESS LESSON ------------------
    #[Route('/stripe/success/lesson/{id}', name: 'stripe_success_lesson')]
    public function successLesson(Lesson $lesson): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $this->frontService->simulateSandboxPurchase($user, $lesson);
        $this->addFlash('success', "Achat de la leçon confirmé !");
        return $this->redirectToRoute('front_lesson_show', ['id' => $lesson->getId()]);
    }
}
