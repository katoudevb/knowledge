<?php

namespace App\Controller\Back;

use App\Entity\Lesson;
use App\Form\Back\LessonType;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/lesson', name: 'admin_lesson_')]
#[IsGranted('ROLE_ADMIN')]
final class LessonController extends AbstractController
{
    // -----------------------------
    // Liste des leçons
    // -----------------------------
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(LessonRepository $lessonRepository): Response
    {
        return $this->render('back/lesson/index.html.twig', [
            'lessons' => $lessonRepository->findAll(),
        ]);
    }

    // -----------------------------
    // Création d’une leçon
    // -----------------------------
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lesson = new Lesson();
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lesson);
            $entityManager->flush();

            return $this->redirectToRoute('admin_lesson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/lesson/new.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
        ]);
    }

    // -----------------------------
    // Affichage d’une leçon
    // -----------------------------
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Lesson $lesson): Response
    {
        return $this->render('back/lesson/show.html.twig', [
            'lesson' => $lesson,
        ]);
    }

    // -----------------------------
    // Édition d’une leçon
    // -----------------------------
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lesson $lesson, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_lesson_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/lesson/edit.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
        ]);
    }

    // -----------------------------
    // Suppression d’une leçon
    // -----------------------------
    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Lesson $lesson, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lesson->getId(), $request->request->get('_token'))) {
            $entityManager->remove($lesson);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_lesson_index', [], Response::HTTP_SEE_OTHER);
    }
}
