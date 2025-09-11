<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\Back\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/user', name: 'admin_user_')]
#[IsGranted('ROLE_ADMIN')]
/**
 * Administration controller for managing users.
 *
 * This controller allows administrators (ROLE_ADMIN) to perform CRUD operations
 * on User entities: list, create, read, update, and delete.
 */
final class UserController extends AbstractController
{
    /**
     * Lists all registered users.
     *
     * @param UserRepository $userRepository Repository to access User entities
     * @return Response HTTP response rendering the list of users
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('back/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * Creates a new user.
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
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * Shows details of a user.
     *
     * @param User $user User entity automatically injected
     * @return Response HTTP response rendering user details
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('back/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Edits an existing user.
     *
     * Displays an edit form and, if submitted and valid,
     * updates the entity in the database.
     *
     * @param Request $request HTTP request containing form data
     * @param User $user User entity to edit
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @return Response HTTP response rendering the form or redirecting to the index
     */
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * Deletes a user.
     *
     * Checks CSRF token validity before removing the entity
     * from the database.
     *
     * @param Request $request HTTP request containing the CSRF token
     * @param User $user User entity to delete
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @return Response HTTP response redirecting to the index after deletion
     */
    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
