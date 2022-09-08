<?php
/**
 * Comment controller test
 */
namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CommentControllerTest
 */
class CommentControllerTest extends WebTestCase
{

    public const TEST_ROUTE = '/comment';

    private KernelBrowser $httpClient;

    /**
     * Set up test
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Delete comment test
     */
    public function testDeleteComment(): void
    {
        //given
        $user = $this->createUser('userForTest');
        $this->httpClient->loginUser($user);

        $commentToDelete = new Comment();
        $commentToDelete->setContent('TestCommentDelete');
        $commentToDelete->setBook($this->createBook('delete', $this->createCategory('delete')));
        $book = $commentToDelete->getBook();
        $commentToDelete->setAuthor($this->createUser('delete'));
        $commentRepository = static::getContainer()->get(CommentRepository::class);
        $commentRepository->save($commentToDelete);

        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$commentToDelete->getId().'/delete');

        //when
        $this->httpClient->submitForm(
            'Usun'
        );

        //then
        $this->assertNull($commentRepository->findOneByBook($book));
    }

    /**
     * Create category for comment's tests
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
     * Create book for comment's tests
     *
     * @param string $title
     * @param Category $category
     *
     * @return Book
     */
    private function createBook(string $title, Category $category): Book
    {
        $book = new Book();
        $book->setTitle($title);
        $book->setAuthor('TestBookForComment');
        $book->setCategory($category);

        $bookRepository = static::getContainer()->get(BookRepository::class);
        $bookRepository->save($book);

        return $book;
    }


    /**
     * Create user for comment's tests
     *
     * @param string $name
     *
     * @return User
     */
    private function createUser(string $name): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setEmail($name.'example.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                '1234'
            )
        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }
}
