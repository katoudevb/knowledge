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
/**
 * Administration controller for lessons.
 *
 * This controller allows administrators (ROLE_ADMIN) to manage
 * Lesson entities: listing, creation, edition, and deletion.
 */
final class LessonController extends AbstractController
{
    /**
     * Lists all available lessons.
     *
     * @param LessonRepository $lessonRepository Repository to access Lesson entities
     * @return Response HTTP response rendering the list of lessons
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(LessonRepository $lessonRepository): Response
    {
        return $this->render('back/lesson/index.html.twig', [
            'lessons' => $lessonRepository->findAll(),
        ]);
    }

    /**
     * Creates a new lesson.
     *
     * Displays a creation form and, if valid, persists the entity to the database.
     *
     * @param Request $request HTTP request containing the form data
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @return Response HTTP response rendering the form or redirecting to the index
     */
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

    /**
     * Shows details of a lesson.
     *
     * @param Lesson $lesson The Lesson entity automatically injected
     * @return Response HTTP response rendering the lesson details
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Lesson $lesson): Response
    {
        return $this->render('back/lesson/show.html.twig', [
            'lesson' => $lesson,
        ]);
    }

    /**
     * Edits an existing lesson.
     *
     * Displays an edit form and, if valid, updates the entity in the database.
     *
     * @param Request $request HTTP request containing the form data
     * @param Lesson $lesson Lesson entity to be edited
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @return Response HTTP response rendering the form or redirecting to the index
     */
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

    /**
     * Deletes a lesson.
     *
     * Checks the validity of the CSRF token before removing the entity
     * from the database.
     *
     * @param Request $request HTTP request containing the CSRF token
     * @param Lesson $lesson Lesson entity to delete
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @return Response HTTP response redirecting to the index after deletion
     */
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
