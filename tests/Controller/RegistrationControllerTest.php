<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class RegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();

        $this->em = $container->get('doctrine')->getManager();
        $this->userRepository = $container->get(UserRepository::class);

        // Supprime tous les utilisateurs pour un état propre
        foreach ($this->userRepository->findAll() as $user) {
            $this->em->remove($user);
        }
        $this->em->flush();
    }

    public function testRegister(): void
    {
        // Accéder au formulaire d'inscription
        $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Inscription'); // corrigé selon ton template

        // Soumettre le formulaire
        $this->client->submitForm("S'inscrire", [
            'registration_form[email]' => 'me@example.com',
            'registration_form[plainPassword]' => 'password',
            'registration_form[agreeTerms]' => true,
        ]);

        // Vérifie que l'utilisateur a été créé
        $users = $this->userRepository->findAll();
        self::assertCount(1, $users);

        $user = $users[0];
        self::assertFalse($user->isVerified());

        // Vérifie que l'email de vérification a été envoyé
        self::assertEmailCount(1);
        $messages = $this->getMailerMessages();
        /** @var TemplatedEmail $email */
        $email = $messages[0];

        self::assertEmailAddressContains($email, 'from', 'noreply@knowledge.com');
        self::assertEmailAddressContains($email, 'to', 'me@example.com');
        self::assertEmailTextBodyContains($email, 'This link will expire in 1 hour.');

        // Suivre le lien de vérification
        $this->client->followRedirects(true);
        $body = $email->getHtmlBody();
        self::assertIsString($body);

        preg_match('#(http://localhost/verify/email.+)">#', $body, $matches);
        $verificationLink = $matches[1] ?? null;
        self::assertNotNull($verificationLink, 'Le lien de vérification doit exister');

        $this->client->request('GET', $verificationLink);

        // Vérifie que l'utilisateur est maintenant vérifié
        $userVerified = $this->userRepository->findAll()[0];
        self::assertTrue($userVerified->isVerified());
    }
}
