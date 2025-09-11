<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\TestHelpers;

/**
 * Functional tests for the RegistrationController.
 *
 * Verifies user registration and email verification workflow.
 */
class RegistrationControllerTest extends WebTestCase
{
    use TestHelpers;

    /**
     * Repository to access User entities from the database.
     */
    protected UserRepository $userRepository;

    /**
     * Sets up the test environment before each test.
     *
     * - Initializes $client and $em via the TestHelpers trait.
     * - Retrieves the UserRepository.
     * - Cleans User, UserLesson, and Purchase entities for a clean state.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->initTest();

        $this->userRepository = static::getContainer()->get(UserRepository::class);

        $this->em->createQuery('DELETE FROM App\Entity\UserLesson ul')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Purchase p')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User u')->execute();
        $this->em->flush();
    }

    /**
     * Generates a unique email for registration testing.
     *
     * @return string Unique email address
     */
    private function generateUniqueEmail(): string
    {
        return 'user_' . uniqid('', true) . '@example.com';
    }

    /**
     * Tests the full registration process for a new user.
     *
     * Scenario:
     * - Generate a unique email.
     * - Access the registration form.
     * - Submit the form with email, password, and agreement to terms.
     * - Check that the user is created and not yet verified.
     * - Verify that the confirmation email is sent.
     * - Extract and follow the verification link.
     * - Confirm that the user is marked as verified after validation.
     *
     * @return void
     */
    public function testRegister(): void
    {
        $email = $this->generateUniqueEmail();

        // Access the registration form
        $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Inscription');

        // Submit the registration form
        $this->client->submitForm("S'inscrire", [
            'registration_form[email]' => $email,
            'registration_form[plainPassword]' => 'password',
            'registration_form[agreeTerms]' => true,
        ]);

        // Verify the user was created
        $users = $this->userRepository->findAll();
        self::assertCount(1, $users);

        /** @var User $user */
        $user = $users[0];
        self::assertFalse($user->isVerified());

        // Verify that a confirmation email was sent
        self::assertEmailCount(1);
        $messages = $this->getMailerMessages();
        /** @var TemplatedEmail $emailMessage */
        $emailMessage = $messages[0];

        self::assertEmailAddressContains($emailMessage, 'from', 'noreply@knowledge.com');
        self::assertEmailAddressContains($emailMessage, 'to', $user->getEmail());
        self::assertEmailTextBodyContains($emailMessage, 'expirera');
        self::assertEmailTextBodyContains($emailMessage, 'Confirmer mon e-mail');

        // Extract the verification link from the HTML email
        $body = $emailMessage->getHtmlBody();
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($body);
        libxml_clear_errors();

        $links = $dom->getElementsByTagName('a');
        $verificationLink = null;
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (str_contains($href, '/verify/email')) {
                $verificationLink = $href;
                break;
            }
        }

        self::assertNotNull($verificationLink, 'Verification link must exist');

        // Access the verification link and follow redirect
        $this->client->request('GET', $verificationLink);
        $this->client->followRedirect();

        // Refresh the entity to get updated isVerified value
        $this->em->refresh($user);

        self::assertTrue($user->isVerified());
    }
}
