<?php

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
 * FavouriteServiceTest class
 */
class FavouriteServiceTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    private ?FavouriteService $favouriteService;

    private ?UserService $userService;

    private ?BookService $bookService;

    private ?CategoryService $categoryService;

    /**
     * Set up test
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

    public function testSave():void
    {
        //given
        $testCategory = new Category();
        $testCategory->setTitle('Test Category');
        $testCategory->setCreatedAt(new \DateTimeImmutable());
        $testCategory->setUpdatedAt(new \DateTimeImmutable());
        $this->categoryService->save($testCategory);

        $testUser = new User();
        $testUser->setEmail('testUser0@example.com');
        $testUser->setRoles(['ROLE_USER']);
        $testUser->setPassword('testUser1234');
        $this->userService->save($testUser);

        $testBook = new Book();
        $testBook->setTitle('Test Book');
        $testBook->setAuthor('Test Author');
        $testBook->setCategory($testCategory);
        $this->bookService->save($testBook);

        $expectedFavourite = new Favourite();
        $expectedFavourite->setAuthor($testUser);
        $expectedFavourite->setBook($testBook);

        //when
        $this->favouriteService->save($expectedFavourite);

        //then
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

    public function testDelete():void
    {
        //given
        $testCategory = new Category();
        $testCategory->setTitle('Test Category');
        $testCategory->setCreatedAt(new \DateTimeImmutable());
        $testCategory->setUpdatedAt(new \DateTimeImmutable());
        $this->categoryService->save($testCategory);

        $testUser = new User();
        $testUser->setEmail('testUser1@example.com');
        $testUser->setRoles(['ROLE_USER']);
        $testUser->setPassword('testUser1234');
        $this->userService->save($testUser);

        $testBook = new Book();
        $testBook->setTitle('Test Book');
        $testBook->setAuthor('Test Author');
        $testBook->setCategory($testCategory);
        $this->bookService->save($testBook);


        $favouriteToDelete = new Favourite();
        $favouriteToDelete->setBook($testBook);
        $favouriteToDelete->setAuthor($testUser);
        $this->entityManager->persist($favouriteToDelete);
        $this->entityManager->flush();
        $deletedFavouriteId = $favouriteToDelete->getId();

        //when
        $this->favouriteService->delete($favouriteToDelete);

        //then
        $resultFavourite = $this->entityManager->createQueryBuilder()
        ->select('favourite')
        ->from(Favourite::class, 'favourite')
        ->where('favourite.id = :id')
        ->setParameter('id', $deletedFavouriteId, Types::INTEGER)
        ->getQuery()
        ->getOneOrNullResult();

        $this->assertNull($resultFavourite);

        //

    }

    //  TRZEBA PARAMETR AUTHOR //
//    public function testGetPaginatedList(): void
//    {
//        //given
//        $page = 1;
//
//        $dataSetSize = 3;
//        $expectedResultSize = 3;
//
//        $counter = 0;
//        while ($counter < $dataSetSize) {
//            $testCategory = new Category();
//            $testCategory->setTitle('Test Category #'.$counter);
//            $testCategory->setCreatedAt(new \DateTimeImmutable());
//            $testCategory->setUpdatedAt(new \DateTimeImmutable());
//            $this->categoryService->save($testCategory);

//            $testUser = new User();
//            $testUser->setEmail('testUser@example.com#'.$counter);
//            $testUser->setRoles(['ROLE_USER']);
//            $testUser->setPassword('testUser1234');
//            $this->userService->save($testUser);

//            $testBook = new Book();
//            $testBook->setTitle('Test Book #'.$counter);
//            $testBook->setAuthor('Test Author #'.$counter);
//            $testBook->setCategory($thi);
//            $this->bookService->save($testBook);

//            $favourite = new Favourite();
//            $favourite->setAuthor($this->createUser('testPaginated'.$counter));
//            $favourite->setBook($this->createBook('testPaginated'.$counter, $this->createCategory('testPaginated'.$counter)));
//            $this->favouriteService->save($favourite);
//            ++$counter;
//        }
//
//        //when
//        $result = $this->favouriteService->getPaginatedList($page, $this->createUser('user'));
//
//        $this->assertEquals($expectedResultSize, $result->count());
//    }

    private function createCategory(string $name): Category
    {
        $category = new Category();
        $category->setCreatedAt(new \DateTimeImmutable());
        $category->setUpdatedAt(new \DateTimeImmutable());
        $category->setTitle($name);
        $this->categoryService->save($category);

        return $category;
    }

    private function createBook(string $title, Category $category): Book
    {
        $book = new Book();
        $book->setTitle($title);
        $book->setCategory($category);
        $book->setAuthor($title);

        $this->bookService->save($book);

        return $book;
    }

    private function createUser($name): User
    {
        $user = new User();
        $user->setEmail($name.'@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('testUser1234');
        $this->userService->save($user);

        return $user;
    }

}
