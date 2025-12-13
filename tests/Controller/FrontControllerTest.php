<?php
namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Lesson;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\TestHelpers;

/**
 * Functional tests for the FrontController.
 *
 * Verifies:
 * - Access to lessons for a logged-in user
 * - Display of the certifications page
 */
class FrontControllerTest extends WebTestCase
{
    use TestHelpers;

    /**
     * Initialize the client and EntityManager before each test
     */
    protected function setUp(): void
    {
        $this->initTest();
    }

    /**
     * Tests access to a lesson for a logged-in user
     * and ensures the course title is correctly displayed
     */
    public function testAccessLesson(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        // Create the lesson with its theme and course
        $lesson = $this->createLessonWithTheme('Test Lesson', 50, 'Theme Test', 'Test Course');

        // Purchase the full course to grant access to all lessons
        $this->purchaseCourse($user, $lesson->getCourse(), $lesson->getCourse()->getPrice());

        $url = $this->client->getContainer()->get('router')->generate(
            'front_lesson_show',
            ['id' => $lesson->getId()]
        );

        $crawler = $this->client->request('GET', $url);
        while ($this->client->getResponse()->isRedirection()) {
            $crawler = $this->client->followRedirect();
        }

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $lesson->getTitle());
    }

    /**
     * Tests the display of the certifications page
     */
    public function testCertificationsPage(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        // Create a course and a lesson to generate a certification
        $lesson = $this->createLessonWithTheme('Test Lesson', 50, 'Theme Test', 'Test Course');
        $course = $lesson->getCourse();

        // Simulate the purchase and validation to generate the certification
        $this->purchaseCourse($user, $course, $course->getPrice());
        $this->validateCourse($user, $course);
        
        // Force Doctrine to reload the user
        $this->em->refresh($user);

        // Access the certifications page
        $crawler = $this->client->request('GET', '/front/certifications');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.certification-list'); // The div now exists
    }
}
