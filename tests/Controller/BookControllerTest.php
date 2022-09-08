<?php

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

class BookControllerTest extends WebTestCase
{
    public const TEST_ROUTE = '/book';

    private KernelBrowser $httpClient;


    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }


    public function testIndexRouteAnonymousUser(): void
    {
        //given
        $expectedStatusCode = 200;

        //when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $result = $this->httpClient->getResponse()->getStatusCode();
        //then
        $this->assertEquals($expectedStatusCode, $result);
    }

    public function testIndexRouteAdminUser(): void
    {
        //given
        $expectedStatusCode = 200;
        $user = $this->createUser('indexAdmin');
        $this->httpClient->loginUser($user);

        //when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $result = $this->httpClient->getResponse()->getStatusCode();
        //then
        $this->assertEquals($expectedStatusCode, $result);
    }

    public function testShowBook(): void
    {
        //given
        $user = $this->createUser('showBook');
        $this->httpClient->loginUser($user);

        $expectedStatusCode = 200;
        $expectedBook = new Book();
        $expectedBook->setTitle('TestBook');
        $expectedBook->setAuthor('TestBook');
        $expectedBook->setCategory($this->createCategory('show'));

        $comment = new Comment();
        $comment->setAuthor($user);
//        $comment->setAuthor($this->createUser('comment'));
        $comment->setContent('comment');
        $comment->setBook($expectedBook);

        $bookRepository = static::getContainer()->get(BookRepository::class);
        $commentRepository = static::getContainer()->get(CommentRepository::class);
        $bookRepository->save($expectedBook);
        $commentRepository->save($comment);
        $bookId = $expectedBook->getId();

        //when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$bookId);
        $result = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $result);
//        $this->assertEquals($comment, $commentRepository->findOneByBook($expectedBook));
    }

//    public function testCreateBook(): void
//    {
//        $titleForNewBook = 'BookTitle';
//        $bookRepository = static::getContainer()->get(BookRepository::class);
//
//        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');
//
//        //when
//        $this->httpClient->submitForm(
//            'Zapisz',
//            ['book' => [
//                'title' => $titleForNewBook,
//                'category' => 'categoryTitle',
//                'tag' => 'tagTitle'
//            ]]
//        );
//
//        //then
//        $resultBook = $bookRepository->findOneByTitle('BookTitle');
//        $this->assertEquals($titleForNewBook, $resultBook->getTitle());
//
//    }

    public function testEditBook(): void
    {

        //given
        $user = $this->createUser('edit');
        $this->httpClient->loginUser($user);

        $bookToEdit = new Book();

//        $commentForBook = new Comment();
//        $commentForBook->setAuthor($user);
//        $commentForBook->setBook($bookToEdit);
//        $commentForBook->setContent('edit');

        $bookToEdit->setTitle('TestBookToEdit');
        $bookToEdit->setAuthor('TestBookToEdit');
        $bookToEdit->setCategory($this->createCategory('edit'));
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $bookRepository->save($bookToEdit);
        $bookToEditId = $bookToEdit->getId();

        $expectedEditedTitleBook = 'EditedTitle';

        //when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$bookToEditId.'/edit');
        $this->httpClient->submitForm(
            'Zapisz',
            ['book' => ['title' => $expectedEditedTitleBook]]
        );

        $result = $bookRepository->findOneById($bookToEdit->getId());
//        var_dump($result);
        $this->assertEquals($expectedEditedTitleBook, $result->getTitle());
//        $this->assertEquals($editedTitleBook, $result->getTitle());
//        var_dump($expectedBook);

//        $resultBook = $bookRepository->findOneById($expectedBook->getId());
//        $this->assertEquals($editedTitle, $resultBook->getTitle());
//        $editedAuthor = $bookRepository->findOneByAuthor('EditedAuthor');
//        $this->assertEquals('EditedAuthor', $editedAuthor->getAuthor());


    }

//    public function testDeleteBook(): void
//    {
//        //given
//        $user = $this->createUser('delete');
//        $this->httpClient->loginUser($user);
//
//        $bookToDelete = new Book();
//        $bookToDelete->setTitle('TestBookToDelete');
//        $bookToDelete->setAuthor('TestBookToDelete');
//        $bookToDelete->setCategory($this->createCategory('delete'));
//        $bookRepository = static::getContainer()->get(BookRepository::class);
//        $bookRepository->save($bookToDelete);
//        $bookToDeleteId = $bookToDelete->getId();
//
//
//        var_dump($this->httpClient->request('GET', self::TEST_ROUTE.'/'.$bookToDeleteId.'/delete'));
//
//        //when
//        $this->httpClient->submitForm(
//            'Usun'
//        );
//
//        $this->assertNull($bookRepository->findOneByTitle('TestBookToDelete'));
//    }

    private function createUser(string $name): User
    {
        $paswordHasher = static::getContainer()->get('security.password_hasher');

        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail($name.'example@com');
        $user->setPassword(
            $paswordHasher->hashPassword(
                $user,
                '1234'
            )
        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }

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