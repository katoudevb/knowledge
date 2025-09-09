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
    // Accessible à tous
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
    // Accessible uniquement aux utilisateurs connectés
    // -----------------------------
    #[Route('courses/{id}', name: 'courses')]
    #[IsGranted('ROLE_USER')]
    public function courses(Course $course, ?User $user = null): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // Vérification que l'utilisateur a acheté le cours ou une leçon
        $hasAccess = false;
        foreach ($course->getPurchases() as $purchase) {
            if ($purchase->getUser() === $user) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            $this->addFlash('error', 'Vous devez acheter ce cours pour y accéder.');
            return $this->redirectToRoute('front_themes');
        }

        return $this->render('front/courses.html.twig', compact('course'));
    }

    // -----------------------------
    // Affichage d’une leçon
    // Accessible uniquement aux utilisateurs connectés
    // -----------------------------
    #[Route('lessons/{id}', name: 'lessons')]
    #[IsGranted('ROLE_USER')]
    public function lessons(Lesson $lesson): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $hasAccess = false;

        foreach ($lesson->getPurchases() as $purchase) {
            if ($purchase->getUser() === $user) {
                $hasAccess = true;
                break;
            }
        }

        // Vérification si l'utilisateur a acheté le cours global
        foreach ($lesson->getCourse()->getPurchases() as $purchase) {
            if ($purchase->getUser() === $user) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            $this->addFlash('error', 'Vous n’avez pas accès à cette leçon.');
            return $this->redirectToRoute('front_courses', ['id' => $lesson->getCourse()->getId()]);
        }

        $validated = $user->hasValidatedLesson($lesson);

        return $this->render('front/lessons.html.twig', [
            'lesson' => $lesson,
            'validated' => $validated,
        ]);
    }

    // -----------------------------
    // Achat sandbox : le compte doit être activé
    // -----------------------------
    #[Route('purchase/{type}/{id}', name: 'purchase')]
    #[IsGranted('ROLE_USER')]
    public function purchase(EntityManagerInterface $em, string $type, int $id): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user->getIsVerified()) {
            $this->addFlash('error', 'Vous devez vérifier votre email pour effectuer un achat.');
            return $this->redirectToRoute('front_themes');
        }

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
        $purchase->sandboxPurchase($user, $item); // utilise la méthode sandboxPurchase de l'entité
        $em->persist($purchase);
        $em->flush();

        $this->addFlash('success', 'Achat simulé avec succès !');
        return $this->redirectToRoute('front_themes');
    }

    // -----------------------------
    // Validation d’une leçon par l’utilisateur
    // -----------------------------
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

        $userLesson->setValidated(true)
                   ->setValidateAt(new \DateTimeImmutable());

        $em->persist($userLesson);
        $em->flush();

        // Vérification si toutes les leçons du cours sont validées
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
    // Récapitulatif des certifications de l'utilisateur
    // -----------------------------
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
