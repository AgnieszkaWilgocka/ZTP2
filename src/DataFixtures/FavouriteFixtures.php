<?php

namespace App\DataFixtures;

use App\DataFixtures\AbstractBaseFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Favourite;
use App\Entity\User;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\BookFixtures;
/**
 * Class Favourite fixtures
 */
class FavouriteFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
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
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class, BookFixtures::class];
    }
}