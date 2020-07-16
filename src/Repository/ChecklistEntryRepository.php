<?php

namespace App\Repository;

use App\Entity\ChecklistEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChecklistEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChecklistEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChecklistEntry[]    findAll()
 * @method ChecklistEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChecklistEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChecklistEntry::class);
    }

    // /**
    //  * @return ChecklistEntry[] Returns an array of ChecklistEntry objects
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
    public function findOneBySomeField($value): ?ChecklistEntry
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
