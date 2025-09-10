<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\TestHelpers;

class PurchaseControllerTest extends WebTestCase
{
    use TestHelpers;
    
    protected function setUp(): void
    {
        $this->initTest(); 
    }

    public function testPurchaseCourse(): void
    {
        $user = $this->createUser();
        $this->client->loginUser($user);

        $course = $this->em->getRepository(Course::class)->findOneBy([]);
        $this->purchaseCourse($user, $course, 100);

        $this->client->request('GET', '/purchase/course/'.$course->getId());
        $this->assertResponseRedirects('/themes');
    }
}
