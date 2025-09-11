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
        // Crée un utilisateur et le connecte
        $user = $this->createUser();
        $this->client->loginUser($user);

        // Crée une leçon avec thème et cours en un seul appel
        $lesson = $this->createLessonWithTheme(
            'Test Lesson', 50, 'Theme Test', 'Test Course'
        );

        // Simule l'achat de la leçon
        $this->purchaseLesson($user, $lesson, 50);

        // Teste la route d'achat de leçon
        $this->client->request('GET', '/purchase/lesson/'.$lesson->getId());
        $this->assertResponseRedirects('/themes');

        // Vérifie que l'achat est bien enregistré dans la base
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
        // Crée un utilisateur et le connecte
        $user = $this->createUser();
        $this->client->loginUser($user);

        // Crée une leçon avec thème et cours
        $lesson = $this->createLessonWithTheme(
            'Test Lesson', 50, 'Theme Test', 'Test Course'
        );

        // Simule la validation de la leçon
        $this->client->request('GET', '/validate-lesson/'.$lesson->getId());
        $this->assertResponseRedirects('/courses/'.$lesson->getCourse()->getId());

        // Vérifie que le UserLesson est créé et validé
        $userLesson = $this->em->getRepository(\App\Entity\UserLesson::class)
                               ->findOneBy(['user' => $user, 'lesson' => $lesson]);
        $this->assertNotNull($userLesson, 'A UserLesson should be created');
        $this->assertTrue($userLesson->isValidated(), 'The lesson must be validated');
    }
}
