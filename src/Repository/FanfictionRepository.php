<?php

namespace App\Repository;

use App\Entity\Fanfiction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fanfiction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fanfiction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fanfiction[]    findAll()
 * @method Fanfiction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FanfictionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fanfiction::class);
    }

    public function findBestFanfics($limit){
        return $this->createQueryBuilder('f')
                    ->select('f as fanfiction, AVG(c.rating) as avgRatings')
                    ->join('f.comments', 'c')
                    ->groupBy('f')
                    ->orderBy('avgRatings', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult()
        ;
    }

    public function findRecentUpload($limit){
        return $this->createQueryBuilder('f')
                    ->select('f as fanfiction, c.addedAt as uploadDate')
                    ->join('f.chapters', 'c')
                    ->groupBy('f')
                    ->orderBy('uploadDate', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult()
        ;
    }

    // /**
    //  * @return Fanfiction[] Returns an array of Fanfiction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fanfiction
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
