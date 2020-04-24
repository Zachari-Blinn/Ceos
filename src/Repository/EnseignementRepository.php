<?php

namespace App\Repository;

use App\Entity\Enseignement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Enseignement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enseignement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enseignement[]    findAll()
 * @method Enseignement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnseignementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enseignement::class);
    }

    // /**
    //  * @return Enseignement[] Returns an array of Enseignement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Enseignement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     *    @return Enseignement [] 
     */
    public function findGroupBy($profId)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.prof = :id')
            ->setParameter('id', $profId)
            ->groupBy('e.classe')
            ->getQuery()
            ->getResult();
    }

    // public function findMatiereByProf($prof)
    // {
    //     return $this->createQueryBuilder('enseignement')
    //         ->addSelect('prof.id', 'matiere.NomMatiere')
    //         ->andWhere('enseignement.prof = :prof')
    //         ->leftJoin('enseignement.matiere', 'matiere')
    //         ->leftJoin('enseignement.prof', 'prof')
    //         ->setParameter('prof', $prof)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
}
