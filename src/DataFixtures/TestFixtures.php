<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use App\Entity\Course;
use App\Entity\Lesson;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Fixture to populate the database with test themes, courses, and lessons.
 */
class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // -------------------------
        // Theme: Music
        // -------------------------
        $music = new Theme();
        $music->setName('Musique');
        $manager->persist($music);

        // Guitar course
        $guitarCourse = new Course();
        $guitarCourse->setTitle('Cursus d’initiation à la guitare')
                     ->setPrice(50)
                     ->setTheme($music);
        $manager->persist($guitarCourse);

        $lesson1Guitar = new Lesson();
        $lesson1Guitar->setTitle('Leçon n°1 : Découverte de l’instrument')
                      ->setPrice(26)
                      ->setCourse($guitarCourse)
                      ->setTheme($music);
        $manager->persist($lesson1Guitar);

        $lesson2Guitar = new Lesson();
        $lesson2Guitar->setTitle('Leçon n°2 : Les accords et les gammes')
                      ->setPrice(26)
                      ->setCourse($guitarCourse)
                      ->setTheme($music);
        $manager->persist($lesson2Guitar);

        // Piano course
        $pianoCourse = new Course();
        $pianoCourse->setTitle('Cursus d’initiation au piano')
                    ->setPrice(50)
                    ->setTheme($music);
        $manager->persist($pianoCourse);

        $lesson1Piano = new Lesson();
        $lesson1Piano->setTitle('Leçon n°1 : Découverte de l’instrument')
                     ->setPrice(26)
                     ->setCourse($pianoCourse)
                     ->setTheme($music);
        $manager->persist($lesson1Piano);

        $lesson2Piano = new Lesson();
        $lesson2Piano->setTitle('Leçon n°2 : Les accords et les gammes')
                     ->setPrice(26)
                     ->setCourse($pianoCourse)
                     ->setTheme($music);
        $manager->persist($lesson2Piano);

        // -------------------------
        // Theme: IT
        // -------------------------
        $it = new Theme();
        $it->setName('Informatique');
        $manager->persist($it);

        $webDev = new Course();
        $webDev->setTitle('Cursus d’initiation au développement web')
               ->setPrice(60)
               ->setTheme($it);
        $manager->persist($webDev);

        $lesson1Web = new Lesson();
        $lesson1Web->setTitle('Leçon n°1 : Les langages HTML et CSS')
                   ->setPrice(32)
                   ->setCourse($webDev)
                   ->setTheme($it);
        $manager->persist($lesson1Web);

        $lesson2Web = new Lesson();
        $lesson2Web->setTitle('Leçon n°2 : Dynamiser votre site avec JavaScript')
                   ->setPrice(32)
                   ->setCourse($webDev)
                   ->setTheme($it);
        $manager->persist($lesson2Web);

        // -------------------------
        // Theme: Gardening
        // -------------------------
        $garden = new Theme();
        $garden->setName('Jardinage');
        $manager->persist($garden);

        $gardenCourse = new Course();
        $gardenCourse->setTitle('Cursus d’initiation au jardinage')
                     ->setPrice(30)
                     ->setTheme($garden);
        $manager->persist($gardenCourse);

        $lesson1Garden = new Lesson();
        $lesson1Garden->setTitle('Leçon n°1 : Les outils du jardinier')
                      ->setPrice(16)
                      ->setCourse($gardenCourse)
                      ->setTheme($garden);
        $manager->persist($lesson1Garden);

        $lesson2Garden = new Lesson();
        $lesson2Garden->setTitle('Leçon n°2 : Jardiner avec la lune')
                      ->setPrice(16)
                      ->setCourse($gardenCourse)
                      ->setTheme($garden);
        $manager->persist($lesson2Garden);

        // -------------------------
        // Theme: Cooking
        // -------------------------
        $cooking = new Theme();
        $cooking->setName('Cuisine');
        $manager->persist($cooking);

        $cookingCourse1 = new Course();
        $cookingCourse1->setTitle('Cursus d’initiation à la cuisine')
                       ->setPrice(44)
                       ->setTheme($cooking);
        $manager->persist($cookingCourse1);

        $lesson1Cook = new Lesson();
        $lesson1Cook->setTitle('Leçon n°1 : Les modes de cuisson')
                    ->setPrice(23)
                    ->setCourse($cookingCourse1)
                    ->setTheme($cooking);
        $manager->persist($lesson1Cook);

        $lesson2Cook = new Lesson();
        $lesson2Cook->setTitle('Leçon n°2 : Les saveurs')
                    ->setPrice(23)
                    ->setCourse($cookingCourse1)
                    ->setTheme($cooking);
        $manager->persist($lesson2Cook);

        $cookingCourse2 = new Course();
        $cookingCourse2->setTitle('Cursus d’initiation à l’art du dressage culinaire')
                       ->setPrice(48)
                       ->setTheme($cooking);
        $manager->persist($cookingCourse2);

        $lesson1Dress = new Lesson();
        $lesson1Dress->setTitle('Leçon n°1 : Mettre en œuvre le style dans l’assiette')
                     ->setPrice(26)
                     ->setCourse($cookingCourse2)
                     ->setTheme($cooking);
        $manager->persist($lesson1Dress);

        $lesson2Dress = new Lesson();
        $lesson2Dress->setTitle('Leçon n°2 : Harmoniser un repas à quatre plats')
                     ->setPrice(26)
                     ->setCourse($cookingCourse2)
                     ->setTheme($cooking);
        $manager->persist($lesson2Dress);

        // -------------------------
        // Persist all data to the database
        // -------------------------
        $manager->flush();
    }
}
