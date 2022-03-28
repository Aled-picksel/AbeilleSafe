<?php

namespace App\Repository;

use App\Entity\Alert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Alert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alert[]    findAll()
 * @method Alert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alert::class);
    }

    /**
     * @return Alert[] Returns an array of Alert objects
     */
    
    public function findAlertsByOwner($userid)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.Owner', 'u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $userid)
            ->orderBy('a.EmissionDateTime', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getCountNotDismissedAlertsByOwner($userid)
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.IsDismissed = false')
            ->leftJoin('a.Owner', 'u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $userid)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
