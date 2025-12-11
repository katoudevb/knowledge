<?php

namespace App\Controller\Back;

use App\Entity\Theme;
use App\Form\Back\ThemeType;
use App\Service\ThemeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/theme', name: 'admin_theme_')]
#[IsGranted('ROLE_ADMIN')]
/**
 * Admin controller for managing themes.
 *
 * Allows administrators to perform CRUD operations on Theme entities.
 */
final class ThemeController extends AbstractController
{
    /**
     * Lists all themes.
     *
     * @param ThemeService $themeService
     * @return Response
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(ThemeService $themeService): Response
    {
        return $this->render('back/theme/index.html.twig', [
            'themes' => $themeService->getAllThemes(),
        ]);
    }

    /**
     * Creates a new theme.
     *
     * @param Request $request
     * @param ThemeService $themeService
     * @return Response
     */
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ThemeService $themeService): Response
    {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $themeService->saveTheme($theme);
            return $this->redirectToRoute('admin_theme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/theme/new.html.twig', [
            'theme' => $theme,
            'form' => $form,
        ]);
    }

    /**
     * Shows a theme.
     *
     * @param Theme $theme
     * @return Response
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Theme $theme): Response
    {
        return $this->render('back/theme/show.html.twig', [
            'theme' => $theme,
        ]);
    }

    /**
     * Edits an existing theme.
     *
     * @param Request $request
     * @param Theme $theme
     * @param ThemeService $themeService
     * @return Response
     */
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Theme $theme, ThemeService $themeService): Response
    {
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $themeService->saveTheme($theme);
            return $this->redirectToRoute('admin_theme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/theme/edit.html.twig', [
            'theme' => $theme,
            'form' => $form,
        ]);
    }

    /**
     * Deletes a theme.
     *
     * @param Request $request
     * @param Theme $theme
     * @param ThemeService $themeService
     * @return Response
     */
    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Theme $theme, ThemeService $themeService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$theme->getId(), $request->request->get('_token'))) {
            $themeService->deleteTheme($theme);
        }

        return $this->redirectToRoute('admin_theme_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Lists all courses of a theme in the back-office.
     *
     * @param Theme $theme
     * @return Response
     */
    #[Route('/{id}/courses', name: 'courses', methods: ['GET'])]
    public function courses(Theme $theme): Response
    {
        $courses = $theme->getCourses();

        return $this->render('back/theme/courses.html.twig', [
            'theme' => $theme,
            'courses' => $courses,
        ]);
    }
}
