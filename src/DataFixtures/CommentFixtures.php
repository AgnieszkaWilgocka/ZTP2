<?php
/**
 * Comment fixtures
 */
namespace App\DataFixtures;

use App\DataFixtures\AbstractBaseFixtures;
use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class CommentFixtures
 */
class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load Data
     */
    public function loadData(): void
    {
        $this->createMany(10, 'comments', function (int $i) {
            $comment = new Comment();
            $comment->setContent($this->faker->title());
            $comment->setBook($this->getRandomReference('books'));
            $comment->setAuthor($this->getRandomReference('users'));

            return $comment;
        });

        $this->manager->flush();
    }

    /**
     * Function getDependencies
     *
     * @return string[]
     *
     * @psalm-return array{0: BookFixtures::class, 1: UserFixtures::class}
     */
    public function getDependencies():array
    {
        return [BookFixtures::class, UserFixtures::class];
    }
}
