<?php

namespace App\Repository;

use App\Entity\Template;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Template|null find($id, $lockMode = null, $lockVersion = null)
 * @method Template|null findOneBy(array $criteria, array $orderBy = null)
 * @method Template[]    findAll()
 * @method Template[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Template::class);
    }

    public function findForListing()
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.checklists', 'c')
            ->leftJoin('c.createdBy', 'u')
            ->leftJoin('c.sections', 's')
            ->leftJoin('s.items', 'i')
            ->leftJoin('i.entries', 'e')
            ->addSelect('c')
            ->addSelect('u')
            ->addSelect('s')
            ->addSelect('i')
            ->addSelect('e')
            ->addOrderBy('t.name')
            ->getQuery()
            ->getResult();
    }
}
