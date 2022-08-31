<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $httpClient;

    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    public function testLogInRoute(): void
    {
        //given
        $expectedStatusCode = 200;

        //when
        $this->httpClient->request('GET', '/login');

        //then
        $result = $this->httpClient->getResponse()->getStatusCode();

        $this->assertEquals($expectedStatusCode, $result);
    }

    public function testLogOutRoute(): void
    {
        $expectedStatusCode = 302;
//        $user = null;
//        try {
//            $user = $this->createUser('logout');
//        } catch (OptimisticLockException|NotFoundExceptionInterface|ContainerExceptionInterface|ORMException $e){
//
//        }
//        $user = $this->createUser('logoutTest');
//        $this->httpClient->loginUser($user);

        //when
        $this->httpClient->request('GET', '/logout');

        //then
        $result = $this->httpClient->getResponse()->getStatusCode();


        $this->assertEquals($expectedStatusCode, $result);
//        $this->assertResponseRedirects('');
    }

//    private function createUser(string $name): User
//    {
//        $passwordHasher = static::getContainer()->get('security.password_hasher');
//        $userRepository = static::getContainer()->get(UserRepository::class);
//        $user = new User();
//        $user->setRoles(['ROLE_ADMIN']);
//        $user->setEmail($name.'example.com');
//        $user->setPassword(
//            $passwordHasher->hashPassword(
//                $user,
//                '1234'
//            )
//        );
//        $userRepository->save($user);
//
//        return $user;
//    }
}