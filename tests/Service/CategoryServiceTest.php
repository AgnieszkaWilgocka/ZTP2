<?php
/**
 * Category service tests.
 */

namespace App\Tests\Service;

use App\Entity\Category;
use App\Service\CategoryService;
use App\Service\CategoryServiceInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CategoryServiceTest
 */
class CategoryServiceTest extends KernelTestCase
{

    private ?EntityManagerInterface $entityManager;

    /**
     * Category service.
     */
    private ?CategoryServiceInterface $categoryService;

    /**
     * Set up test
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->categoryService = $container->get(CategoryService::class);
    }

    /**
     * Test save
     *
     * @throws ORMException
     */
    public function testSave(): void
    {
        // given
        $expectedCategory = new Category();
        $expectedCategory->setTitle('Test Category');
        $expectedCategory->setCreatedAt(new \DateTimeImmutable());
        $expectedCategory->setUpdatedAt(new \DateTimeImmutable());

        // when
        $this->categoryService->save($expectedCategory);

        // then
        $expectedCategoryId = $expectedCategory->getId();
        $resultCategory = $this->entityManager->createQueryBuilder()
            ->select('category')
            ->from(Category::class, 'category')
            ->where('category.id = :id')
            ->setParameter(':id', $expectedCategoryId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedCategory, $resultCategory);
    }

    /**
     * Delete test
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testDelete(): void
    {
        //given
        $categoryToDelete = new Category();
        $categoryToDelete->setCreatedAt(new \DateTimeImmutable());
        $categoryToDelete->setUpdatedAt(new \DateTimeImmutable());
        $categoryToDelete->setTitle('Test Category');
        $this->entityManager->persist($categoryToDelete);
        $this->entityManager->flush();
        $deletedCategoryId = $categoryToDelete->getId();

        //when
        $this->categoryService->delete($categoryToDelete);

        //then
        $resultCategory = $this->entityManager->createQueryBuilder()
            ->select('category')
            ->from(Category::class, 'category')
            ->where('category.id = :id')
            ->setParameter('id', $deletedCategoryId,Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultCategory);
    }

    /**
     * Test get paginated list
     */
    public function testGetPaginatedList():void
    {
        //given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 3;

        $counter = 0;
        while ($counter < $dataSetSize) {
            $category = new Category();
            $category->setTitle('Test Category #'.$counter);
            $category->setUpdatedAt(new \DateTimeImmutable());
            $category->setCreatedAt(new \DateTimeImmutable());
            $this->categoryService->save($category);

            ++$counter;
        }

        //when
        $result = $this->categoryService->getPaginatedList($page);

        //then
        $this->assertEquals($expectedResultSize, $result->count());
    }

    public function testFindOneById(): void
    {
        //given
        $category = new Category();
        $category->setTitle('find_one_by_id_test');
        $category->setCreatedAt(new \DateTimeImmutable());
        $category->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $expectedCategoryId = $category->getId();

        //when
        $resultCategory = $this->categoryService->findOneById($expectedCategoryId);

        //then
        $this->assertEquals($expectedCategoryId, $resultCategory->getId());

    }
}
