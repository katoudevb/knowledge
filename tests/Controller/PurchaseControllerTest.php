<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\Purchase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\TestHelpers;

/**
 * Functional tests for the PurchaseController.
 *
 * Verifies course and lesson purchases and related redirections for users.
 */
class PurchaseControllerTest extends WebTestCase
{
    use TestHelpers;
    
    protected function setUp(): void
    {
        $this->initTest(); 
    }

    /**
     * Test purchasing a course.
     */
    public function testPurchaseCourse(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        $course = $this->em->getRepository(Course::class)->findOneBy([]);
        $this->purchaseCourse($user, $course, 100);

        $this->client->request('GET', '/front/course/'.$course->getId());
        $this->assertResponseRedirects('/front/dashboard');

        // Vérification via Doctrine
        $purchase = $this->em->getRepository(Purchase::class)
            ->findOneBy([
                'user' => $user,
                'course' => $course,
            ]);
        $this->assertNotNull($purchase, "Le cours a bien été acheté");
    }

    /**
     * Test purchasing a lesson.
     */
    public function testPurchaseLesson(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        $lesson = $this->em->getRepository(Lesson::class)->findOneBy([]);
        $this->purchaseLesson($user, $lesson, 50);

        $this->client->request('GET', '/front/lesson/'.$lesson->getId());
        $this->assertResponseRedirects('/front/dashboard');

        $purchase = $this->em->getRepository(Purchase::class)
            ->findOneBy([
                'user' => $user,
                'lesson' => $lesson,
            ]);
        $this->assertNotNull($purchase, "La leçon a bien été achetée");
    }
}
