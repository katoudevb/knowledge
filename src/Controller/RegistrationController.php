<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Service\UserService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

/**
 * Controller responsible for user registration and email verification.
 *
 * This controller handles:
 *  - Display and processing of the registration form
 *  - Delegation of user creation and password hashing to a service
 *  - Sending email verification messages
 *  - Validating email confirmation links
 */
class RegistrationController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param EmailVerifier $emailVerifier Service used to handle email verification
     */
    public function __construct(private EmailVerifier $emailVerifier) {}

    /**
     * Displays and processes the registration form.
     *
     * This method:
     *  - Creates a new User entity
     *  - Delegates password hashing and persistence to the UserService
     *  - Sends an email confirmation message
     *  - Redirects to the login page after successful registration
     *
     * @param Request $request The current HTTP request
     * @param UserService $userService Service handling user creation and password hashing
     * @return Response HTTP response containing the registration form or redirect
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserService $userService): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Delegate user creation and password hashing to the service
            $userService->registerUser($user, $plainPassword);

            // Send email confirmation
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('noreply@knowledge.com', 'Knowledge Learning'))
                    ->to($user->getEmail())
                    ->subject('Please confirm your email address')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            $this->addFlash('success', 'Your account has been created. Please confirm your email address.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    /**
     * Handles email verification after the user clicks the confirmation link.
     *
     * This method:
     *  - Validates the signed email confirmation link
     *  - Handles exceptions for expired or invalid links
     *  - Adds flash messages for success or error
     *  - Redirects the user to the login page after successful verification
     *
     * @param Request $request Current HTTP request containing the verification token
     * @param TranslatorInterface $translator Translator for flash messages
     * @param UserRepository $userRepository Repository to retrieve the User entity
     * @return Response HTTP redirect response
     */
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request $request,
        TranslatorInterface $translator,
        UserRepository $userRepository
    ): Response {
        $id = $request->query->get('id');

        if (!$id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (!$user) {
            return $this->redirectToRoute('app_register');
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash(
                'verify_email_error',
                $translator->trans($exception->getReason(), [], 'VerifyEmailBundle')
            );

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }
}
