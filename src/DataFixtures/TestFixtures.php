<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use App\Entity\Course;
use App\Entity\Lesson;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Test fixtures for the Knowledge application.
 *
 * Creates themes, courses, and lessons with prices and relationships
 * between entities to populate the database with realistic data.
 */
class TestFixtures extends Fixture
{
    /**
     * Loads data into the database.
     *
     * @param ObjectManager $manager Doctrine entity manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // -------------------------
        // Theme: Music
        // -------------------------
        $music = new Theme();
        $music->setName('Music');
        $manager->persist($music);

        // Guitar course
        $guitarCourse = new Course();
        $guitarCourse->setTitle('Introduction to Guitar')
                     ->setPrice(50)
                     ->setTheme($music);
        $manager->persist($guitarCourse);

        $lesson1Guitar = new Lesson();
        $lesson1Guitar->setTitle('Discovering the Instrument')
                      ->setPrice(26)
                      ->setCourse($guitarCourse)
                      ->setTheme($guitarCourse->getTheme());
        $manager->persist($lesson1Guitar);

        $lesson2Guitar = new Lesson();
        $lesson2Guitar->setTitle('Chords and Scales')
                      ->setPrice(26)
                      ->setCourse($guitarCourse)
                      ->setTheme($guitarCourse->getTheme());
        $manager->persist($lesson2Guitar);

        // Piano course
        $pianoCourse = new Course();
        $pianoCourse->setTitle('Introduction to Piano')
                    ->setPrice(50)
                    ->setTheme($music);
        $manager->persist($pianoCourse);

        $lesson1Piano = new Lesson();
        $lesson1Piano->setTitle('Discovering the Instrument')
                     ->setPrice(26)
                     ->setCourse($pianoCourse)
                     ->setTheme($pianoCourse->getTheme());
        $manager->persist($lesson1Piano);

        $lesson2Piano = new Lesson();
        $lesson2Piano->setTitle('Chords and Scales')
                     ->setPrice(26)
                     ->setCourse($pianoCourse)
                     ->setTheme($pianoCourse->getTheme());
        $manager->persist($lesson2Piano);

        // -------------------------
        // Theme: IT
        // -------------------------
        $it = new Theme();
        $it->setName('IT');
        $manager->persist($it);

        $webDev = new Course();
        $webDev->setTitle('Introduction to Web Development')
               ->setPrice(60)
               ->setTheme($it);
        $manager->persist($webDev);

        $lesson1Web = new Lesson();
        $lesson1Web->setTitle('HTML and CSS Basics')
                   ->setPrice(32)
                   ->setCourse($webDev)
                   ->setTheme($webDev->getTheme());
        $manager->persist($lesson1Web);

        $lesson2Web = new Lesson();
        $lesson2Web->setTitle('Bring Your Website to Life with JavaScript')
                   ->setPrice(32)
                   ->setCourse($webDev)
                   ->setTheme($webDev->getTheme());
        $manager->persist($lesson2Web);

        // -------------------------
        // Theme: Gardening
        // -------------------------
        $garden = new Theme();
        $garden->setName('Gardening');
        $manager->persist($garden);

        $gardenCourse = new Course();
        $gardenCourse->setTitle('Introduction to Gardening')
                     ->setPrice(30)
                     ->setTheme($garden);
        $manager->persist($gardenCourse);

        $lesson1Garden = new Lesson();
        $lesson1Garden->setTitle('Gardening Tools')
                      ->setPrice(16)
                      ->setCourse($gardenCourse)
                      ->setTheme($gardenCourse->getTheme());
        $manager->persist($lesson1Garden);

        $lesson2Garden = new Lesson();
        $lesson2Garden->setTitle('Gardening by the Moon')
                      ->setPrice(16)
                      ->setCourse($gardenCourse)
                      ->setTheme($gardenCourse->getTheme());
        $manager->persist($lesson2Garden);

        // -------------------------
        // Theme: Cooking
        // -------------------------
        $cooking = new Theme();
        $cooking->setName('Cooking');
        $manager->persist($cooking);

        $cookingCourse1 = new Course();
        $cookingCourse1->setTitle('Introduction to Cooking')
                       ->setPrice(44)
                       ->setTheme($cooking);
        $manager->persist($cookingCourse1);

        $lesson1Cook = new Lesson();
        $lesson1Cook->setTitle('Cooking Methods')
                    ->setPrice(23)
                    ->setCourse($cookingCourse1)
                    ->setTheme($cookingCourse1->getTheme());
        $manager->persist($lesson1Cook);

        $lesson2Cook = new Lesson();
        $lesson2Cook->setTitle('Flavors')
                    ->setPrice(23)
                    ->setCourse($cookingCourse1)
                    ->setTheme($cookingCourse1->getTheme());
        $manager->persist($lesson2Cook);

        $cookingCourse2 = new Course();
        $cookingCourse2->setTitle('Introduction to Culinary Plating')
                       ->setPrice(48)
                       ->setTheme($cooking);
        $manager->persist($cookingCourse2);

        $lesson1Dress = new Lesson();
        $lesson1Dress->setTitle('Plating Techniques')
                     ->setPrice(26)
                     ->setCourse($cookingCourse2)
                     ->setTheme($cookingCourse2->getTheme());
        $manager->persist($lesson1Dress);

        $lesson2Dress = new Lesson();
        $lesson2Dress->setTitle('Harmonizing a Four-Course Meal')
                     ->setPrice(26)
                     ->setCourse($cookingCourse2)
                     ->setTheme($cookingCourse2->getTheme());
        $manager->persist($lesson2Dress);

        // -------------------------
        // Flush all entities
        // -------------------------
        $manager->flush();
    }
}
