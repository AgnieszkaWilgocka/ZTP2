<?php

namespace App\Repository;

use App\Entity\Favourite;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favourite>
 *
 * @method Favourite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favourite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favourite[]    findAll()
 * @method Favourite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavouriteRepository extends ServiceEntityRepository
{
    /**
     * Items per page
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE = 3;


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favourite::class);
    }




    /**
     * @return QueryBuilder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('favourite.book', 'DESC');
    }

    /**
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('favourite');
    }

    /**
     * @param User $user
     *
     * @return QueryBuilder
     */
    public function queryByAuthor(User $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('favourite.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }

    /**
     * @param Favourite $favourite
     *
     * @return void
     */
    public function save(Favourite $favourite): void
    {
        $this->_em->persist($favourite);
        $this->_em->flush($favourite);
    }

    /**
     * @param Favourite $favourite
     *
     * @return void
     */
    public function delete(Favourite $favourite): void
    {
        $this->_em->remove($favourite);
        $this->_em->flush($favourite);
    }
//    /**
//     * @return Favourite[] Returns an array of Favourite objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Favourite
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
