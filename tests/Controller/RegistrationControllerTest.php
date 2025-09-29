<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\TestHelpers;

class RegistrationControllerTest extends WebTestCase
{
    use TestHelpers;

    protected UserRepository $userRepository;

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

    private function generateUniqueEmail(): string
    {
        return 'user_' . uniqid('', true) . '@example.com';
    }

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
