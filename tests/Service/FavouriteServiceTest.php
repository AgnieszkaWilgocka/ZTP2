<?php
/**
 * Favourite service test.
 */

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Favourite;
use App\Entity\User;
use App\Service\BookService;
use App\Service\CategoryService;
use App\Service\FavouriteService;
use App\Service\UserService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * FavouriteServiceTest class.
 */
class FavouriteServiceTest extends KernelTestCase
{
    /**
     * Test entity manager.
     *
     * @var EntityManagerInterface|object|null
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Test favourite service.
     *
     * @var FavouriteService|object|null
     */
    private ?FavouriteService $favouriteService;

    /**
     * Test user service.
     *
     * @var UserService|object|null
     */
    private ?UserService $userService;

    /**
     * Test book service.
     *
     * @var BookService|object|null
     */
    private ?BookService $bookService;

    /**
     * Test category service.
     *
     * @var CategoryService|object|null
     */
    private ?CategoryService $categoryService;

    /**
     * Set up test.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->favouriteService = $container->get(FavouriteService::class);
        $this->categoryService = $container->get(CategoryService::class);
        $this->userService = $container->get(UserService::class);
        $this->bookService = $container->get(BookService::class);
    }

    /**
     * Save test.
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testSave(): void
    {
        // given
        // create entity for favourite
        $testCategory = $this->createCategory('favourite_save');
        $testUser = $this->createUser('favourite_save');
        $testBook = $this->createBook('favourite_save', $testCategory);

        $expectedFavourite = new Favourite();
        $expectedFavourite->setAuthor($testUser);
        $expectedFavourite->setBook($testBook);

        // when
        $this->favouriteService->save($expectedFavourite);

        // then
        $expectedFavouriteId = $expectedFavourite->getId();
        $resultFavourite = $this->entityManager->createQueryBuilder()
            ->select('favourite')
            ->from(Favourite::class, 'favourite')
            ->where('favourite.id = :id')
            ->setParameter(':id', $expectedFavouriteId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedFavourite, $resultFavourite);
    }

    /**
     * Delete test.
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testDelete(): void
    {
        // given
        $testCategory = $this->createCategory('favouriteDelete');
        $testUser = $this->createUser('favourite_delete');
        $testBook = $this->createBook('favourite_delete', $testCategory);

        $favouriteToDelete = new Favourite();
        $favouriteToDelete->setBook($testBook);
        $favouriteToDelete->setAuthor($testUser);
        $this->entityManager->persist($favouriteToDelete);
        $this->entityManager->flush();
        $deletedFavouriteId = $favouriteToDelete->getId();

        // when
        $this->favouriteService->delete($favouriteToDelete);

        // then
        $resultFavourite = $this->entityManager->createQueryBuilder()
        ->select('favourite')
        ->from(Favourite::class, 'favourite')
        ->where('favourite.id = :id')
        ->setParameter('id', $deletedFavouriteId, Types::INTEGER)
        ->getQuery()
        ->getOneOrNullResult();

        $this->assertNull($resultFavourite);
    }

    /**
     * Create category for favourite's tests.
     *
     * @param string $name Name
     *
     * @return Category Category entity
     */
    private function createCategory(string $name): Category
    {
        $category = new Category();
        $category->setCreatedAt(new \DateTimeImmutable());
        $category->setUpdatedAt(new \DateTimeImmutable());
        $category->setTitle($name);
        $this->categoryService->save($category);

        return $category;
    }

    /**
     * Create book for favourite's tests.
     *
     * @param string   $title    Title
     * @param Category $category Category entity
     *
     * @return Book Book entity
     */
    private function createBook(string $title, Category $category): Book
    {
        $book = new Book();
        $book->setTitle($title);
        $book->setCategory($category);
        $book->setAuthor($title);

        $this->bookService->save($book);

        return $book;
    }

    /**
     * Create user for favourite's tests.
     *
     * @param string $name Name
     *
     * @return User User entity
     */
    private function createUser(string $name): User
    {
        $user = new User();
        $user->setEmail($name.'@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('testUser1234');
        $this->userService->save($user);

        return $user;
    }
}
