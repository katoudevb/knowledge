<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\TestHelpers;

/**
 * Functional tests for the RegistrationController.
 *
 * Verifies:
 * - User registration workflow
 * - Database creation of a new user
 * - Default verification status for newly registered users
 */
class RegistrationControllerTest extends WebTestCase
{
    use TestHelpers;

    /**
     * @var UserRepository Repository to fetch User entities
     */
    protected UserRepository $userRepository;

    /**
     * Initialize the test client, entity manager, and repositories.
     * Purges related entities to ensure a clean database state before each test.
     */
    protected function setUp(): void
    {
        $this->initTest();

        $this->userRepository = static::getContainer()->get(UserRepository::class);

        // Purge des entités pour un état propre
        $this->em->createQuery('DELETE FROM App\Entity\UserLesson ul')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Purchase p')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User u')->execute();
        $this->em->flush();
    }

    /**
     * Generates a unique email address for testing purposes.
     *
     * @return string Unique email address
     */
    private function generateUniqueEmail(): string
    {
        return 'user_' . uniqid('', true) . '@example.com';
    }

    /**
     * Tests the user registration process.
     *
     * Steps:
     * - Access the registration form
     * - Submit the form with a valid password
     * - Assert redirection after submission
     * - Verify that the user is created in the database
     * - Verify that the user is not verified by default
     */
    public function testRegister(): void
    {
        $email = $this->generateUniqueEmail();

        // Accède au formulaire d’inscription
        $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();

        // Soumet le formulaire avec un mot de passe valide
        $this->client->submitForm("S'inscrire", [
            'registration_form[email]' => $email,
            'registration_form[plainPassword]' => 'Password1!',
            'registration_form[agreeTerms]' => true,
        ]);

        // Vérifie la redirection
        self::assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        // Vérifie que l’utilisateur a bien été créé
        $user = $this->userRepository->findOneBy(['email' => $email]);
        self::assertNotNull($user, "L'utilisateur doit être créé en base");
        self::assertFalse($user->isVerified(), "L'utilisateur ne doit pas être vérifié par défaut");
    }
}
