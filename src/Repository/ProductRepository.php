<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Recherche de produits par nom avec tri par pertinence (version simple)
     */
    public function searchByName(string $query, int $limit = 10): array
    {
        // D'abord chercher ceux qui commencent par la recherche
        $qb = $this->createQueryBuilder('p');
        
        return $qb
            ->andWhere('p.name LIKE :query OR p.description LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            // Tri simple : ceux qui commencent par la recherche en premier
            ->addOrderBy('
                CASE 
                    WHEN LOWER(p.name) LIKE :startQuery THEN 0
                    ELSE 1
                END
            ', 'ASC')
            ->addOrderBy('LENGTH(p.name)', 'ASC')
            ->addOrderBy('p.name', 'ASC')
            ->setParameter('startQuery', strtolower($query) . '%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les produits avec un stock faible
     */
    public function findLowStockProducts(int $threshold = 5): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.stock <= :threshold')
            ->andWhere('p.stock >= 0') // Exclure les stocks négatifs
            ->setParameter('threshold', $threshold)
            ->orderBy('p.stock', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte les produits avec un stock faible
     */
    public function countLowStockProducts(int $threshold = 5): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->andWhere('p.stock <= :threshold')
            ->andWhere('p.stock >= 0')
            ->setParameter('threshold', $threshold)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
