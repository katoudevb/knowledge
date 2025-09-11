<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional tests to ensure admin routes are protected.
 */
class UserControllerSecurityTest extends WebTestCase
{
    public function testAdminAccess(): void
    {
        $client = static::createClient();
        $em = static::getContainer()->get('doctrine')->getManager();

        // --- Créer et persister un admin ---
        $admin = new User();
        $admin->setEmail('admin@example.com')
              ->setPassword(password_hash('password', PASSWORD_BCRYPT))
              ->setRoles(['ROLE_ADMIN']);
        $em->persist($admin);
        $em->flush();

        $client->loginUser($admin);
        $client->request('GET', '/admin/user');
        $this->assertResponseIsSuccessful();

        // --- Créer et persister un utilisateur normal ---
        $user = new User();
        $user->setEmail('user@example.com')
             ->setPassword(password_hash('password', PASSWORD_BCRYPT))
             ->setRoles(['ROLE_CLIENT']);
        $em->persist($user);
        $em->flush();

        $client->loginUser($user);
        $client->request('GET', '/admin/user');
        $this->assertResponseStatusCodeSame(403);
    }
}
