<?php

namespace App\Tests\Controller;

use App\Entity\Tag;
use App\Entity\User;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagControllerTest extends WebTestCase
{
    public const TEST_ROUTE = '/tag';

    private KernelBrowser $httpClient;

    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    public function testRouteIndex(): void
    {
        //given
        $expectedStatusCode = 200;

        $user = $this->createUser('indexTest');
        $this->httpClient->loginUser($user);

        //when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $result = $this->httpClient->getResponse()->getStatusCode();
        //then
        $this->assertEquals($expectedStatusCode, $result);
    }

    public function testShowTag(): void
    {
        //given
        $expectedStatusCode = 200;
        $user = $this->createUser('ShowTest');
        $this->httpClient->loginUser($user);

        $tag = new Tag();
//        $createdAt = new \DateTimeImmutable();
//        $updatedAt = new \DateTimeImmutable();
        $tag->setCreatedAt(new \DateTimeImmutable());
        $tag->setUpdatedAt(new \DateTimeImmutable());
        $tag->setTitle('TestShowTag');
        $tagRepository = static::getContainer()->get(TagRepository::class);
        $tagRepository->save($tag);
        $tagId = $tag->getId();

        //when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$tagId);
        $this->assertNotNull($tag->getCreatedAt());
        $this->assertNotNull($tag->getUpdatedAt());


        //then
        $result = $this->httpClient->getResponse()->getStatusCode();

        $this->assertEquals($expectedStatusCode, $result);
//        $this->assertEquals($createdAt, $tag->getCreatedAt());
//        $this->assertEquals($updatedAt, $tag->getUpdatedAt());
    }


    public function testCreateTag(): void
    {
        //given
        $user = $this->createUser('createTagTest');
        $this->httpClient->loginUser($user);

        $titleForCreateTag = 'titleTag';
        $tagRepository = static::getContainer()->get(TagRepository::class);

        $this->httpClient->request('GET', self::TEST_ROUTE.'/create');
        //when
        $this->httpClient->submitForm(
            'Zapisz',
            ['tag' => ['title' => $titleForCreateTag]]
        );

        //then
        $createdTag = $tagRepository->findOneByTitle($titleForCreateTag);
        $this->assertEquals($titleForCreateTag, $createdTag->getTitle());
    }

    public function testEditTag(): void
    {
        //given
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

        //when
        $this->httpClient->submitForm(
            'Save',
            ['tag' => ['title' => $expectedTagEditedTitle]]
        );

        //then
        $editedTag = $tagRepository->findOneById($tagToEdit->getId());
        $this->assertEquals($expectedTagEditedTitle, $editedTag->getTitle());
    }

    public function testDeleteTag(): void
    {
        //given
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

        //when
        $this->httpClient->submitForm(
            'Usun'
        );

        //then
        $this->assertNull($tagRepository->findOneByTitle('TestDeleteTag'));
//        $this->assertNull($tagRepository->findOneByCreatedAt($createdAt));
//        $this->assertNull($tagRepository->findOneByUpdatedAt($updatedAt));
    }

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