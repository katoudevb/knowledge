<?php

namespace App\Controller\Back;

use App\Entity\Course;
use App\Form\Back\CourseType;
use App\Service\CourseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/course', name: 'admin_course_')]
#[IsGranted('ROLE_ADMIN')]
/**
 * Admin controller for managing courses.
 *
 * Handles listing, creating, showing, editing, and deleting courses.
 * All business logic is delegated to CourseService.
 */
final class CourseController extends AbstractController
{
    public function __construct(private CourseService $courseService)
    {
    }

    /**
     * Lists all courses.
     *
     * @return Response
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $courses = $this->courseService->getAllCourses();
        return $this->render('back/course/index.html.twig', compact('courses'));
    }

    /**
     * Creates a new course.
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->courseService->createCourse($course);
            return $this->redirectToRoute('admin_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/course/new.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    /**
     * Shows course details.
     *
     * No purchase logic is checked in the back office.
     *
     * @param Course $course
     * @return Response
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
     * @param Request $request
     * @param Course $course
     * @return Response
     */
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->courseService->updateCourse($course);
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
     * @param Request $request
     * @param Course $course
     * @return Response
     */
    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Course $course): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $this->courseService->deleteCourse($course);
        }

        return $this->redirectToRoute('admin_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
