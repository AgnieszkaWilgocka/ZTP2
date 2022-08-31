<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\UserData;
use App\Repository\UserrDataRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserDataControllerTest extends WebTestCase
{
    public const TEST_ROUTE = '/userData';

    private KernelBrowser $httpClient;

    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    public function testEditUserData(): void
    {
        //given
        $user = $this->createUser('edit');
        $this->httpClient->loginUser($user);

        $expectedUserData = new UserData();
        $expectedUserData->setNick('TestUserData');
        $userDataRepository = static::getContainer()->get(UserrDataRepository::class);
        $userDataRepository->save($expectedUserData);
        $expectedUserDataId = $expectedUserData->getId();
        $expectedUserDataEditedNick = 'EditedTitle';

        $this->httpClient->request('GET',self::TEST_ROUTE.'/'.$expectedUserDataId.'/edit');

        //when
        $this->httpClient->submitForm(
            'Save',
            ['userData' => ['nick' => $expectedUserDataEditedNick]]
        );

        //then
        $editedUserData = $userDataRepository->findOneById($expectedUserDataId);
        $this->assertEquals($expectedUserDataEditedNick, $editedUserData->getNick());

    }

    private function createUser(string $name): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail($name.'example.com');
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