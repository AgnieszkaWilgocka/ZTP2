<?php
/**
 * Book controller test.
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
 * Class BookControllerTest.
 */
class BookControllerTest extends WebTestCase
{
    /**
     * Test route.
     *
     * @const string
     */
    public const TEST_ROUTE = '/book';

    /**
     * Test Client.
     */
    private KernelBrowser $httpClient;

    /**
     * Set up test.
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test index route for anonymous user.
     */
    public function testIndexRouteAnonymousUser(): void
    {
        // given
        $expectedStatusCode = 200;

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $result = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $result);
    }

    /**
     * Test index route for admin.
     */
    public function testIndexRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $user = $this->createUser('indexAdmin');
        $this->httpClient->loginUser($user);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $result = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $result);
    }

    /**
     * Show book test.
     */
    public function testShowBook(): void
    {
        // given
        $user = $this->createUser('showBook');
        $this->httpClient->loginUser($user);

        $expectedStatusCode = 200;
        $expectedBook = new Book();
        $expectedBook->setTitle('TestBook');
        $expectedBook->setAuthor('TestBook');
        $expectedBook->setCategory($this->createCategory('show'));

        $comment = new Comment();
        $comment->setAuthor($user);
        $comment->setContent('comment');
        $comment->setBook($expectedBook);

        $bookRepository = static::getContainer()->get(BookRepository::class);
        $commentRepository = static::getContainer()->get(CommentRepository::class);
        $bookRepository->save($expectedBook);
        $commentRepository->save($comment);
        $bookId = $expectedBook->getId();

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$bookId);
        $result = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $result);
    }

    /**
     * Edit book test.
     */
    public function testEditBook(): void
    {
        // given
        $user = $this->createUser('edit');
        $this->httpClient->loginUser($user);

        $bookToEdit = new Book();
        $bookToEdit->setTitle('TestBookToEdit');
        $bookToEdit->setAuthor('TestBookToEdit');
        $bookToEdit->setCategory($this->createCategory('edit'));
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $bookRepository->save($bookToEdit);
        $bookToEditId = $bookToEdit->getId();

        $expectedEditedTitleBook = 'EditedTitle';

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$bookToEditId.'/edit');
        $this->httpClient->submitForm(
            'Zapisz',
            ['book' => ['title' => $expectedEditedTitleBook]]
        );

        $result = $bookRepository->findOneById($bookToEdit->getId());

        // then
        $this->assertEquals($expectedEditedTitleBook, $result->getTitle());
    }

    /**
     * Create user for book's tests.
     *
     * @param string $name Name
     *
     * @return User User
     */
    private function createUser(string $name): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');

        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail($name.'example@com');
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

    /**
     * Create category for book's tests.
     *
     * @param string $name Name
     *
     * @return Category Category
     */
    private function createCategory(string $name): Category
    {
        $category = new Category();
        $category->setCreatedAt(new \DateTimeImmutable());
        $category->setUpdatedAt(new \DateTimeImmutable());
        $category->setTitle($name);

        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryRepository->save($category);

        return $category;
    }
}
