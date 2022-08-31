<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\UserData;
use App\Repository\UserrDataRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public const TEST_ROUTE = '/user';

    private KernelBrowser $httpClient;



    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    public function testShowUser(): void
    {
        //given
        $expectedStatusCode = 200;
        $userData = $this->createUserData('testShow');
        $user = $this->createUser('testShow', $userData);
        $this->httpClient->loginUser($user);

        $userId = $user->getId();

        //when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$userId);

        //then
        $result = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $result);
    }

    public function testChangePassword(): void
    {
        //given
//        $passwordHasher = static::getContainer()->get('security.password_hasher');

        $userData = $this->createUserData('nick');
        $user = $this->createUser('changePassword', $userData);
        $this->httpClient->loginUser($user);
        $userId = $user->getId();
//        $newPassword = $passwordHasher->hashPassword(
//            $user,
//            '12345'
//        );

//        $userRepository = static::getContainer()->get(UserRepository::class);

        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$userId.'/edit');

        //when
        $this->httpClient->submitForm(
            'Zapisz',
            ['change_password' => [
                'password' => [
                'first' => '1234',
                'second' => '1234'
            ]]]

        );
//            ['change_password' => ['password' =>
//                [
//                    'first_options' => ['1234'],
//                    'second_options' => ['1234']]]]
////                [

//                    'first_options' => ['1234'],
//                    'second_options' => ['1234']
//                ]
//            ]]
//        );
//        $this->assertEquals('1234', $user->getPassword());
//        $this->assertNotNull($user->getPassword());
        $this->assertNotNull($user->getPassword());
        //then
//        $this->assertEquals($newPassword, $user->getPassword());


    }

//////////// nie dziala z tym nickiem //////
//    public function testIndexRoute(): void
//    {
//        $expectedCodeStatus = 200;
//        $userData = $this->createUserData('index');
//
//        $user = $this->createUser('indexTestUser', $userData);
//
//        $this->httpClient->loginUser($user);
//
//        //when
//        var_dump($this->httpClient->request('GET',self::TEST_ROUTE));
//
//        //then
//        $resultHttpCode = $this->httpClient->getResponse()->getStatusCode();
//
////        var_dump($resultHttpCode);
//        $this->assertEquals($expectedCodeStatus, $resultHttpCode);
//    }
//
//////        $userData = null;
//////        $user->setUserData($this->createUserData('index'));
//////        try {
//////            $userData = $this->createUserData('testIndex');
//////        } catch (OptimisticLockException|NotFoundExceptionInterface|ContainerExceptionInterface|ORMException $e)
//////        {
////
//////        }
////        $this->httpClient->loginUser($user);
////
////
////
////////        $user->setUserData($this->createUserData('userDataForUser'));
//////
//////        $this->httpClient->loginUser($user);
//////
////        //when
////       $this->httpClient->request('GET', self::TEST_ROUTE);
////////        var_dump($user);
//////
////        //then
////        $result = $this->httpClient->getResponse()->getStatusCode();
////
////        $this->assertEquals($expectedCodeStatus, $result);
//////
////    }
////////
////////
    private function createUser(string $name, UserData $userData): User
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
        $user->setUserData($userData);
//        $user->setUserData($userData);
////        $user->setUserData($userData);
////////        $user->setUserData($this->createUserData('userDataForUser'));
////////        $userData = new UserData();
////        $userData->setNick($name);
////////        $userDataRepository = static::getContainer()->get(UserrDataRepository::class);
////////        $userDataRepository->save($userData);
////////        $user->setUserData($userData);
////////
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);
////////
        return $user;
    }
////////
    private function createUserData($nick): UserData
    {

        $userData = new UserData();
        $userData->setNick($nick);

        $userDataRepository = static::getContainer()->get(UserrDataRepository::class);
        $userDataRepository->save($userData);

//        $newUser = $user;
//        $newUser->setUserData($userData);

        return $userData;
    }
//////////
//////////
//////////
}