<?php
/**
 * Book repository
 */
namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class BookRepository
 *
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    /**
     * Items per page
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 4;

    /**
     * Constructor
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Query all records
     *
     * @param array $filters
     *
     * @return QueryBuilder
     */
    public function queryAll(array $filters = []): QueryBuilder
    {
        $queryBuilder =  $this->getOrCreateQueryBuilder()
            ->select(
                'partial book.{id, author, title}',
                'partial category.{id, title}',
                'partial tags.{id, title}'
            )
            ->join('book.category', 'category')
            ->leftJoin('book.tags', 'tags')
            ->orderBy('book.title', 'ASC');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Apply filters function
     *
     * @param QueryBuilder $queryBuilder
     * @param array        $filters
     *
     * @return QueryBuilder
     */
    public function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {

        if (isset($filters['tag']) && $filters['tag'] instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters['tag']);
        }

        if (isset($filters['category']) && $filters['category'] instanceof Category) {
            $queryBuilder->andWhere('category IN (:category)')
                ->setParameter('category', $filters['category']);
        }

        return $queryBuilder;
    }


    /**
     * Get or create a new query builder
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('book');
    }

    /**
     * Action save
     *
     * @param Book $book
     *
     * @return void
     */
    public function save(Book $book): void
    {
        $this->_em->persist($book);
        $this->_em->flush($book);
    }

    /**
     * Action delete
     *
     * @param Book $book
     *
     * @return void
     */
    public function delete(Book $book): void
    {
        $this->_em->remove($book);
        $this->_em->flush();
    }

    /**
     * Count by category
     *
     * @param Category $category
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countByCategory(Category $category): int
    {
        $qb = $this->getOrCreateQueryBuilder();

        return $qb->select($qb->expr()->countDistinct('book.id'))
            ->where('book.category = :category')
            ->setParameter(':category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
