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
        // Create a user and log them in
        $user = $this->createUser();
        $this->client->loginUser($user);

        // Create a lesson along with its theme and course
        $lesson = $this->createLessonWithTheme(
            'Test Lesson', 50, 'Theme Test', 'Test Course'
        );

        // Purchase the full course to grant access to all lessons
        $this->purchaseCourse($user, $lesson->getCourse(), $lesson->getCourse()->getPrice());

        // Access the lesson page and follow redirections
        $crawler = $this->client->request('GET', '/lessons/'.$lesson->getId());
        while ($this->client->getResponse()->isRedirection()) {
            $crawler = $this->client->followRedirect();
        }

        $this->assertResponseIsSuccessful();
        // Vérifie maintenant le titre du cours dans le h1
        $this->assertSelectorTextContains('h1', $lesson->getCourse()->getTitle());
    }

    /**
     * Tests the display of the certifications page
     */
    public function testCertificationsPage(): void
    {
        // Create a user and log them in
        $user = $this->createUser();
        $this->client->loginUser($user);

        // Access the certifications page
        $this->client->request('GET', '/certifications');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.certification-list');
    }
}
