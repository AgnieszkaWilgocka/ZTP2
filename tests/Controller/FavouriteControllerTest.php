<?php
/**
 * Favourite controller test
 */
namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Favourite;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\FavouriteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class FavouriteControllerTest
 */
class FavouriteControllerTest extends WebTestCase
{
    public const TEST_ROUTE = '/favourite';

    private KernelBrowser $httpClient;

    /**
     * Set up test
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test index route
     */
    public function testRouteIndex(): void
    {
        //given
        $user = null;
        $expectedStatusCode = 200;

        try {
            $user = $this->createUser('index');
        } catch (OptimisticLockException|NotFoundExceptionInterface|ContainerExceptionInterface|ORMException $e) {

        }
        $this->httpClient->loginUser($user);

        //when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $result = $this->httpClient->getResponse()->getStatusCode();

        //then
        $this->assertEquals($expectedStatusCode, $result);
    }

    /*
     * Create favourite test
     */
    public function testCreateFavourite(): void
    {
        //given
        $expectedStatusCode = 200;
        $user = $this->createUser('create');
        $this->httpClient->loginUser($user);

        $createCategoryForBook = $this->createCategory('create');
        $createBookForFavourite = $this->createBook('create', $createCategoryForBook);

        $newFavourite = new Favourite();
        $newFavourite->setAuthor($user);
        $newFavourite->setBook($createBookForFavourite);
        $favouriteRepository = static::getContainer()->get(FavouriteRepository::class);
        $favouriteRepository->save($newFavourite);

        //when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');

        //then
        $result = $this->httpClient->getResponse()->getStatusCode();

        $this->assertEquals($expectedStatusCode, $result);
        $this->assertNotNull($favouriteRepository->findOneById($newFavourite->getId()));
    }

    /**
     * Delete favourite test
     *
     */
    public function testDeleteFavourite(): void
    {
        //given
        $user = $this->createUser('deleteFavourite');
        $this->httpClient->loginUser($user);

        $favouriteToDelete = new Favourite();
        $favouriteToDelete->setAuthor($user);
        $favouriteToDelete->setBook($this->createBook('deleteTest', $this->createCategory('categoryForBook')) );
        $favouriteRepository = static::getContainer()->get(FavouriteRepository::class);
        $book = $favouriteToDelete->getBook();
        $favouriteRepository->save($favouriteToDelete);
        $favouriteToDeleteId = $favouriteToDelete->getId();

        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$favouriteToDeleteId.'/delete');

        //when
        $this->httpClient->submitForm(
            'Usun'
        );

        //then
        $this->assertNull($favouriteRepository->findOneByBook($book));
    }

    /**
     * Create category for favourite's tests
     *
     * @param string $title
     *
     * @return Category
     */
    private function createCategory(string $title): Category
    {
        $category = new Category();
        $category->setCreatedAt(new \DateTimeImmutable());
        $category->setUpdatedAt(new \DateTimeImmutable());
        $category->setTitle($title);

        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryRepository->save($category);

        return $category;
    }

    /**
     * Create book for favourite's tests
     *
     * @param string $title
     * @param Category $category
     *
     * @return Book
     */
    public function createBook(string $title, Category $category): Book
    {
        $book = new Book();
        $book->setTitle($title);
        $book->setAuthor($title);
        $book->setCategory($category);

        $bookRepository = static::getContainer()->get(BookRepository::class);
        $bookRepository->save($book);

        return $book;
    }

    /**
     * Create user for favourite's tests
     *
     * @param string $name
     *
     * @return User
     */
    private function createUser(string $name): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail($name.'example.com');
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                '1234'
            )
        );
        $userRepository->save($user);

        return $user;
    }
}
