<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use App\Entity\Course;
use App\Entity\Lesson;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // -------------------------
        // Thème : Musique
        // -------------------------
        $music = new Theme();
        $music->setName('Musique');

        $guitarCourse = new Course();
        $guitarCourse->setTitle('Cursus d’initiation à la guitare')
                     ->setPrice(50)
                     ->setTheme($music);

        $lesson1Guitar = new Lesson();
        $lesson1Guitar->setTitle('Découverte de l’instrument')
                      ->setPrice(26)
                      ->setCourse($guitarCourse);

        $lesson2Guitar = new Lesson();
        $lesson2Guitar->setTitle('Les accords et les gammes')
                      ->setPrice(26)
                      ->setCourse($guitarCourse);

        $pianoCourse = new Course();
        $pianoCourse->setTitle('Cursus d’initiation au piano')
                    ->setPrice(50)
                    ->setTheme($music);

        $lesson1Piano = new Lesson();
        $lesson1Piano->setTitle('Découverte de l’instrument')
                     ->setPrice(26)
                     ->setCourse($pianoCourse);

        $lesson2Piano = new Lesson();
        $lesson2Piano->setTitle('Les accords et les gammes')
                     ->setPrice(26)
                     ->setCourse($pianoCourse);

        $manager->persist($music);
        $manager->persist($guitarCourse);
        $manager->persist($lesson1Guitar);
        $manager->persist($lesson2Guitar);
        $manager->persist($pianoCourse);
        $manager->persist($lesson1Piano);
        $manager->persist($lesson2Piano);

        // -------------------------
        // Thème : Informatique
        // -------------------------
        $it = new Theme();
        $it->setName('Informatique');

        $webDev = new Course();
        $webDev->setTitle('Cursus d’initiation au développement web')
               ->setPrice(60)
               ->setTheme($it);

        $lesson1Web = new Lesson();
        $lesson1Web->setTitle('Les langages Html et CSS')
                   ->setPrice(32)
                   ->setCourse($webDev);

        $lesson2Web = new Lesson();
        $lesson2Web->setTitle('Dynamiser votre site avec Javascript')
                   ->setPrice(32)
                   ->setCourse($webDev);

        $manager->persist($it);
        $manager->persist($webDev);
        $manager->persist($lesson1Web);
        $manager->persist($lesson2Web);

        // -------------------------
        // Thème : Jardinage
        // -------------------------
        $garden = new Theme();
        $garden->setName('Jardinage');

        $gardenCourse = new Course();
        $gardenCourse->setTitle('Cursus d’initiation au jardinage')
                     ->setPrice(30)
                     ->setTheme($garden);

        $lesson1Garden = new Lesson();
        $lesson1Garden->setTitle('Les outils du jardinier')
                      ->setPrice(16)
                      ->setCourse($gardenCourse);

        $lesson2Garden = new Lesson();
        $lesson2Garden->setTitle('Jardiner avec la lune')
                      ->setPrice(16)
                      ->setCourse($gardenCourse);

        $manager->persist($garden);
        $manager->persist($gardenCourse);
        $manager->persist($lesson1Garden);
        $manager->persist($lesson2Garden);

        // -------------------------
        // Thème : Cuisine
        // -------------------------
        $cooking = new Theme();
        $cooking->setName('Cuisine');

        $cookingCourse1 = new Course();
        $cookingCourse1->setTitle('Cursus d’initiation à la cuisine')
                       ->setPrice(44)
                       ->setTheme($cooking);

        $lesson1Cook = new Lesson();
        $lesson1Cook->setTitle('Les modes de cuisson')
                    ->setPrice(23)
                    ->setCourse($cookingCourse1);

        $lesson2Cook = new Lesson();
        $lesson2Cook->setTitle('Les saveurs')
                    ->setPrice(23)
                    ->setCourse($cookingCourse1);

        $cookingCourse2 = new Course();
        $cookingCourse2->setTitle('Cursus d’initiation à l’art du dressage culinaire')
                       ->setPrice(48)
                       ->setTheme($cooking);

        $lesson1Dress = new Lesson();
        $lesson1Dress->setTitle('Mettre en œuvre le style dans l’assiette')
                     ->setPrice(26)
                     ->setCourse($cookingCourse2);

        $lesson2Dress = new Lesson();
        $lesson2Dress->setTitle('Harmoniser un repas à quatre plats')
                     ->setPrice(26)
                     ->setCourse($cookingCourse2);

        $manager->persist($cooking);
        $manager->persist($cookingCourse1);
        $manager->persist($lesson1Cook);
        $manager->persist($lesson2Cook);
        $manager->persist($cookingCourse2);
        $manager->persist($lesson1Dress);
        $manager->persist($lesson2Dress);

        $manager->flush();
    }
}
