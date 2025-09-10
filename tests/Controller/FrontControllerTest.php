<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Lesson;
use App\Entity\Purchase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\TestHelpers;

class FrontControllerTest extends WebTestCase
{
    use TestHelpers;
    
    protected function setUp(): void
    {
        $this->initTest(); // ✅ initialise $client et $em avant chaque test
    }

    public function testAccessLesson(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        $lesson = $this->em->getRepository(Lesson::class)->findOneBy([]);
        $this->purchaseLesson($user, $lesson, 100);

        $this->client->request('GET', '/lessons/'.$lesson->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $lesson->getTitle());
    }

    public function testCertificationsPage(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        $this->client->request('GET', '/certifications');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.certification-list');
    }
}
