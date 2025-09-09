<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PurchaseControllerTest extends WebTestCase
{
    public function testPurchaseCourse(): void
    {
        $client = static::createClient();

        // Simule un utilisateur connecté
        $user = static::getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneByEmail('testuser@example.com');

        $client->loginUser($user);

        // Récupère un cours existant
        $course = static::getContainer()->get('doctrine')
            ->getRepository(Course::class)
            ->findOneBy([]);

        $client->request('GET', '/purchase/course/'.$course->getId());
        $this->assertResponseRedirects('/themes');

        $client->followRedirect();
        $this->assertSelectorTextContains('.flash-success', 'Achat simulé avec succès');
    }
}
