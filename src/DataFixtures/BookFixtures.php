<?php
/**
 * Book fixtures.
 */

namespace App\DataFixtures;

// use AbstractBaseFixtures;
use App\Entity\Book;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Category;

/**
 * Class BookFixtures.
 */
class BookFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
//        if (null === $this->manager || null === $this->faker) {
//            return;
//        }

        $this->createMany(20, 'books', function (int $i) {
            $book = new Book();
            $book->setTitle($this->faker->domainName);
            $book->setAuthor($this->faker->firstName);

            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $book->setCategory($category);
            $tags = $this->getRandomReferences(
                'tags',
                $this->faker->numberBetween(1, 3)
            );

            foreach ($tags as $tag) {
                $book->addTag($tag);
            }

            return $book;
        });

        $this->manager->flush();
    }

    /**
     * Function getDependencies.
     *
     * @return string[] of dependencies
     *
     */
    public function getDependencies(): array
    {
        // TODO: Implement getDependencies() method.

        return [CategoryFixtures::class, TagFixtures::class];
    }
}
