<?php

namespace App\Repository;

use App\Entity\Checklist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Checklist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Checklist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Checklist[]    findAll()
 * @method Checklist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChecklistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Checklist::class);
    }

    public function findForListing(string $fixedSort = null, string $fixedDirection = null)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.template', 't')
            ->leftJoin('c.createdBy', 'u')
            ->addSelect('t')
            ->addSelect('u')
            ->addOrderBy($fixedSort, $fixedDirection)
            ->getQuery()
            ->getResult();
    }

    public function findOneById(string $id): Checklist
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.sections', 's')
            ->leftJoin('s.items', 'i')
            ->leftJoin('i.entries', 'e')
            ->leftJoin('i.notes', 'n')
            ->addSelect('s')
            ->addSelect('i')
            ->addSelect('e')
            ->addSelect('n')
            ->andWhere('c.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByIdOrThrow(string $id): Checklist
    {
        $obj = $this->find($id);
        assert($obj !== null);
        return $obj;
    }

    // /**
    //  * @return Checklist[] Returns an array of ChecklistSession objects
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
    public function findOneBySomeField($value): ?ChecklistSession
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
