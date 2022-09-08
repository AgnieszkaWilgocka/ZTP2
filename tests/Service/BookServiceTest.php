<?php
/**
 * Book service test
 */
namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\Category;
use App\Service\BookService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class BookServiceTest
 */
class BookServiceTest extends KernelTestCase
{

    private ?EntityManagerInterface $entityManager;

    private ?BookService $bookService;

    /**
     * Set up test
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
        $this->bookService = $container->get(BookService::class);
    }

    /**
     * Save test
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testSave(): void
    {
        //given
        $testCategory = new Category();
        $testCategory->setCreatedAt(new \DateTimeImmutable());
        $testCategory->setUpdatedAt(new \DateTimeImmutable());
        $testCategory->setTitle('Category Test10');
        $this->entityManager->persist($testCategory);
        $this->entityManager->flush();

        $expectedBook = new Book();
        $expectedBook->setCategory($testCategory);
        $expectedBook->setTitle('Test Book');
        $expectedBook->setAuthor('Test Author');

        //when
        $this->bookService->save($expectedBook);

        //then
        $expectedBookId = $expectedBook->getId();
        $resultBook = $this->entityManager->createQueryBuilder()
            ->select('book')
            ->from(Book::class, 'book')
            ->where('book.id = :id')
            ->setParameter(':id', $expectedBookId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedBook, $resultBook);
    }

    /**
     * Delete test
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testDelete(): void
    {
        //given
        $testCategoryForBook = new Category();
        $testCategoryForBook->setCreatedAt(new \DateTimeImmutable());
        $testCategoryForBook->setUpdatedAt(new \DateTimeImmutable());
        $testCategoryForBook->setTitle('Test Category');
        $this->entityManager->persist($testCategoryForBook);
        $this->entityManager->flush();

        $bookToDelete = new Book();
        $bookToDelete->setCategory($testCategoryForBook);
        $bookToDelete->setTitle('Book Test');
        $bookToDelete->setAuthor('Test Author');
        $this->entityManager->persist($bookToDelete);
        $this->entityManager->flush();
        $deletedBookId = $bookToDelete->getId();

        //when
        $this->bookService->delete($bookToDelete);

        //then
        $resultBook = $this->entityManager->createQueryBuilder()
            ->select('book')
            ->from(Book::class, 'book')
            ->where('book.id = :id')
            ->setParameter(':id', $deletedBookId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultBook);
    }
}
