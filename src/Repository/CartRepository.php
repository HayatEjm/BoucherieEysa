<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * 🗄️ REPOSITORY CART - Je gère les requêtes en base de données pour les paniers
 * 
 * POURQUOI CE REPOSITORY ?
 * - Je fournis des méthodes pour chercher, sauvegarder, supprimer les paniers
 * - J'encapsule les requêtes complexes pour ne pas les répéter
 * - Je suis le point d'entrée unique pour toutes les opérations sur Cart
 * 
 * MES RESPONSABILITÉS :
 * - Trouver un panier par session ID
 * - Nettoyer les vieux paniers
 * - Créer de nouveaux paniers
 * - Sauvegarder les modifications
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    /**
     * Je trouve ou crée un panier pour une session donnée
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Chaque visiteur a besoin d'un panier lié à sa session
     * - Si le panier existe déjà, je le retourne
     * - Sinon, j'en crée un nouveau
     * 
     * UTILISATION :
     * $cart = $cartRepository->findOrCreateBySessionId($sessionId);
     */
    public function findOrCreateBySessionId(string $sessionId): Cart
    {
        // Je cherche d'abord un panier existant pour cette session
        $cart = $this->findOneBy([
            'sessionId' => $sessionId,
            'status' => 'active' // Je ne veux que les paniers actifs
        ]);

        // Si je ne trouve pas de panier, j'en crée un nouveau
        if (!$cart) {
            $cart = new Cart();
            $cart->setSessionId($sessionId);
            $cart->setStatus('active');
            
            // Je sauvegarde immédiatement le nouveau panier
            $this->getEntityManager()->persist($cart);
            $this->getEntityManager()->flush();
        }

        return $cart;
    }

    /**
     * Je trouve un panier actif par session ID
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Plus spécifique que findOrCreate
     * - Retourne null si aucun panier actif n'existe
     * - Utile pour vérifier l'existence sans créer
     */
    public function findActiveBySessionId(string $sessionId): ?Cart
    {
        return $this->findOneBy([
            'sessionId' => $sessionId,
            'status' => 'active'
        ]);
    }

    /**
     * Je trouve tous les paniers abandonnés (anciens)
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour nettoyer la base de données des vieux paniers
     * - Un panier est considéré abandonné après X heures
     * - Permet de faire du ménage automatique
     * 
     * PARAMÈTRES :
     * - $hoursAgo : nombre d'heures pour considérer un panier comme abandonné
     */
    public function findAbandonedCarts(int $hoursAgo = 24): array
    {
        $cutoffDate = new \DateTime();
        $cutoffDate->modify("-{$hoursAgo} hours");

        return $this->createQueryBuilder('c')
            ->where('c.updatedAt < :cutoffDate')
            ->andWhere('c.status = :status')
            ->setParameter('cutoffDate', $cutoffDate)
            ->setParameter('status', 'active')
            ->getQuery()
            ->getResult();
    }

    /**
     * Je nettoie les paniers abandonnés
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour éviter que la base de données se remplisse de vieux paniers
     * - Je marque les paniers comme "abandoned" plutôt que de les supprimer
     * - Permet de garder des statistiques si besoin
     */
    public function cleanupAbandonedCarts(int $hoursAgo = 24): int
    {
        $cutoffDate = new \DateTime();
        $cutoffDate->modify("-{$hoursAgo} hours");

        // Je marque les paniers comme abandonnés plutôt que de les supprimer
        $query = $this->createQueryBuilder('c')
            ->update()
            ->set('c.status', ':abandonedStatus')
            ->where('c.updatedAt < :cutoffDate')
            ->andWhere('c.status = :activeStatus')
            ->setParameter('abandonedStatus', 'abandoned')
            ->setParameter('cutoffDate', $cutoffDate)
            ->setParameter('activeStatus', 'active')
            ->getQuery();

        return $query->execute(); // Retourne le nombre de paniers modifiés
    }

    /**
     * Je trouve les paniers les plus récents
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour afficher des statistiques d'administration
     * - Pour voir l'activité récente
     */
    public function findRecentCarts(int $limit = 10): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.updatedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Je compte le nombre de paniers actifs
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour des statistiques en temps réel
     * - Pour monitorer l'activité du site
     */
    public function countActiveCarts(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.status = :status')
            ->setParameter('status', 'active')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Je trouve les paniers non vides (qui ont des articles)
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour identifier les paniers avec du contenu
     * - Utile pour les statistiques de conversion
     */
    public function findNonEmptyCarts(): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.cartItems', 'ci') // Je joins avec les articles
            ->where('c.status = :status')
            ->setParameter('status', 'active')
            ->groupBy('c.id')
            ->having('COUNT(ci.id) > 0') // Au moins un article
            ->getQuery()
            ->getResult();
    }

    /**
     * Je sauvegarde un panier (méthode utilitaire)
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Centralise la logique de sauvegarde
     * - Met à jour automatiquement la date de modification
     * - Simplifie le code dans les services
     */
    public function save(Cart $cart, bool $flush = true): void
    {
        // Je mets à jour automatiquement la date de modification
        $cart->setUpdatedAt(new \DateTime());
        
        $this->getEntityManager()->persist($cart);
        
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Je supprime un panier
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour supprimer définitivement un panier
     * - Supprime aussi tous ses articles (cascade)
     */
    public function remove(Cart $cart, bool $flush = true): void
    {
        $this->getEntityManager()->remove($cart);
        
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Cart[] Returns an array of Cart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cart
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
