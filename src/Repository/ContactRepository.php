<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    /**
     * Trouve les messages non lus
     */
    public function findUnreadMessages(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isRead = :val')
            ->setParameter('val', false)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Trouve les messages récents (dernières 24h)
     */
    public function findRecentMessages(): array
    {
        $yesterday = new \DateTime('-1 day');
        
        return $this->createQueryBuilder('c')
            ->andWhere('c.createdAt >= :yesterday')
            ->setParameter('yesterday', $yesterday)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
