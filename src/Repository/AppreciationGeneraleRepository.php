<?php

namespace App\Repository;

use App\Entity\AppreciationGenerale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AppreciationGenerale|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppreciationGenerale|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppreciationGenerale[]    findAll()
 * @method AppreciationGenerale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppreciationGeneraleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppreciationGenerale::class);
    }

    // /**
    //  * @return AppreciationGenerale[] Returns an array of AppreciationGenerale objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AppreciationGenerale
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
