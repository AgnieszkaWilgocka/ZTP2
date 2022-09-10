<?php
/**
 * Registration controller test.
 */

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\UserData;
use App\Repository\UserrDataRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class RegistrationControllerTest.
 */
class RegistrationControllerTest extends WebTestCase
{
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
     * Registration test.
     */
    public function testRegistration(): void
    {
        // given
        $emailForNewUser = 'register@example.com';
        $userRepository = static::getContainer()->get(UserRepository::class);

        $this->httpClient->request('GET', '/registration');

        // when
        $this->httpClient->submitForm(
            'Zapisz',
            [
                'registration' => [
                'email' => $emailForNewUser,
                'password' => [
                    'first' => '1234',
                    'second' => '1234',
                ],
                ], ]
        );

        // then
        $result = $userRepository->findOneByEmail('register@example.com');
        $this->assertEquals($emailForNewUser, $result->getEmail());
    }

    /**
     * Create user for tests.
     *
     * @param string   $name     Name
     * @param UserData $userData UserData
     *
     * @return User User
     */
    public function createUser(string $name, UserData $userData): User
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

    /**
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
