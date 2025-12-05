<?php

namespace App\Tests\Unit;

use App\Entity\User;
use App\Entity\Purchase;
use App\Entity\Course;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the User entity.
 */
class UserUnitTest extends TestCase
{
    public function testUserCreation(): void
    {
        $user = new User();
        $user->setEmail('test@example.com')
             ->setPassword('password123');

        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals(['ROLE_CLIENT'], $user->getRoles());
        $this->assertEquals('password123', $user->getPassword());
        $this->assertFalse($user->isVerified());
    }

    public function testPasswordVerification(): void
    {
        $user = new User();
        $hashed = password_hash('secret', PASSWORD_BCRYPT);
        $user->setPassword($hashed);

        $this->assertTrue(password_verify('secret', $user->getPassword()));
        $this->assertFalse(password_verify('wrong', $user->getPassword()));
    }

    public function testAddRemovePurchase(): void
    {
        $user = new User();
        $purchase = new Purchase();

        // Utilisation des méthodes correctes
        $user->addPurchase($purchase);
        $this->assertCount(1, $user->getPurchases());
        $this->assertSame($user, $purchase->getUser());

        $user->removePurchase($purchase);
        $this->assertCount(0, $user->getPurchases());
        $this->assertNull($purchase->getUser());
    }

    public function testAddCertificationFromCourse(): void
    {
        $user = new User();
        $course = new Course();

        $user->addCertificationFromCourse($course);
        $this->assertCount(1, $user->getCertifications());

        // Adding the same course again should not duplicate
        $user->addCertificationFromCourse($course);
        $this->assertCount(1, $user->getCertifications());
    }
}
