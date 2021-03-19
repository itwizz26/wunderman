<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function findOneBySomeField ($field1, $value1): ?News
    {
        return $this->createQueryBuilder('n')
            ->where ('n.' . $field1 . ' = :val1')
            ->setParameter('val1', $value1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return array
     */
    public function getNewsAssoc(): array
    {
        return $this->createQueryBuilder('news', 'news.id')
            ->setFirstResult(1)
            ->setMaxResults(30)
            ->getQuery()
            ->getArrayResult();
    }

    // /**
    //  * @return News[] Returns an array of News objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
