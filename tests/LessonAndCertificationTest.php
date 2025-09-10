<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Lesson;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\TestHelpers;

class LessonAndCertificationTest extends WebTestCase
{
    use TestHelpers;

    protected function setUp(): void
    {
        $this->initTest(); // ✅ initialise $client et $em avant chaque test
    }

    
    public function testPurchaseLesson(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        $lesson = $this->em->getRepository(Lesson::class)->findOneBy([]);
        $this->purchaseLesson($user, $lesson, 100);

        $this->client->request('GET', '/purchase/lesson/'.$lesson->getId());
        $this->assertResponseRedirects('/themes');

        $this->em->refresh($lesson);
        $hasPurchase = false;
        foreach ($lesson->getPurchases() as $p) {
            if ($p->getUser() === $user) {
                $hasPurchase = true;
                break;
            }
        }
        $this->assertTrue($hasPurchase, 'L’achat de la leçon doit être enregistré');
    }

    public function testValidateLessonAndCertification(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        $lesson = $this->em->getRepository(Lesson::class)->findOneBy([]);

        $this->client->request('GET', '/validate-lesson/'.$lesson->getId());
        $this->assertResponseRedirects('/courses/'.$lesson->getCourse()->getId());

        $userLesson = $this->em->getRepository(\App\Entity\UserLesson::class)
                               ->findOneBy(['user' => $user, 'lesson' => $lesson]);
        $this->assertNotNull($userLesson);
        $this->assertTrue($userLesson->isValidated(), 'La leçon doit être validée');
    }
}
