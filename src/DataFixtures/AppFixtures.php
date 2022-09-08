<?php
/**
 * App fixtures
 */
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AppFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * Function load
     *
     * @param ObjectManager $manager
     *
     */
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
