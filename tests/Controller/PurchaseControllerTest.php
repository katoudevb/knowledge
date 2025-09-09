<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PurchaseControllerTest extends WebTestCase
{
    private function createTestUser(): User
    {
        $container = static::getContainer();
        $em = $container->get('doctrine')->getManager();
        $passwordHasher = $container->get('security.password_hasher');

        $user = new User();
        $user->setEmail('testuser@example.com');
        $user->setPassword(
            $passwordHasher->hashPassword($user, 'password')
        );
        $user->setRoles(['ROLE_USER']);

        $em->persist($user);
        $em->flush();

        return $user;
    }

    public function testPurchaseCourse(): void
    {
        $client = static::createClient();
        $user = $this->createTestUser();
        $client->loginUser($user);

        $course = static::getContainer()->get('doctrine')
            ->getRepository(Course::class)
            ->findOneBy([]);

        $client->request('GET', '/purchase/course/'.$course->getId());

        $this->assertResponseRedirects('/themes'); // Vérifie la redirection après achat
    }
}
