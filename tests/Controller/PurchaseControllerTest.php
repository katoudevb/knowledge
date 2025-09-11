<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\TestHelpers;

/**
 * Functional tests for the PurchaseController.
 *
 * Verifies course purchases and related redirections for users.
 */
class PurchaseControllerTest extends WebTestCase
{
    use TestHelpers;
    
    /**
     * Sets up the client and EntityManager before each test.
     * Calls the initTest() method from the TestHelpers trait.
     */
    protected function setUp(): void
    {
        $this->initTest(); 
    }

    /**
     * Tests purchasing a course by a logged-in user.
     *
     * Scenario:
     * - Create a user.
     * - Log in the user.
     * - Retrieve a random course from the database.
     * - Simulate the course purchase.
     * - Access the course purchase confirmation page.
     *
     * Assertions:
     * - Ensures the response redirects to the themes page.
     *
     * @return void
     */
    public function testPurchaseCourse(): void
    {
        // Create and log in the user
        $user = $this->createUser();
        $this->client->loginUser($user);

        // Retrieve a random course from the database
        $course = $this->em->getRepository(Course::class)->findOneBy([]);

        // Simulate the course purchase
        $this->purchaseCourse($user, $course, 100);

        // Access the course purchase page
        $this->client->request('GET', '/purchase/course/'.$course->getId());

        // Assertion: check redirection to themes page
        $this->assertResponseRedirects('/themes');
    }
}
