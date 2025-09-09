<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Lesson;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerTest extends WebTestCase
{
    private function createUser(?string $email = null): User
    {
        $container = static::getContainer();
        $em = $container->get('doctrine')->getManager();

        $userRepo = $container->get(\App\Repository\UserRepository::class);

        $email = $email ?? 'user_' . uniqid() . '@example.com';

        // Supprime si l’utilisateur existe déjà
        $existing = $userRepo->findOneBy(['email' => $email]);
        if ($existing) {
            $em->remove($existing);
            $em->flush();
        }

        $user = new User();
        $user->setEmail($email)
             ->setPassword(password_hash('password', PASSWORD_BCRYPT));

        $em->persist($user);
        $em->flush();

        return $user;
    }

    public function testAccessLesson(): void
    {
        $client = static::createClient();
        $user = $this->createUser();
        $client->loginUser($user);

        $lesson = static::getContainer()->get('doctrine')
            ->getRepository(Lesson::class)
            ->findOneBy([]);

        $client->request('GET', '/lessons/'.$lesson->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1'); // Vérifie le titre de la leçon
    }

    public function testCertificationsPage(): void
    {
        $client = static::createClient();
        $user = $this->createUser();
        $client->loginUser($user);

        $client->request('GET', '/certifications');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.certification-list'); // Classe CSS de ton template
    }
}
