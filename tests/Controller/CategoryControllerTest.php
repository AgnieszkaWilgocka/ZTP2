<?php

namespace App\Tests\Category;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{

    /**
     * Test route
     *
     * @const string
     */
    public const TEST_ROUTE = '/category';

    /**
     * Test client
     */
    private KernelBrowser $httpClient;


    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test index route for anonymous user
     */
    public function testIndexRouteAnonymousUser(): void
    {
        //given
        $expectedStatusCode = 200;

        //when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        //then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }

    public function testIndexRouteAdminUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $adminUser = $this->createUser('user');
        $this->httpClient->loginUser($adminUser);

        // when
        $client = $this->httpClient->request('GET', self::TEST_ROUTE);
//        var_dump($client);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $resultStatusCode);
    }


    public function testCreateForAdmin(): void
    {
        $expectedStatusCode = 200;
        $user = $this->createUser('create');
        $this->httpClient->loginUser($user);

        //when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');

        //then
        $result = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $result);
    }

    public function testCreateForAnonymous(): void
    {
        $expectedStatusCode = 302;

        //when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');

        $result = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $result);
    }

    public function testShowCategory(): void
    {
        //given
//        $adminUser = $this->createUser('UserTest');
//        $this->httpClient->loginUser($adminUser);

        $expectedCategory = new Category();
        $createdAt = new \DateTimeImmutable();
        $updatedAt = new \DateTimeImmutable();
        $expectedCategory->setCreatedAt($createdAt);
        $expectedCategory->setUpdatedAt($updatedAt);
        $expectedCategory->setTitle('TestCategory');
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryRepository->save($expectedCategory);


        //when
        $this->httpClient->request('GET', self::TEST_ROUTE. '/'.$expectedCategory->getId());
        $result = $this->httpClient->getResponse();

        //then
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertSelectorTextContains('html title', $expectedCategory->getId());
        $this->assertEquals($createdAt, $expectedCategory->getCreatedAt());
        $this->assertEquals($updatedAt, $expectedCategory->getUpdatedAt());

    }


    public function testCreateCategory(): void
    {

        //given

        $user = $this->createUser('testCreate');
        $this->httpClient->loginUser($user);



        $createCategoryTitle = 'createCategoryTest';
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);

//        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');
        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');

        //when
        $this->httpClient->submitForm(
            'Zapisz',
            ['category' => ['title' => $createCategoryTitle]]
        );

        //then
        $category = $categoryRepository->findOneByTitle($createCategoryTitle);
        $this->assertEquals($createCategoryTitle, $category->getTitle());
//        $this->assertNotNull($category->getCreatedAt());



    }


    public function testCategoryEdit(): void
    {
        //given
        $user = $this->createUser('UserForTestEditMethod');
        $this->httpClient->loginUser($user);

        $categoryRepository = static::getContainer()->get(CategoryRepository::class);

        $categoryToEdit = new Category();
        $categoryToEdit->setCreatedAt(new \DateTimeImmutable());
        $categoryToEdit->setUpdatedAt(new \DateTimeImmutable());
        $categoryToEdit->setTitle('TestCategory');
        $categoryRepository->save($categoryToEdit);
        $expectedEditedCategoryTitle = 'EditedTitle';

//        $categoryId = $categoryToEdit->getId();


        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$categoryToEdit->getId().'/edit');
//        $expectedEditedCategoryTitle = 'EditedTitle';
//        when
        $this->httpClient->submitForm(
            'Save',
            ['category' => ['title' => $expectedEditedCategoryTitle]]
        );

        //then
        $editedCategory = $categoryRepository->findOneById($categoryToEdit->getId());
        $this->assertEquals($expectedEditedCategoryTitle, $editedCategory->getTitle());

    }

    public function testDeleteCategory(): void
    {
        //given
        $user = $this->createUser('UserForTestDeleteMethod');
        $this->httpClient->loginUser($user);

        $categoryRepository = static::getContainer()->get(CategoryRepository::class);

        $categoryToDelete = new Category();
        $categoryToDelete->setCreatedAt(new \DateTimeImmutable());
        $categoryToDelete->setUpdatedAt(new \DateTimeImmutable());
        $categoryToDelete->setTitle('TestCategoryToDelete');
        $categoryRepository->save($categoryToDelete);
        $categoryToDeleteId = $categoryToDelete->getId();

        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$categoryToDeleteId.'/delete');

        //when
        $this->httpClient->submitForm(
            'Usun'
        );

        //then
        $this->assertNull($categoryRepository->findOneByTitle('TestCategoryToDelete'));
    }

    public function testCanDeleteCategory(): void
    {
        //given
        $user = $this->createUser('testCanDelete');
        $this->httpClient->loginUser($user);

        $expectedStatusCode = 302;

        $categoryToDelete = new Category();
        $categoryToDelete->setCreatedAt(new \DateTimeImmutable());
        $categoryToDelete->setUpdatedAt(new \DateTimeImmutable());
        $categoryToDelete->setTitle('testCanDelete');
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryRepository->save($categoryToDelete);
        $categoryToDeleteId = $categoryToDelete->getId();

        $this->createBook($categoryToDelete);

        //when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$categoryToDeleteId.'/delete');

        //then
        $result = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $result);
        $this->assertNotNull($categoryRepository->findOneByTitle('testCanDelete'));

    }
    private function createUser($name): User
    {
//        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail($name.'user@example.com');
        $user->setPassword('testUser1234');
//        $user->setPassword(
//            $passwordHasher->hashPassword(
//                $user,
//                'p@55w0rd'
//
//            )
//        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }

    private function createBook(Category $category): Book
    {
        $book = new Book();
        $book->setAuthor('testCanDelete');
        $book->setTitle('testCanDelete');
        $book->setCategory($category);

        $bookRepository = static::getContainer()->get(BookRepository::class);
        $bookRepository->save($book);

        return $book;
    }



}