<?php

namespace App\Repository;

use App\Entity\Noter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Noter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Noter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Noter[]    findAll()
 * @method Noter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Noter::class);
    }

    // /**
    //  * @return Noter[] Returns an array of Noter objects
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

    /*
    public function findOneBySomeField($value): ?Noter
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
