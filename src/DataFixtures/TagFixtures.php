<?php
/**
 * Tag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use DateTimeImmutable;

/**
 * Class TagFixtures.
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Load Data.
     */
    public function loadData(): void
    {
        $this->createMany(20, 'tags', function ($i) {
            $tag = new Tag();
            $tag->setTitle($this->faker->colorName);
            $tag->setCreatedAt(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );
            $tag->setUpdatedAt(
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-100 days', '-1 days'))
            );

            return $tag;
        });

        $this->manager->flush();
    }
}
