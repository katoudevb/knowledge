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
class FrontController extends AbstractController
{
    // -----------------------------
    // Page d'accueil / liste des thèmes
    // -----------------------------
    #[Route('', name: 'home')]
    #[Route('themes', name: 'themes')]
    public function themes(EntityManagerInterface $em): Response
    {
        $themes = $em->getRepository(Theme::class)->findAll();
        return $this->render('front/themes.html.twig', compact('themes'));
    }

    // -----------------------------
    // Affichage d’un cours et ses leçons
    // -----------------------------
    #[Route('courses/{id}', name: 'courses')]
    public function courses(Course $course): Response
    {
        return $this->render('front/courses.html.twig', compact('course'));
    }

    // -----------------------------
    // Affichage d’une leçon
    // -----------------------------
    #[Route('lessons/{id}', name: 'lessons')]
    public function lessons(Lesson $lesson): Response
    {
        // -----------------------------
        // AJOUT : récupération sécurisée de l'utilisateur et vérification de la leçon
        // -----------------------------
        /** @var User $user */
        $user = $this->getUser();
        $validated = $user->hasValidatedLesson($lesson);

        return $this->render('front/lessons.html.twig', [
            'lesson' => $lesson,
            'validated' => $validated,
        ]);
    }

    // -----------------------------
    // Achat sandbox
    // -----------------------------
    #[Route('purchase/{type}/{id}', name: 'purchase')]
    #[IsGranted('ROLE_USER')]
    public function purchase(EntityManagerInterface $em, string $type, int $id): Response
    {
        // -----------------------------
        // AJOUT : récupération sécurisée de l'utilisateur
        // -----------------------------
        /** @var User $user */
        $user = $this->getUser();

        $item = match($type) {
            'course' => $em->getRepository(Course::class)->find($id),
            'lesson' => $em->getRepository(Lesson::class)->find($id),
            default => null,
        };

        if (!$item) {
            $this->addFlash('error', 'Élément introuvable.');
            return $this->redirectToRoute('front_themes');
        }

        $purchase = new Purchase();
        $purchase->setUser($user)
                 ->setCreatedAt(new \DateTimeImmutable());

        if ($type === 'course') {
            $purchase->setCourse($item);
        } else {
            $purchase->setLesson($item);
        }

        $em->persist($purchase);
        $em->flush();

        $this->addFlash('success', 'Achat simulé avec succès !');
        return $this->redirectToRoute('front_themes');
    }

    // -----------------------------
    // Validation d’une leçon
    // -----------------------------
    #[Route('validate-lesson/{id}', name: 'validate_lesson')]
    #[IsGranted('ROLE_USER')]
    public function validateLesson(EntityManagerInterface $em, Lesson $lesson): Response
    {
        // -----------------------------
        // AJOUT : récupération sécurisée de l'utilisateur
        // -----------------------------
        /** @var User $user */
        $user = $this->getUser();

        $userLesson = $em->getRepository(UserLesson::class)
                         ->findOneBy(['user' => $user, 'lesson' => $lesson]);

        if (!$userLesson) {
            $userLesson = new UserLesson();
            $userLesson->setUser($user)
                       ->setLesson($lesson);
        }

        $userLesson->setValidated(true)
                   ->setValidateAt(new \DateTimeImmutable());

        $em->persist($userLesson);
        $em->flush();

        // -----------------------------
        // AJOUT : Vérification si toutes les leçons du cours sont validées
        // -----------------------------
        $allValidated = true;
        foreach ($lesson->getCourse()->getLessons() as $l) {
            if (!$user->hasValidatedLesson($l)) {
                $allValidated = false;
                break;
            }
        }

        if ($allValidated) {
            $user->addFrontCertificationFromCourse($lesson->getCourse());
            $em->flush();
        }

        $this->addFlash('success', 'Leçon validée !');
        return $this->redirectToRoute('front_courses', ['id' => $lesson->getCourse()->getId()]);
    }

    // -----------------------------
    // Récapitulatif des certifications
    // -----------------------------
    #[Route('certifications', name: 'certifications')]
    #[IsGranted('ROLE_USER')]
    public function certifications(): Response
    {
        // -----------------------------
        // AJOUT : récupération sécurisée de l'utilisateur
        // -----------------------------
        /** @var User $user */
        $user = $this->getUser();
        $certifications = $user->getFrontCertifications();

        return $this->render('front/certifications.html.twig', [
            'certifications' => $certifications,
        ]);
    }
}
