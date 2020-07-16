<?php

namespace App\Repository;

use App\Entity\ChecklistItemGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChecklistItemGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChecklistItemGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChecklistItemGroup[]    findAll()
 * @method ChecklistItemGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChecklistItemGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChecklistItemGroup::class);
    }

    // /**
    //  * @return ChecklistGroup[] Returns an array of ChecklistGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ChecklistGroup
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
