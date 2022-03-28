<?php

namespace App\Repository;

use App\Entity\AlertType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AlertType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlertType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlertType[]    findAll()
 * @method AlertType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlertTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlertType::class);
    }

    // /**
    //  * @return AlertType[] Returns an array of AlertType objects
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

    
    public function findOneByDanger($value): ?AlertType
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.Danger = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
