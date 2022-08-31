<?php

namespace App\Tests\Service;

use App\Entity\UserData;
use App\Service\UserDataService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserDataServiceTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    private ?UserDataService $userDataService;

    public function setUp(): void
    {
        $this->entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
        $this->userDataService = static::getContainer()->get(UserDataService::class);
    }

    public function testUserDataSave(): void
    {
        //given
        $expectedUserData = new UserData();
        $expectedUserData->setNick('testUserData');

        //when
        $this->userDataService->save($expectedUserData);


        //then
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
