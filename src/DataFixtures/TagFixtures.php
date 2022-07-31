<?php

namespace App\DataFixtures;

use App\DataFixtures\AbstractBaseFixtures;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use DateTimeImmutable;

/**
 *
 */
class TagFixtures extends AbstractBaseFixtures
{
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