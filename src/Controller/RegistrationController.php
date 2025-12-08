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
    public function __construct(private EmailVerifier $emailVerifier) {}

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

            // Send email confirmation with debug
            try {
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address('bricotteaux@alwaysdata.net', 'Knowledge Learning'))
                        ->to($user->getEmail())
                        ->subject('Merci de confirmer votre adresse e-mail')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
            } catch (\Exception $e) {
                // Affiche l'erreur sur la page ou dans les logs
                dump($e->getMessage()); // temporaire pour debug
                $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email : '.$e->getMessage());
            }

            $this->addFlash('success', 'Votre compte a été créé. Veuillez confirmer votre adresse e-mail.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

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

        $this->addFlash('success', 'Votre adresse email a bien été vérifiée !');

        return $this->redirectToRoute('app_login');
    }
}
