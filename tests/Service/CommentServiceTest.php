<?php
/**
 * Comment service test
 */
namespace App\Test\Service;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\User;
use App\Service\BookService;
use App\Service\CategoryService;
use App\Service\CommentService;
use App\Service\UserService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class CommentServiceTest
 */
class CommentServiceTest extends KernelTestCase
{

    private ?EntityManagerInterface $entityManager;

    private ?CommentService $commentService;


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
        $this->commentService = $container->get(CommentService::class);

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
        $testUser = new User();
        $testUser->setEmail('testUser2@example.com');
        $testUser->setPassword('testUser1234');
        $testUser->setRoles(['ROLES_USER']);
        $this->entityManager->persist($testUser);
        $this->entityManager->flush();

        $testCategory = new Category();
        $testCategory->setCreatedAt(new \DateTimeImmutable());
        $testCategory->setUpdatedAt(new \DateTimeImmutable());
        $testCategory->setTitle('Category Test');
        $this->entityManager->persist($testCategory);
        $this->entityManager->flush();

        $testBook = new Book();
        $testBook->setCategory($testCategory);
        $testBook->setTitle('Book Test');
        $testBook->setAuthor('Author Test');
        $this->entityManager->persist($testBook);
        $this->entityManager->flush();

        $expectedComment = new Comment();
        $expectedComment->setBook($testBook);
        $expectedComment->setAuthor($testUser);
        $expectedComment->setContent('Comment Test');

        //when
        $this->commentService->save($expectedComment);

        //then
        $expectedCommentId = $expectedComment->getId();
        $resultComment = $this->entityManager->createQueryBuilder()
            ->select('comment')
            ->from(Comment::class, 'comment')
            ->where('comment.id = :id')
            ->setParameter(':id', $expectedCommentId, Types::INTEGER)
            ->getQuery()
            ->getSingleResult();

        $this->assertEquals($expectedComment->getContent(), $resultComment->getContent());
    }

    /**
     * Delete test
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testDelete(): void
    {
        //given
        $testUser = new User();
        $testUser->setEmail('testUser3@example.com');
        $testUser->setPassword('testUser1234');
        $testUser->setRoles(['ROLES_USER']);
        $this->entityManager->persist($testUser);
        $this->entityManager->flush();

        $testCategory = new Category();
        $testCategory->setCreatedAt(new \DateTimeImmutable());
        $testCategory->setUpdatedAt(new \DateTimeImmutable());
        $testCategory->setTitle('Category Test');
        $this->entityManager->persist($testCategory);
        $this->entityManager->flush();

        $testBook = new Book();
        $testBook->setCategory($testCategory);
        $testBook->setTitle('Book Test');
        $testBook->setAuthor('Author Test');
        $this->entityManager->persist($testBook);
        $this->entityManager->flush();

        $commentToDelete = new Comment();
        $commentToDelete->setContent('Comment Test');
        $commentToDelete->setBook($testBook);
        $commentToDelete->setAuthor($testUser);
        $this->entityManager->persist($commentToDelete);
        $this->entityManager->flush();
        $deletedCommentId = $commentToDelete->getId();

        //when
        $this->commentService->delete($commentToDelete);

        //then
        $resultComment = $this->entityManager->createQueryBuilder()
            ->select('comment')
            ->from(Comment::class, 'comment')
            ->where('comment.id = :id')
            ->setParameter(':id', $deletedCommentId, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        $this->assertNull($resultComment);

    }
}
