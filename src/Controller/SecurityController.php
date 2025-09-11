<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controller responsible for user authentication.
 *
 * Handles user login and logout functionality.
 */
class SecurityController extends AbstractController
{
    /**
     * Displays the login page.
     *
     * Retrieves the last entered username and any authentication error
     * to display in the login form.
     *
     * @param AuthenticationUtils $authenticationUtils Service to retrieve login information
     * @return Response HTTP response rendering the login form
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Retrieve any login error if exists
        $error = $authenticationUtils->getLastAuthenticationError();
        // Retrieve the last entered username
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * Logs out the user.
     *
     * This method is intercepted automatically by Symfony's firewall.
     * It must not contain any executable code.
     *
     * @return void
     *
     * @throws \LogicException Always thrown to indicate this method is intercepted
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
