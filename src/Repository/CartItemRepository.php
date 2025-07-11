<?php

namespace App\Repository;

use App\Entity\CartItem;
use App\Entity\Cart;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * 🛒 REPOSITORY CARTITEM - Je gère les requêtes pour les articles du panier
 * 
 * POURQUOI CE REPOSITORY ?
 * - Je fournis des méthodes pour gérer les articles dans les paniers
 * - J'évite la duplication de code pour les requêtes complexes
 * - Je centralise la logique de recherche des articles
 * 
 * MES RESPONSABILITÉS :
 * - Trouver un article par panier et produit
 * - Calculer les totaux par panier
 * - Gérer l'ajout/suppression d'articles
 * - Fournir des statistiques
 */
class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartItem::class);
    }

    /**
     * Je trouve un article spécifique dans un panier
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Avant d'ajouter un produit, je vérifie s'il existe déjà
     * - Si oui, j'augmente la quantité au lieu de créer un doublon
     * - Évite d'avoir 2 lignes pour le même produit
     * 
     * UTILISATION :
     * $existingItem = $repo->findByCartAndProduct($cart, $product);
     * if ($existingItem) { $existingItem->increaseQuantity(); }
     */
    public function findByCartAndProduct(Cart $cart, Product $product): ?CartItem
    {
        return $this->findOneBy([
            'cart' => $cart,
            'product' => $product
        ]);
    }

    /**
     * Je trouve tous les articles d'un panier, triés par date d'ajout
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour afficher les articles dans l'ordre où ils ont été ajoutés
     * - Plus user-friendly que l'ordre par ID
     * - Le dernier ajouté apparaît en premier
     */
    public function findByCartOrderedByDate(Cart $cart): array
    {
        return $this->createQueryBuilder('ci')
            ->where('ci.cart = :cart')
            ->setParameter('cart', $cart)
            ->orderBy('ci.addedAt', 'DESC') // Le plus récent en premier
            ->getQuery()
            ->getResult();
    }

    /**
     * Je calcule le total en centimes pour un panier
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Plus rapide qu'une boucle PHP sur tous les articles
     * - Calcul direct en base de données
     * - Évite de charger tous les objets en mémoire
     * 
     * UTILISATION :
     * $totalCents = $repo->calculateCartTotalCents($cart);
     */
    public function calculateCartTotalCents(Cart $cart): int
    {
        $result = $this->createQueryBuilder('ci')
            ->select('SUM(ci.quantity * ci.unitPriceCents) as total')
            ->where('ci.cart = :cart')
            ->setParameter('cart', $cart)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) ($result ?? 0); // Je retourne 0 si le panier est vide
    }

    /**
     * Je calcule la quantité totale d'articles dans un panier
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour afficher le badge du panier (nombre d'articles)
     * - Calcul direct en base sans charger les objets
     * - Plus performant pour juste obtenir un nombre
     */
    public function calculateCartTotalQuantity(Cart $cart): int
    {
        $result = $this->createQueryBuilder('ci')
            ->select('SUM(ci.quantity) as total')
            ->where('ci.cart = :cart')
            ->setParameter('cart', $cart)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) ($result ?? 0);
    }

    /**
     * Je trouve les produits les plus ajoutés au panier
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour des statistiques de vente
     * - Identifier les produits populaires
     * - Recommandations produits
     */
    public function findMostAddedProducts(int $limit = 10): array
    {
        return $this->createQueryBuilder('ci')
            ->select('ci.product, SUM(ci.quantity) as totalQuantity')
            ->groupBy('ci.product')
            ->orderBy('totalQuantity', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Je supprime tous les articles d'un panier
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour vider complètement un panier
     * - Plus rapide qu'une boucle de suppression
     * - Opération atomique en base
     * 
     * UTILISATION :
     * $repo->clearCart($cart); // Panier vidé
     */
    public function clearCart(Cart $cart): int
    {
        $query = $this->createQueryBuilder('ci')
            ->delete()
            ->where('ci.cart = :cart')
            ->setParameter('cart', $cart)
            ->getQuery();

        return $query->execute(); // Retourne le nombre d'articles supprimés
    }

    /**
     * Je trouve les articles récemment ajoutés
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour afficher "Récemment ajouté au panier"
     * - Animations de notification
     * - Feedback utilisateur
     */
    public function findRecentlyAdded(Cart $cart, int $minutes = 5): array
    {
        $cutoffDate = new \DateTime();
        $cutoffDate->modify("-{$minutes} minutes");

        return $this->createQueryBuilder('ci')
            ->where('ci.cart = :cart')
            ->andWhere('ci.addedAt > :cutoffDate')
            ->setParameter('cart', $cart)
            ->setParameter('cutoffDate', $cutoffDate)
            ->orderBy('ci.addedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Je compte le nombre d'articles différents dans un panier
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour savoir combien de produits différents (pas la quantité totale)
     * - Utile pour les messages "3 produits dans votre panier"
     */
    public function countDistinctProductsInCart(Cart $cart): int
    {
        return $this->createQueryBuilder('ci')
            ->select('COUNT(DISTINCT ci.product)')
            ->where('ci.cart = :cart')
            ->setParameter('cart', $cart)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Je trouve les articles avec une quantité spécifique
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour identifier les articles en faible quantité
     * - Proposer d'augmenter la quantité
     * - Gestion des stocks
     */
    public function findItemsWithQuantity(Cart $cart, int $quantity): array
    {
        return $this->createQueryBuilder('ci')
            ->where('ci.cart = :cart')
            ->andWhere('ci.quantity = :quantity')
            ->setParameter('cart', $cart)
            ->setParameter('quantity', $quantity)
            ->getQuery()
            ->getResult();
    }

    /**
     * Je sauvegarde un article (méthode utilitaire)
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Centralise la logique de sauvegarde
     * - Simplifie le code dans les services
     * - Cohérence dans l'application
     */
    public function save(CartItem $cartItem, bool $flush = true): void
    {
        $this->getEntityManager()->persist($cartItem);
        
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Je supprime un article
     * 
     * POURQUOI CETTE MÉTHODE ?
     * - Pour retirer un produit du panier
     * - Centralise la logique de suppression
     */
    public function remove(CartItem $cartItem, bool $flush = true): void
    {
        $this->getEntityManager()->remove($cartItem);
        
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CartItem[] Returns an array of CartItem objects
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

//    public function findOneBySomeField($value): ?CartItem
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
