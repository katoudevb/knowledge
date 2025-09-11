<?php

namespace App\Controller\Back;

use App\Entity\Theme;
use App\Form\Back\ThemeType;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param ThemeRepository $themeRepository Repository to access Theme entities
     * @return Response HTTP response rendering the list of themes
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(ThemeRepository $themeRepository): Response
    {
        return $this->render('back/theme/index.html.twig', [
            'themes' => $themeRepository->findAll(),
        ]);
    }

    /**
     * Creates a new theme.
     *
     * Displays a creation form and, if submitted and valid,
     * persists the entity to the database.
     *
     * @param Request $request HTTP request containing form data
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @return Response HTTP response rendering the form or redirecting to the index
     */
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($theme);
            $entityManager->flush();

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
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @return Response HTTP response rendering the form or redirecting to the index
     */
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Theme $theme, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
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
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @return Response HTTP response redirecting to the index after deletion
     */
    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Theme $theme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$theme->getId(), $request->request->get('_token'))) {
            $entityManager->remove($theme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_theme_index', [], Response::HTTP_SEE_OTHER);
    }
}