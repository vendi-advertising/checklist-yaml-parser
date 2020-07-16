<?php

namespace App\Repository;

use App\Entity\ChecklistTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChecklistTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChecklistTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChecklistTemplate[]    findAll()
 * @method ChecklistTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChecklistTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChecklistTemplate::class);
    }

    // /**
    //  * @return ChecklistTemplate[] Returns an array of ChecklistTemplate objects
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
    public function findOneBySomeField($value): ?ChecklistTemplate
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
