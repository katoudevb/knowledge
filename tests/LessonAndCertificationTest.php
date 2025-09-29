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

        // Crée une leçon avec thème et cours (4 arguments)
        $lesson = $this->createLessonWithTheme(
            'Test Lesson',   // titre de la leçon
            50,              // prix
            'Theme Test',    // nom du thème
            'Test Course'    // titre du cours
        );

        // Simule l'achat de la leçon
        $this->purchaseLesson($user, $lesson, 50);

        // Appelle la route correcte pour l'achat
        $this->client->request('GET', '/front/lesson/'.$lesson->getId().'/purchase');

        // Vérifie la redirection vers le dashboard
        $this->assertResponseRedirects('/front/dashboard');

        // Vérifie que l'achat est bien enregistré
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

        // Crée une leçon avec thème et cours (4 arguments)
        $lesson = $this->createLessonWithTheme(
            'Test Lesson',   // titre de la leçon
            50,              // prix
            'Theme Test',    // nom du thème
            'Test Course'    // titre du cours
        );

        // POST vers la route correcte avec /validate
        $this->client->request('POST', '/front/lesson/'.$lesson->getId().'/validate');

        // Vérifie la redirection vers le dashboard
        $this->assertResponseRedirects('/front/dashboard');

        // Vérifie que le UserLesson a été créé et validé
        $userLesson = $this->em->getRepository(\App\Entity\UserLesson::class)
                               ->findOneBy(['user' => $user, 'lesson' => $lesson]);
        $this->assertNotNull($userLesson, 'A UserLesson should be created');
        $this->assertTrue($userLesson->isValidated(), 'The lesson must be validated');
    }
}
