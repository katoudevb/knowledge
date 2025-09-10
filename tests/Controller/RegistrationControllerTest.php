<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\TestHelpers;

class RegistrationControllerTest extends WebTestCase
{
    use TestHelpers;
    
    protected UserRepository $userRepository;

    protected function setUp(): void
    {
        // Initialise $client et $em depuis le trait
        $this->initTest();

        // Récupérer UserRepository
        $this->userRepository = static::getContainer()->get(UserRepository::class);

        // Purger les entités pour un état propre
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

        // Accéder au formulaire d'inscription
        $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Inscription');

        // Soumettre le formulaire
        $this->client->submitForm("S'inscrire", [
            'registration_form[email]' => $email,
            'registration_form[plainPassword]' => 'password',
            'registration_form[agreeTerms]' => true,
        ]);

        // Vérifie que l'utilisateur a été créé
        $users = $this->userRepository->findAll();
        self::assertCount(1, $users);

        /** @var User $user */
        $user = $users[0];
        self::assertFalse($user->isVerified());

        // Vérifie l'envoi d'email
        self::assertEmailCount(1);
        $messages = $this->getMailerMessages();
        /** @var TemplatedEmail $emailMessage */
        $emailMessage = $messages[0];

        self::assertEmailAddressContains($emailMessage, 'from', 'noreply@knowledge.com');
        self::assertEmailAddressContains($emailMessage, 'to', $user->getEmail());
        self::assertEmailTextBodyContains($emailMessage, 'expirera');
        self::assertEmailTextBodyContains($emailMessage, 'Confirmer mon e-mail');

        // Extraire le lien de vérification
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

        self::assertNotNull($verificationLink, 'Le lien de vérification doit exister');

        // Accède au lien de vérification et suit la redirection
        $this->client->request('GET', $verificationLink);
        $this->client->followRedirect();

        // Rafraîchir l’entité pour récupérer la nouvelle valeur de isVerified
        $this->em->refresh($user);

        self::assertTrue($user->isVerified());
    }
}
