<?php

namespace App\Controller\Back;

use App\Entity\Course;
use App\Form\Back\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/course', name: 'admin_course_')]
#[IsGranted('ROLE_ADMIN')]
/**
 * Administration controller for managing courses.
 *
 * This controller allows an administrator (ROLE_ADMIN) to perform CRUD operations
 * on the {@see Course} entity: listing, creating, editing, showing, and deleting courses.
 */
final class CourseController extends AbstractController
{
    /**
     * Lists all courses.
     *
     * @param CourseRepository $courseRepository Repository to access Course entities
     * @return Response HTTP response with the rendered view
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('back/course/index.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);
    }

    /**
     * Creates a new course.
     *
     * Displays a form for creating a new course and persists it to the database
     * if the form is submitted and valid.
     *
     * @param Request $request HTTP request containing form data
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @return Response Redirects to the index or renders the creation form
     */
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($course);
            $entityManager->flush();

            return $this->redirectToRoute('admin_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/course/new.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    /**
     * Displays a course details.
     *
     * @param Course $course Course entity automatically injected
     * @return Response HTTP response with the rendered view
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Course $course): Response
    {
        return $this->render('back/course/show.html.twig', [
            'course' => $course,
        ]);
    }

    /**
     * Edits an existing course.
     *
     * Displays a form for editing an existing course and updates it in the database
     * if the form is submitted and valid.
     *
     * @param Request $request HTTP request containing form data
     * @param Course $course Course entity to edit
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @return Response Redirects to the index or renders the edit form
     */
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/course/edit.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    /**
     * Deletes a course.
     *
     * Checks the CSRF token validity before removing the entity from the database.
     *
     * @param Request $request HTTP request containing the CSRF token
     * @param Course $course Course entity to delete
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @return Response Redirects to the index after deletion
     */
    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Course $course, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $entityManager->remove($course);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
