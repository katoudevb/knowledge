<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Lesson;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontControllerTest extends WebTestCase
{
    public function testAccessLesson(): void
    {
        $client = static::createClient();

        $user = static::getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneByEmail('testuser@example.com');

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

        $user = static::getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneByEmail('testuser@example.com');

        $client->loginUser($user);
        $client->request('GET', '/certifications');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.certification-list'); // Classe CSS de ton template
    }
}
