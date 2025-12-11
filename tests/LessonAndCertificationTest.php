<?php
namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Lesson;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\TestHelpers;

/**
 * Functional tests for lesson purchase, validation, 
 * and the generation of associated certifications.
 */
class LessonAndCertificationTest extends WebTestCase
{
    use TestHelpers;

    protected function setUp(): void
    {
        $this->initTest();
    }

    /**
     * Tests that a user can purchase a lesson.
     */
    public function testPurchaseLesson(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        $lesson = $this->createLessonWithTheme(
            'Test Lesson',   // lesson title
            50,              // price
            'Theme Test',    // theme name
            'Test Course'    // course title
        );

        // Simulate lesson purchase
        $this->purchaseLesson($user, $lesson, 50);

        // Call the correct purchase route
        $this->client->request('GET', '/front/lesson/'.$lesson->getId().'/purchase');

        // Check redirection to the lesson page (not dashboard)
        $this->assertResponseRedirects('/front/lesson/'.$lesson->getId());

        // Verify that the purchase is recorded
        $this->em->refresh($lesson);
        $hasPurchase = false;
        foreach ($lesson->getPurchases() as $p) {
            if ($p->getUser() === $user) {
                $hasPurchase = true;
                break;
            }
        }
        $this->assertTrue($hasPurchase, 'The lesson purchase must be recorded');
    }

    /**
     * Tests the validation of a lesson and the generation of a UserLesson entity.
     */
    public function testValidateLessonAndCertification(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        $lesson = $this->createLessonWithTheme(
            'Test Lesson',   // lesson title
            50,              // price
            'Theme Test',    // theme name
            'Test Course'    // course title
        );

        // POST to the validation route
        $this->client->request('POST', '/front/lesson/'.$lesson->getId().'/validate');

        // Check redirection to the lesson page (updated from dashboard)
        $this->assertResponseRedirects('/front/lesson/'.$lesson->getId());

        // Verify that UserLesson is created and validated
        $userLesson = $this->em->getRepository(\App\Entity\UserLesson::class)
                               ->findOneBy(['user' => $user, 'lesson' => $lesson]);
        $this->assertNotNull($userLesson, 'A UserLesson should be created');
        $this->assertTrue($userLesson->isValidated(), 'The lesson must be validated');
    }
}
