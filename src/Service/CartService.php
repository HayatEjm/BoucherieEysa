<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\CartItemRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;

/**
 * 🛒 SERVICE CARTSERVICE - Je gère toute la logique métier du panier
 * 
 * POURQUOI CE SERVICE ?
 * - Je centralise toute la logique complexe du panier
 * - J'évite d'avoir du code métier dans les Controllers
 * - Je suis réutilisable partout dans l'application
 * - Je gère la session automatiquement
 * 
 * MES RESPONSABILITÉS :
 * - Récupérer le panier de la session courante
 * - Ajouter/retirer des produits intelligemment
 * - Calculer les totaux
 * - Gérer les quantités
 * - Vider le panier
 * - Synchroniser avec la base de données
 */
class CartService
{
    public function __construct(
        private CartRepository $cartRepository,
        private CartItemRepository $cartItemRepository,
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack
    ) {
        // J'injecte toutes mes dépendances pour pouvoir travailler
        // - CartRepository : pour gérer les paniers
        // - CartItemRepository : pour gérer les articles
        // - EntityManager : pour sauvegarder en base
        // - RequestStack : pour accéder à la session
    }

    /**
     * Je récupère le panier de l'utilisateur actuel
     * 
     * COMMENT JE FONCTIONNE :
     * 1. Je récupère l'ID de session de l'utilisateur
     * 2. Je cherche son panier en base ou j'en crée un nouveau
     * 3. Je le retourne prêt à être utilisé
     * 
     * POURQUOI C'EST PRATIQUE :
     * - L'utilisateur n'a pas besoin de compte pour avoir un panier
     * - Son panier persiste tant que sa session existe
     * - Je gère automatiquement la création/récupération
     */
    public function getCurrentCart(): Cart
    {
        // Je récupère la session de l'utilisateur
        $session = $this->requestStack->getCurrentRequest()?->getSession();
        
        if (!$session) {
            // Si pas de session, je ne peux pas créer de panier
            throw new \RuntimeException('Impossible de récupérer la session pour le panier');
        }

        // Je génère un ID de session unique si il n'existe pas
        if (!$session->has('cart_session_id')) {
            $session->set('cart_session_id', uniqid('cart_', true));
        }
        
        $sessionId = $session->get('cart_session_id');
        
        // Je trouve ou crée le panier pour cette session
        return $this->cartRepository->findOrCreateBySessionId($sessionId);
    }

    /**
     * J'ajoute un produit au panier intelligemment
     * 
     * COMMENT JE FONCTIONNE :
     * 1. Je vérifie si le produit est déjà dans le panier
     * 2. Si oui, j'augmente la quantité
     * 3. Si non, je crée un nouvel article
     * 4. Je recalcule le total du panier
     * 5. Je sauvegarde tout en base
     * 
     * POURQUOI C'EST INTELLIGENT :
     * - Pas de doublons dans le panier
     * - Quantités automatiquement gérées
     * - Total toujours à jour
     */
    public function addProduct(Product $product, int $quantity = 1): CartItem
    {
        // Je valide que la quantité est positive
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('La quantité doit être positive');
        }

        $cart = $this->getCurrentCart();
        
        // Je cherche si ce produit est déjà dans le panier
        $existingItem = $this->cartItemRepository->findByCartAndProduct($cart, $product);
        
        if ($existingItem) {
            // Le produit existe déjà, j'augmente la quantité
            $existingItem->increaseQuantity($quantity);
            $cartItem = $existingItem;
        } else {
            // Nouveau produit, je crée un nouvel article
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity($quantity);
            $cartItem->setUnitPrice($product->getPrice()); // Je copie le prix actuel
            
            // J'ajoute l'article au panier
            $cart->addCartItem($cartItem);
        }
        
        // Je recalcule le total du panier et je sauvegarde
        $this->updateCartTotal($cart);
        
