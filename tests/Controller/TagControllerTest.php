<?php
/**
 * Tag controller test.
 */

namespace App\Tests\Controller;

use App\Entity\Tag;
use App\Entity\User;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TagControllerTest.
 */
class TagControllerTest extends WebTestCase
{
    /**
     * Test route.
     *
     * @const string
     */
    public const TEST_ROUTE = '/tag';

    /**
     * Test client.
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
     * Test index route.
     */
    public function testRouteIndex(): void
    {
        // given
        $expectedStatusCode = 200;

        $user = $this->createUser('indexTest');
        $this->httpClient->loginUser($user);

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $result = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals($expectedStatusCode, $result);
    }

    /**
     * Show tag test.
     */
    public function testShowTag(): void
    {
        // given
        $expectedStatusCode = 200;
        $user = $this->createUser('ShowTest');
        $this->httpClient->loginUser($user);

        $tag = new Tag();
        $tag->setCreatedAt(new \DateTimeImmutable());
        $tag->setUpdatedAt(new \DateTimeImmutable());
        $tag->setTitle('TestShowTag');
        $tagRepository = static::getContainer()->get(TagRepository::class);
        $tagRepository->save($tag);
        $tagId = $tag->getId();

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$tagId);
        $this->assertNotNull($tag->getCreatedAt());
        $this->assertNotNull($tag->getUpdatedAt());

        // then
        $result = $this->httpClient->getResponse()->getStatusCode();

        $this->assertEquals($expectedStatusCode, $result);
    }

    /**
     * Create tag test.
     */
    public function testCreateTag(): void
    {
        // given
        $user = $this->createUser('createTagTest');
        $this->httpClient->loginUser($user);

        $titleForCreateTag = 'titleTag';
        $tagRepository = static::getContainer()->get(TagRepository::class);

        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');

        // when
        $this->httpClient->submitForm(
            'Zapisz',
            ['tag' => ['title' => $titleForCreateTag]]
        );

        // then
        $createdTag = $tagRepository->findOneByTitle($titleForCreateTag);
        $this->assertEquals($titleForCreateTag, $createdTag->getTitle());
    }

    /**
     * Edit tag test.
     */
    public function testEditTag(): void
    {
        // given
        $user = $this->createUser('editTest');
        $this->httpClient->loginUser($user);

        $tagToEdit = new Tag();
        $tagToEdit->setCreatedAt(new \DateTimeImmutable());
        $tagToEdit->setUpdatedAt(new \DateTimeImmutable());
        $tagToEdit->setTitle('TestTag');
        $tagRepository = static::getContainer()->get(TagRepository::class);
        $tagRepository->save($tagToEdit);
        $tagToEditId = $tagToEdit->getId();
        $expectedTagEditedTitle = 'EditedTitleTag';

        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$tagToEditId.'/edit');

        // when
        $this->httpClient->submitForm(
            'Save',
            ['tag' => ['title' => $expectedTagEditedTitle]]
        );

        // then
        $editedTag = $tagRepository->findOneById($tagToEdit->getId());
        $this->assertEquals($expectedTagEditedTitle, $editedTag->getTitle());
    }

    /**
     * Delete tag test.
     */
    public function testDeleteTag(): void
    {
        // given
        $user = $this->createUser('deleteTest');
        $this->httpClient->loginUser($user);

        $tagToDelete = new Tag();
        $tagToDelete->setCreatedAt(new \DateTimeImmutable());
        $tagToDelete->setUpdatedAt(new \DateTimeImmutable());
        $tagToDelete->setTitle('TestDeleteTag');

        $tagRepository = static::getContainer()->get(TagRepository::class);
        $tagRepository->save($tagToDelete);
        $tagToDeleteId = $tagToDelete->getId();

        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$tagToDeleteId.'/delete');

        // when
        $this->httpClient->submitForm(
            'Usun'
        );

        // then
        $this->assertNull($tagRepository->findOneByTitle('TestDeleteTag'));
    }

    /**
     * Create user for tag's tests.
     *
     * @param string $name Name
     *
     * @return User User
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
