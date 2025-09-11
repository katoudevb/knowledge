<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

/**
 * Controller for user registration and email verification.
 *
 * Handles:
 *  - Registration form submission
 *  - Automatic role assignment (ROLE_CLIENT)
 *  - Password hashing
 *  - Sending confirmation emails
 *  - Verification of email confirmation links
 */
class RegistrationController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param EmailVerifier $emailVerifier Service handling email confirmation
     */
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    /**
     * Handles registration form submission.
     *
     * - Creates a new User entity
     * - Hashes the password
     * - Assigns ROLE_CLIENT automatically
     * - Persists the user in the database
     * - Sends a verification email
     *
     * @param Request $request HTTP request containing form data
     * @param UserPasswordHasherInterface $userPasswordHasher Service to hash passwords
     * @param EntityManagerInterface $entityManager Doctrine entity manager
     * @param MailerInterface $mailer Service to send emails
     *
     * @return Response Renders the registration form or redirects after success
     */
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Hash the plain password before saving
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Assign ROLE_CLIENT automatically
            $user->setRoles(array_merge($user->getRoles(), ['ROLE_CLIENT']));

            $entityManager->persist($user);
            $entityManager->flush();

            // Send confirmation email with signed URL
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('noreply@knowledge.com', 'Knowledge Learning'))
                    ->to((string) $user->getEmail())
                    ->subject('Merci de confirmer votre adresse email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('app_login');
        }

        // Render registration form if not submitted or invalid
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    /**
     * Handles email verification after user clicks the confirmation link.
     *
     * - Validates the user based on the signed URL
     * - Handles exceptions if the link is invalid or expired
     *
     * @param Request $request HTTP request containing verification token
     * @param TranslatorInterface $translator Service for translating messages
     * @param UserRepository $userRepository Repository to retrieve the User entity
     *
     * @return Response Redirects to login after successful verification or back to registration on error
     */
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request $request,
        TranslatorInterface $translator,
        UserRepository $userRepository
    ): Response {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
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

        $this->addFlash('success', 'Votre adresse email a été vérifiée.');

        return $this->redirectToRoute('app_login');
    }
}
