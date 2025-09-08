<?php

namespace App\Tests\Controller;

use App\Entity\Lesson;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class LessonControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $lessonRepository;
    private string $path = '/lesson/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->lessonRepository = $this->manager->getRepository(Lesson::class);

        foreach ($this->lessonRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Lesson index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'lesson[title]' => 'Testing',
            'lesson[price]' => 'Testing',
            'lesson[content]' => 'Testing',
            'lesson[course]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->lessonRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Lesson();
        $fixture->setTitle('My Title');
        $fixture->setPrice('My Title');
        $fixture->setContent('My Title');
        $fixture->setCourse('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Lesson');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Lesson();
        $fixture->setTitle('Value');
        $fixture->setPrice('Value');
        $fixture->setContent('Value');
        $fixture->setCourse('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'lesson[title]' => 'Something New',
            'lesson[price]' => 'Something New',
            'lesson[content]' => 'Something New',
            'lesson[course]' => 'Something New',
        ]);

        self::assertResponseRedirects('/lesson/');

        $fixture = $this->lessonRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getPrice());
        self::assertSame('Something New', $fixture[0]->getContent());
        self::assertSame('Something New', $fixture[0]->getCourse());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Lesson();
        $fixture->setTitle('Value');
        $fixture->setPrice('Value');
        $fixture->setContent('Value');
        $fixture->setCourse('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/lesson/');
        self::assertSame(0, $this->lessonRepository->count([]));
    }
}
