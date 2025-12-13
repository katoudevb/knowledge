<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Main fixtures class for the application.
 *
 * Used to prefill the database with initial data
 * for testing or development purposes.
 */
class AppFixtures extends Fixture
{
    /**
     * Loads data into the database.
     *
     * @param ObjectManager $manager Doctrine entity manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // Example of entity creation:
        // $product = new Product();
        // $manager->persist($product);

        // Executes database insertion for all persisted entities
        $manager->flush();
    }
}
