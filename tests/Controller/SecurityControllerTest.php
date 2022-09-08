<?php
/**
 * Security controller test
 */
namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SecurityControllerTest
 */
class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $httpClient;

    /**
     * Set up test
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Test log in route
     */
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

    /**
     * Test log out route
     */
    public function testLogOutRoute(): void
    {
        //given
        $expectedStatusCode = 302;

        //when
        $this->httpClient->request('GET', '/logout');

        //then
        $result = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $result);
    }
}