        return $cartItem;
    }

    /**
     * Je retire un produit du panier
     * 
     * COMMENT JE FONCTIONNE :
     * 1. Je trouve l'article dans le panier
     * 2. Je le supprime complètement
     * 3. Je recalcule le total
     * 4. Je sauvegarde
     */
    public function removeProduct(Product $product): bool
    {
        $cart = $this->getCurrentCart();
        $cartItem = $this->cartItemRepository->findByCartAndProduct($cart, $product);
        
        if (!$cartItem) {
            return false; // Le produit n'était pas dans le panier
        }
        
        // Je retire l'article du panier et je le supprime
        $cart->removeCartItem($cartItem);
        $this->cartItemRepository->remove($cartItem, false); // Je ne flush pas encore
        
        // Je recalcule le total et je sauvegarde tout
        $this->updateCartTotal($cart);
        
        return true;
    }

    /**
     * Je modifie la quantité d'un produit dans le panier
     * 
     * COMMENT JE FONCTIONNE :
     * 1. Je trouve l'article dans le panier
     * 2. Si nouvelle quantité = 0, je supprime l'article
     * 3. Sinon, je mets à jour la quantité
     * 4. Je recalcule et sauvegarde
     */
    public function updateProductQuantity(Product $product, int $newQuantity): bool
    {
        if ($newQuantity < 0) {
            throw new \InvalidArgumentException('La quantité ne peut pas être négative');
        }
        
        $cart = $this->getCurrentCart();
        $cartItem = $this->cartItemRepository->findByCartAndProduct($cart, $product);
        
        if (!$cartItem) {
            return false; // Le produit n'est pas dans le panier
        }
        
        if ($newQuantity === 0) {
            // Quantité 0 = suppression du produit
            return $this->removeProduct($product);
        }
        
        // Je mets à jour la quantité
        $cartItem->setQuantity($newQuantity);
        
        // Je recalcule le total et je sauvegarde
        $this->updateCartTotal($cart);
        
        return true;
    }

    /**
     * J'augmente la quantité d'un produit
     * 
     * UTILITÉ : Pour les boutons "+" dans l'interface
     */
    public function increaseProductQuantity(Product $product, int $amount = 1): bool
    {
        $cart = $this->getCurrentCart();
        $cartItem = $this->cartItemRepository->findByCartAndProduct($cart, $product);
        
        if (!$cartItem) {
            // Le produit n'est pas dans le panier, je l'ajoute
            $this->addProduct($product, $amount);
            return true;
        }
        
        $cartItem->increaseQuantity($amount);
        $this->updateCartTotal($cart);
        
        return true;
    }

    /**
     * Je diminue la quantité d'un produit
     * 
     * UTILITÉ : Pour les boutons "-" dans l'interface
     */
    public function decreaseProductQuantity(Product $product, int $amount = 1): bool
    {
        $cart = $this->getCurrentCart();
        $cartItem = $this->cartItemRepository->findByCartAndProduct($cart, $product);
        
        if (!$cartItem) {
            return false; // Le produit n'est pas dans le panier
        }
        
        $newQuantity = $cartItem->getQuantity() - $amount;
        
        if ($newQuantity <= 0) {
            // La quantité devient 0 ou négative, je supprime l'article
            return $this->removeProduct($product);
        }
        
        $cartItem->setQuantity($newQuantity);
        $this->updateCartTotal($cart);
        
        return true;
    }

    /**
     * Je vide complètement le panier
     * 
     * COMMENT JE FONCTIONNE :
     * 1. Je supprime tous les articles du panier
     * 2. Je remets le total à 0
     * 3. Je sauvegarde
     */
    public function clearCart(): void
    {
        $cart = $this->getCurrentCart();
        
        // Je supprime tous les articles en une fois (plus rapide)
        $this->cartItemRepository->clearCart($cart);
        
        // Je remets le total à zéro
        $cart->setTotalCents(0);
        $this->cartRepository->save($cart);
    }

    /**
     * Je récupère le nombre total d'articles dans le panier
     * 
     * UTILITÉ : Pour afficher le badge du panier (ex: "3" articles)
     */
    public function getTotalQuantity(): int
    {
        $cart = $this->getCurrentCart();
        return $this->cartItemRepository->calculateCartTotalQuantity($cart);
    }

    /**
     * Je récupère le total en euros du panier
     * 
     * UTILITÉ : Pour afficher le prix total à payer
     */
    public function getTotalPrice(): float
    {
        $cart = $this->getCurrentCart();
        return $cart->getTotal();
    }

    /**
     * Je vérifie si le panier est vide
     * 
     * UTILITÉ : Pour masquer le badge ou afficher un message spécial
     */
    public function isEmpty(): bool
    {
        $cart = $this->getCurrentCart();
        return $cart->isEmpty();
    }

    /**
     * Je récupère tous les articles du panier ordonnés
     * 
     * UTILITÉ : Pour afficher la liste complète dans la page panier
     */
    public function getCartItems(): array
    {
        $cart = $this->getCurrentCart();
        return $this->cartItemRepository->findByCartOrderedByDate($cart);
    }

    /**
     * Je vérifie si un produit est déjà dans le panier
     * 
     * UTILITÉ : Pour changer l'affichage des boutons (ex: "Ajouter" vs "Déjà ajouté")
     */
    public function hasProduct(Product $product): bool
    {
        $cart = $this->getCurrentCart();
        $cartItem = $this->cartItemRepository->findByCartAndProduct($cart, $product);
        return $cartItem !== null;
    }

    /**
     * Je récupère la quantité d'un produit dans le panier
     * 
     * UTILITÉ : Pour afficher "Quantité: 3" sur les pages produits
     */
    public function getProductQuantity(Product $product): int
    {
        $cart = $this->getCurrentCart();
        $cartItem = $this->cartItemRepository->findByCartAndProduct($cart, $product);
        return $cartItem ? $cartItem->getQuantity() : 0;
    }

    /**
     * Je mets à jour le total du panier (méthode privée)
     * 
     * POURQUOI PRIVÉE ?
     * - Utilisée en interne par mes autres méthodes
     * - Évite les erreurs en la rendant inaccessible de l'extérieur
     * - Centralise le calcul du total
     */
    private function updateCartTotal(Cart $cart): void
    {
        // Je recalcule le total en additionnant tous les articles
        $cart->recalculateTotal();
        
        // Je sauvegarde le panier avec son nouveau total
        $this->cartRepository->save($cart);
    }

    /**
     * Je fournis un résumé complet du panier
     * 
     * UTILITÉ : Pour les APIs, les confirmations, les emails
     * RETOURNE : Un tableau avec toutes les infos importantes
     */
    public function getCartSummary(): array
    {
        $cart = $this->getCurrentCart();
        
        return [
            'totalQuantity' => $this->getTotalQuantity(),
            'totalPrice' => $this->getTotalPrice(),
            'totalProducts' => $cart->getTotalProducts(),
            'isEmpty' => $this->isEmpty(),
            'items' => array_map(function (CartItem $item) {
                return [
                    'productId' => $item->getProduct()->getId(),
                    'productName' => $item->getProduct()->getName(),
                    'quantity' => $item->getQuantity(),
                    'unitPrice' => $item->getUnitPrice(),
                    'total' => $item->getTotal(),
                ];
            }, $this->getCartItems())
        ];
    }
}
