<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\UserData;
use App\Repository\UserrDataRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $httpClient;

    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    public function testRegistration(): void
    {
//        $passwordHasher = static::getContainer()->get('security.password_hasher');
//        $passwordForUser = $passwordHasher->hashPassword('1234');
        $emailForNewUser = 'register@example.com';
        $userRepository = static::getContainer()->get(UserRepository::class);

        $this->httpClient->request('GET', '/registration');

        //then
        $this->httpClient->submitForm(
            'Zapisz',
            ['registration' => [
                'email' => $emailForNewUser,
                'password' => [
                    'first' => '1234',
                    'second' => '1234'
                ]

//                'password' => ['first_options' => '1234'],
//                    ['second_options' => '1234']
                ]]
        );

//        $resultHttpCode = $this->httpClient->getResponse()->getStatusCode();
        $result = $userRepository->findOneByEmail('register@example.com');
        $this->assertEquals($emailForNewUser, $result->getEmail());
//        $this->assertEquals(200, $resultHttpCode);

    }


//    public function testRegister(): void
//    {
//        $userData = $this->createUserData('register');
//
//
//        $user = $this->createUser('register', $userData);
////        $this->httpClient->loginUser($user);
////        $user->setUserData($userData);
//
//        $userRepository = static::getContainer()->get(UserRepository::class);
//        //when
//        $this->httpClient->request('GET', '/registration');
//
//        //then
//        $result = $this->httpClient->getResponse()->getStatusCode();
//        $this->assertEquals($user, $userRepository->findOneByEmail('register@example.com'));
//        $this->assertEquals(200, $result);
//
//    }

    public function createUser($name, UserData $userData): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');

        $user = new User();
        $user->setEmail($name.'@example.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                '1234'
            )
        );
        $user->setUserData($userData);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }

    private function createUserData($nick): UserData
    {
        $userData = new UserData();
        $userData->setNick($nick);

        $userDataRepository = static::getContainer()->get(UserrDataRepository::class);
        $userDataRepository->save($userData);

        return $userData;
    }
}