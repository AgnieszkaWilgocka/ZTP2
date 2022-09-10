<?php
/**
 * User controller test.
 */

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\UserData;
use App\Repository\UserrDataRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest.
 */
class UserControllerTest extends WebTestCase
{
    /**
     * Test route.
     *
     * @const string
     */
    public const TEST_ROUTE = '/user';

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
     * Show user test.
     */
    public function testShowUser(): void
    {
        // given
        $expectedStatusCode = 200;
        $userData = $this->createUserData('testShow');
        $user = $this->createUser('testShow', $userData);
        $this->httpClient->loginUser($user);

        $userId = $user->getId();

        // when
        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$userId);

        // then
        $result = $this->httpClient->getResponse()->getStatusCode();
        $this->assertEquals($expectedStatusCode, $result);
    }

    /**
     * Change password test.
     */
    public function testChangePassword(): void
    {
        // given
        $userData = $this->createUserData('nick');
        $user = $this->createUser('changePassword', $userData);
        $this->httpClient->loginUser($user);
        $userId = $user->getId();

        $this->httpClient->request('GET', self::TEST_ROUTE.'/'.$userId.'/edit');

        // when
        $this->httpClient->submitForm(
            'Zapisz',
            [
                'change_password' => [
                    'password' => [
                        'first' => '1234',
                        'second' => '1234',
                        ],
                    ], ]
        );

        // then
        $this->assertNotNull($user->getPassword());
    }

    /**
     * Create user for tests.
     *
     * @param string   $name     Name
     * @param UserData $userData UserData
     *
     * @return User User entity
     */
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
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }

    /**
     * Create user data for tests.
     *
     * @param string $nick Nick
     *
     * @return UserData UserData entity
     */
    private function createUserData(string $nick): UserData
    {
        $userData = new UserData();
        $userData->setNick($nick);

        $userDataRepository = static::getContainer()->get(UserrDataRepository::class);
        $userDataRepository->save($userData);

        return $userData;
    }
}
