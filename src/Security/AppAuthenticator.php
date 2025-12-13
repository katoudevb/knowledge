<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Authenticator for form-based login.
 *
 * Handles user authentication, password verification, and post-login redirection
 * based on role or email verification status.
 */
class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    /**
     * @param UrlGeneratorInterface $urlGenerator URL generator for redirections
     */
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * Creates the Passport for authentication.
     *
     * @param Request $request HTTP request containing the login form data
     *
     * @return Passport Contains the user, credentials, and badges (CSRF, etc.)
     */
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');

        // Store the last username for display in the login form
        $request->getSession()->set('_security.last_username', $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    /**
     * Handles redirection after successful authentication.
     *
     * @param Request $request HTTP request
     * @param TokenInterface $token Security token containing the authenticated user
     * @param string $firewallName The firewall name used
     *
     * @return Response|null HTTP redirect response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /** @var User $user */
        $user = $token->getUser();

        // Redirect to previous page if it exists
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        /** @var Session $session */
        $session = $request->getSession();

        // Check if the user's email is verified
        if ($user instanceof User && !$user->isVerified()) {
            $session->getFlashBag()->add('warning', 'Please verify your email before logging in.');
            return new RedirectResponse($this->urlGenerator->generate(self::LOGIN_ROUTE));
        }

        // Redirect based on role
        if ($user instanceof User && in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return new RedirectResponse($this->urlGenerator->generate('admin_dashboard'));
        }

        // Default redirect for normal users
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    /**
     * Returns the URL of the login form.
     *
     * @param Request $request HTTP request
     * @return string URL of the login page
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
