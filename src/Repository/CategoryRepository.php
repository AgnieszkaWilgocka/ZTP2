<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    /**
     * Items per page
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 3;


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Query all records
     *
     * @return QueryBuilder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial category.{id, title, createdAt, updatedAt}',
//                'partial books.{id, title, author}'
            )
            ->leftJoin('category.books', 'books')
            ->orderBy('category.title', 'DESC');
    }

    /**
     * Get or create new query builder
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('category');
    }

    public function save(Category $category): void
    {
        $this->_em->persist($category);
        $this->_em->flush($category);
    }

    public function delete(Category $category): void
    {
        $this->_em->remove($category);
        $this->_em->flush($category);
    }

    /**
     * @param Book $book
     * @return int
     *
     * @throws  NoResultException
     * @throws NonUniqueResultException
     */
//    public function countByBook(Book $book): int
//    {
//        $qb = $this->getOrCreateQueryBuilder();
//
//        return $qb->select($qb->expr()->countDistinct('category.id'))
//            ->where('category.books = :book')
//            ->setParameter(':book', $book)
//            ->getQuery()
//            ->getSingleScalarResult();
//    }




//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}