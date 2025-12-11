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

        // POST on purchase route
        $this->client->request('POST', '/front/course/'.$course->getId().'/purchase');

        // Check redirection to the course page (not dashboard)
        $this->assertResponseRedirects('/front/course/'.$course->getId());

        // Verify in database
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

        // POST on purchase route
        $this->client->request('POST', '/front/lesson/'.$lesson->getId().'/purchase');

        // Check redirection to the lesson page (updated from dashboard)
        $this->assertResponseRedirects('/front/lesson/'.$lesson->getId());

        // Verify in database
        $purchase = $this->em->getRepository(Purchase::class)
            ->findOneBy([
                'user' => $user,
                'lesson' => $lesson,
            ]);
        $this->assertNotNull($purchase, "La leçon a bien été achetée");
    }
}
