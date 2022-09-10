<?php
/**
 * UserData service test.
 */

namespace App\Tests\Service;

use App\Entity\UserData;
use App\Service\UserDataService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class UserDataServiceTest.
 */
class UserDataServiceTest extends KernelTestCase
{
    /**
     * Test entity manager.
     *
     * @var EntityManagerInterface|object|null
     */
    private ?EntityManagerInterface $entityManager;

    /**
     * Test user data service.
     *
     * @var UserDataService|object|null
     */
    private ?UserDataService $userDataService;

    /**
     * Set up test.
     */
    public function setUp(): void
    {
        $this->entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $this->userDataService = static::getContainer()->get(UserDataService::class);
    }

    /**
     * UserData save test.
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testUserDataSave(): void
    {
        // given
        $expectedUserData = new UserData();
        $expectedUserData->setNick('testUserData');

        // when
        $this->userDataService->save($expectedUserData);

        // then
        $expectedUserDataId = $expectedUserData->getId();
        $resultUserData = $this->entityManager->createQueryBuilder()
            ->select('userData')
            ->from(UserData::class, 'userData')
            ->where('userData.id = :id')
            ->setParameter(':id', $expectedUserDataId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedUserData, $resultUserData);
    }
}
