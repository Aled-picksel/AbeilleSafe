<?php

namespace App\Repository;

use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Report|null find($id, $lockMode = null, $lockVersion = null)
 * @method Report|null findOneBy(array $criteria, array $orderBy = null)
 * @method Report[]    findAll()
 * @method Report[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    public function findFilteredReportsByHive($hiveid, $number, $step)
    {
        return $this->createQueryBuilder('r')
            
            ->andWhere('MOD(r.id,:skipval) = 0')
            ->setParameter('skipval', $step)
            ->leftJoin('r.HiveReported', 'h')
            ->andWhere('h.id = :val')
            ->setParameter('val', $hiveid)
            ->orderBy('r.DateTime', 'DESC')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getCountByHive($hiveid){
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->leftJoin('r.HiveReported', 'h')
            ->where('h.id = :val')
            ->setParameter('val', $hiveid)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findReportsByHive($hiveid, $number)
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.HiveReported', 'h')
            ->andWhere('h.id = :val')
            ->setParameter('val', $hiveid)
            ->orderBy('r.DateTime', 'DESC')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findReportsByHiveBetweenDates($hiveid, $begin, $end)
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.HiveReported', 'h')
            ->andWhere('h.id = :val')
            ->setParameter('val', $hiveid)
            ->andWhere('r.DateTime BETWEEN :dateb AND :datee')
            ->setParameter('dateb', $begin)
            ->setParameter('datee', $end)
            ->orderBy('r.DateTime', 'ASC') // !! Ordre croissant pour l'analyse
            ->getQuery()
            ->getResult()
        ;
    }

    function findLastReport($hiveid) {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.HiveReported', 'h')
            ->andWhere('h.id = :hid')
            ->setParameter('hid', $hiveid)
            ->orderBy('r.DateTime', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;      
    }
    

    /*
    public function findOneBySomeField($value): ?Report
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
