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
 * Administration controller for managing themes.
 *
 * This controller allows administrators (ROLE_ADMIN) to perform CRUD operations
 * on Theme entities: list, create, read, update, and delete.
 */
final class ThemeController extends AbstractController
{
    /**
     * Lists all themes.
     *
     * @param ThemeService $themeService Service to access Theme entities
     * @return Response HTTP response rendering the list of themes
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
     * Displays a creation form and, if submitted and valid,
     * persists the entity to the database.
     *
     * @param Request $request HTTP request containing form data
     * @param ThemeService $themeService Service handling persistence
     * @return Response HTTP response rendering the form or redirecting to the index
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
     * Shows details of a theme.
     *
     * @param Theme $theme Theme entity automatically injected
     * @return Response HTTP response rendering theme details
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
     * Displays an edit form and, if submitted and valid,
     * updates the entity in the database.
     *
     * @param Request $request HTTP request containing form data
     * @param Theme $theme Theme entity to edit
     * @param ThemeService $themeService Service handling persistence
     * @return Response HTTP response rendering the form or redirecting to the index
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
     * Checks CSRF token validity before removing the entity
     * from the database.
     *
     * @param Request $request HTTP request containing the CSRF token
     * @param Theme $theme Theme entity to delete
     * @param ThemeService $themeService Service handling deletion
     * @return Response HTTP response redirecting to the index after deletion
     */
    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Theme $theme, ThemeService $themeService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$theme->getId(), $request->request->get('_token'))) {
            $themeService->deleteTheme($theme);
        }

        return $this->redirectToRoute('admin_theme_index', [], Response::HTTP_SEE_OTHER);
    }
}
