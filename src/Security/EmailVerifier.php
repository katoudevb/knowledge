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
        // Generate a signed URL for email confirmation
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            (string) $user->getId(),
            (string) $user->getEmail(),
            ['id' => $user->getId()]
        );

        // Add signature information to the email context
        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        // Send the email
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
        // Validate the email using the signed URL
        $this->verifyEmailHelper->validateEmailConfirmationFromRequest(
            $request,
            (string) $user->getId(),
            (string) $user->getEmail()
        );

        // Mark the user as verified
        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
