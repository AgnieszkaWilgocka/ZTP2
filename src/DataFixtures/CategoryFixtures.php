<?php
/**
 * Category fixtures
 */
namespace App\DataFixtures;

use App\Entity\Category;
use DateTimeImmutable;

//use Doctrine\Persistence\ObjectManager;
//use App\DataFixtures\AbstractBaseFixtures;

/**
 * Class CategoryFixtures
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data
     */
    public function loadData(): void
    {
        $this->createMany(20, 'categories', function (int $i) {
            $category = new Category();
            $category->setTitle($this->faker->colorName);
            $category->setCreatedAt(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $category->setUpdatedAt(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );

            return $category;
        });


        $this->manager->flush();
    }
}
