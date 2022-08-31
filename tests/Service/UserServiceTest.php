<?php


namespace App\Tests\Service;

use App\Entity\User;
use App\Entity\UserData;
use App\Service\UserDataService;
use App\Service\UserService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserServiceTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    private ?UserService $userService;

    private ?UserDataService $userDataService;

    public function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->userService = $container->get(UserService::class);
        $this->userDataService = $container->get(UserDataService::class);
    }


    public function testPaginatedList(): void
    {
        //given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;
        $passwordHasher = static::getContainer()->get('security.password_hasher');

        $counter = 0;
        while ($counter < $dataSetSize) {
            $user = new User();
            $user->setRoles(['ROLE_ADMIN']);
            $user->setEmail('testPaginatedList@example.com#'.$counter);
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    '1234'
                )
            );
            $user->setUserData($this->createUserData('testPaginatedList'.$counter));
            $this->userService->save($user);

            ++$counter;
        }

        //when
        $result = $this->userService->getPaginatedList($page);

        //then
        $this->assertEquals($expectedResultSize, $result->count());
    }


    public function testSave(): void
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail('testSave@example.com');
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                '1234'
            )
        );
        $userData = $this->createUserData('testSave');
        $user->setUserData($userData);

        //when
        $this->userService->save($user);
        $userId = $user->getId();

        //then
        $result = $this->entityManager->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user')
            ->where('user.id = :id')
            ->setParameter(':id', $userId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($user, $result);


    }

    public function testDelete(): void
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail('testDelete@example.com');
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                '1234'
            )
        );
        $userData = $this->createUserData('testDelete');
        $user->setUserData($userData);
        $this->userService->save($user);
        $userId = $user->getId();

        //when
        $this->userService->delete($user);

        //then
        $result = $this->entityManager->createQueryBuilder()
            ->select('user')
            ->from(User::class, 'user')
            ->where('user.id = :id')
            ->setParameter(':id', $userId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($result);
    }

    private function createUserData($nick): UserData
    {
        $userData = new UserData();
        $userData->setNick($nick);
        $this->userDataService->save($userData);

        return $userData;
    }

}