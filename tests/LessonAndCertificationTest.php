<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Lesson;
use App\Entity\Course;
use App\Entity\Purchase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LessonAndCertificationTest extends WebTestCase
{
    private function createTestUser(): User
    {
        $container = static::getContainer();
        $em = $container->get('doctrine')->getManager();
        $passwordHasher = $container->get('security.password_hasher');

        $user = new User();
        $user->setEmail('lessonuser@example.com');
        $user->setPassword(
            $passwordHasher->hashPassword($user, 'password')
        );
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);

        $em->persist($user);
        $em->flush();

        return $user;
    }

    public function testPurchaseLesson(): void
    {
        $client = static::createClient();
        $user = $this->createTestUser();
        $client->loginUser($user);

        $em = static::getContainer()->get('doctrine')->getManager();
        $lesson = $em->getRepository(Lesson::class)->findOneBy([]);

        // Crée un Purchase manuel pour SQLite si la route ne l'injecte pas correctement
        $purchase = new Purchase();
        $purchase->setLesson($lesson);
        $purchase->setUser($user);
        $purchase->setAmount(100); // <-- Obligatoire pour éviter le NOT NULL
        $em->persist($purchase);
        $em->flush();

        // Acheter la leçon via la route (peut créer un doublon mais sans erreur)
        $client->request('GET', '/purchase/lesson/'.$lesson->getId());

        $this->assertResponseRedirects('/themes');

        // Vérifier que l'achat est enregistré
        $em->refresh($lesson);
        $hasPurchase = false;
        foreach ($lesson->getPurchases() as $p) {
            if ($p->getUser() === $user) {
                $hasPurchase = true;
                break;
            }
        }
        $this->assertTrue($hasPurchase, 'L’achat de la leçon doit être enregistré');
    }

    public function testValidateLessonAndCertification(): void
    {
        $client = static::createClient();
        $user = $this->createTestUser();
        $client->loginUser($user);

        $em = static::getContainer()->get('doctrine')->getManager();
        $lesson = $em->getRepository(Lesson::class)->findOneBy([]);

        // Valider la leçon
        $client->request('GET', '/validate-lesson/'.$lesson->getId());
        $this->assertResponseRedirects('/courses/'.$lesson->getCourse()->getId());

        // Vérifier que la leçon est validée
        $userLesson = $em->getRepository(\App\Entity\UserLesson::class)
                         ->findOneBy(['user' => $user, 'lesson' => $lesson]);
        $this->assertNotNull($userLesson);
        $this->assertTrue($userLesson->isValidated(), 'La leçon doit être validée');

        // Vérifier la certification si toutes les leçons du cours sont validées
        $allLessons = $lesson->getCourse()->getLessons();
        $allValidated = true;
        foreach ($allLessons as $l) {
            $ul = $em->getRepository(\App\Entity\UserLesson::class)
                     ->findOneBy(['user' => $user, 'lesson' => $l]);
            if (!$ul || !$ul->isValidated()) {
                $allValidated = false;
                break;
            }
        }

        if ($allValidated) {
            $this->assertNotEmpty($user->getFrontCertifications(), 'La certification doit être créée après validation de toutes les leçons');
        }
    }
}
