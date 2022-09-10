<?php
/**
 * Favourite fixtures.
 */

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Favourite;

/**
 * Class Favourite fixtures.
 */
class FavouriteFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load Data.
     */
    public function loadData(): void
    {
        $this->createMany(10, 'favourites', function ($i) {
            $favourite = new Favourite();
            $author = $this->getRandomReference('users');
            $favourite->setAuthor($author);
            $book = $this->getRandomReference('books');
            $favourite->setBook($book);

            return $favourite;
        });

        $this->manager->flush();
    }

    /**
     * Function getDependencies.
     *
     * @return string[] of dependencies
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class, BookFixtures::class];
    }
}
