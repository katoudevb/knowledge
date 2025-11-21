<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

/**
 * Service for handling email verification and confirmation.
 *
 * Uses SymfonyCasts VerifyEmailBundle to generate signed URLs and verify email confirmations.
 */
class EmailVerifier
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Sends a confirmation email to the user.
     *
     * @param string $verifyEmailRouteName The route name used for email verification
     * @param User $user The user to whom the email is sent
     * @param TemplatedEmail $email A preconfigured email object using Twig
     */
    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        // Générer le lien signé
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            (string) $user->getId(),
            (string) $user->getEmail(),
            ['id' => $user->getId()]
        );

        // Ajouter le contexte complet pour Twig (HTML et texte)
        $email->context([
            'user' => $user,
            'signedUrl' => $signatureComponents->getSignedUrl(),
            'expiresAtMessageKey' => $signatureComponents->getExpirationMessageKey(),
            'expiresAtMessageData' => $signatureComponents->getExpirationMessageData(),
        ]);

        // Ajouter le template texte pour MailHog / clients texte
        $email->textTemplate('registration/confirmation_email.txt.twig');

        // S'assurer que le HTML est utilisé
        $email->htmlTemplate('registration/confirmation_email.html.twig');

        // Envoyer l'email
        $this->mailer->send($email);
    }

    /**
     * Handles email confirmation from an HTTP request.
     *
     * @param Request $request The HTTP request containing verification parameters
     * @param User $user The user whose email is being verified
     *
     * @throws VerifyEmailExceptionInterface If the verification fails
     */
    public function handleEmailConfirmation(Request $request, User $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmationFromRequest(
            $request,
            (string) $user->getId(),
            (string) $user->getEmail()
        );

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
