<?php
namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * Récupère toutes les commandes d'un utilisateur (relation User)
     *
     * @param \App\Entity\User $user
     * @return Order[]
     */
    public function findByUser($user): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.user = :user')
            ->setParameter('user', $user)
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Order[] Returns an array of Order objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Order
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Compte le nombre de commandes pour une date et un créneau donnés
     */
    public function countByDateAndTimeSlot(\DateTime $date, string $timeSlot): int
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.pickupDate = :date')
            ->andWhere('o.pickupTimeSlot = :timeSlot')
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('timeSlot', $timeSlot)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Compte le nombre de commandes pour une date et une heure précise (créneaux 30min)
     */
    public function countByDateAndTime(\DateTime $date, string $time): int
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.pickupDate = :date')
            ->andWhere('o.pickupTimeSlot = :time')
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('time', $time)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Compte le nombre de commandes pour une date donnée
     */
    public function countOrdersByDate(\DateTime $date): int
    {
        $startOfDay = clone $date;
        $startOfDay->setTime(0, 0, 0);
        
        $endOfDay = clone $date;
        $endOfDay->setTime(23, 59, 59);

        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.createdAt >= :start')
            ->andWhere('o.createdAt <= :end')
            ->setParameter('start', $startOfDay)
            ->setParameter('end', $endOfDay)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Trouve les commandes récentes (limité)
     */
    public function findRecentOrders(int $limit = 5): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte les commandes par statut
     */
    public function countByStatus(string $status): int
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
