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
 * SERVICE CARTSERVICE - Je gère toute la logique métier du panier
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
    ) {}

    // Récupère le panier de l'utilisateur actuel (création auto si besoin)
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

    // Ajoute un produit au panier (ou augmente la quantité)
    public function addProduct(Product $product, int $quantity = 1): CartItem
    {
        // Limites personnalisables
        $MAX_PER_PRODUCT = 5000; // ex: 5000g ou 5 unités max par produit
        $MAX_TOTAL = 30; // max 30 articles au total dans le panier

        // Je valide que la quantité est positive
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('La quantité doit être positive');
        }

        // Je valide le poids minimum si le produit en a un
        if ($product->getMinWeight() !== null && $quantity < $product->getMinWeight()) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Quantité insuffisante pour %s. Minimum requis : %dg',
                    $product->getName(),
                    $product->getMinWeight()
                )
            );
        }

        // Je valide le poids maximum si le produit en a un
        if ($product->getMaxWeight() !== null && $quantity > $product->getMaxWeight()) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Quantité trop importante pour %s. Maximum autorisé : %dg',
                    $product->getName(),
                    $product->getMaxWeight()
                )
            );
        }

        $cart = $this->getCurrentCart();
        $existingItem = $this->cartItemRepository->findByCartAndProduct($cart, $product);

        // Calcul de la quantité totale après ajout
        $currentProductQty = $existingItem ? $existingItem->getQuantity() : 0;
        $futureProductQty = $currentProductQty + $quantity;
        if ($futureProductQty > $MAX_PER_PRODUCT) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Vous ne pouvez pas ajouter plus de %d unités de ce produit dans le panier.', $MAX_PER_PRODUCT
                )
            );
        }

        // Calcul du total global après ajout
      // Nouveau test (produits distincts) :
$cartItems = $this->cartItemRepository->findByCart($cart);
$distinctCount = count($cartItems) + ($existingItem ? 0 : 1);
if ($distinctCount > $MAX_TOTAL) {
    throw new \InvalidArgumentException(
        sprintf('Vous ne pouvez pas avoir plus de %d produits différents dans votre panier.', $MAX_TOTAL)
    );
}


        if ($existingItem) {
            $existingItem->increaseQuantity($quantity);
            $cartItem = $existingItem;
        } else {
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity($quantity);
            $cartItem->setUnitPrice($product->getPrice());
            $cart->addCartItem($cartItem);
        }

        $this->updateCartTotal($cart);
        return $cartItem;
    }

    // Retire un produit du panier
    public function removeProduct(Product $product): bool
    {
        $cart = $this->getCurrentCart();
        $cartItem = $this->cartItemRepository->findByCartAndProduct($cart, $product);
        
        if (!$cartItem) {
            return false; // Le produit n'était pas dans le panier
        }
        
        // Je retire l'article du panier et je le supprime
        $cart->removeCartItem($cartItem);
        $this->cartItemRepository->remove($cartItem, true); // Flush immédiat pour garantir la suppression

        // Je recalcule le total et je sauvegarde tout
        $this->updateCartTotal($cart);

        return true;
    }

    // Modifie la quantité d'un produit dans le panier
    public function updateProductQuantity(Product $product, int $newQuantity): bool
    {
        if ($newQuantity < 0) {
            throw new \InvalidArgumentException('La quantité ne peut pas être négative');
        }
        
        // Je valide le poids minimum si le produit en a un (sauf si on supprime)
        if ($newQuantity > 0 && $product->getMinWeight() !== null && $newQuantity < $product->getMinWeight()) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Quantité insuffisante pour %s. Minimum requis : %dg',
                    $product->getName(),
                    $product->getMinWeight()
                )
            );
        }

        // Je valide le poids maximum si le produit en a un
        if ($newQuantity > 0 && $product->getMaxWeight() !== null && $newQuantity > $product->getMaxWeight()) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Quantité trop importante pour %s. Maximum autorisé : %dg',
                    $product->getName(),
                    $product->getMaxWeight()
                )
            );
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

    // Augmente la quantité d'un produit
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

    // Diminue la quantité d'un produit
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

    // Vide complètement le panier
    public function clearCart(): void
    {
        $cart = $this->getCurrentCart();
        
        // Je supprime tous les articles en une fois (plus rapide)
        $this->cartItemRepository->clearCart($cart);
        
        // Je vide le panier (cela recalculera automatiquement le total à 0)
        $cart->clear();
        $this->cartRepository->save($cart);
    }
    // Retourne le nombre total d'articles dans le panier
    
    public function getTotalQuantity(): int
    {
        // On compte juste le nombre de CartItem, pas la somme des quantités
        return count($this->getCartItems());
    }


    // Total en euros du panier
    public function getTotalPrice(): float
    {
        $cart = $this->getCurrentCart();
        return $cart->getTotal();
    }

    // Vérifie si le panier est vide
    public function isEmpty(): bool
    {
        $cart = $this->getCurrentCart();
        return $cart->isEmpty();
    }

    // Retourne tous les articles du panier (ordonnés)
    public function getCartItems(): array
    {
        $cart = $this->getCurrentCart();
        return $this->cartItemRepository->findByCartOrderedByDate($cart);
    }

    // Vérifie si un produit est déjà dans le panier
    public function hasProduct(Product $product): bool
    {
        $cart = $this->getCurrentCart();
        $cartItem = $this->cartItemRepository->findByCartAndProduct($cart, $product);
        return $cartItem !== null;
    }

    // Récupère la quantité d'un produit dans le panier
    public function getProductQuantity(Product $product): int
    {
        $cart = $this->getCurrentCart();
        $cartItem = $this->cartItemRepository->findByCartAndProduct($cart, $product);
        return $cartItem ? $cartItem->getQuantity() : 0;
    }

    // Met à jour le total du panier (interne)
    private function updateCartTotal(Cart $cart): void
    {
        // Je recalcule le total en additionnant tous les articles
        $cart->recalculateTotal();
        
        // Je sauvegarde le panier avec son nouveau total
        $this->cartRepository->save($cart);
    }

    // Retourne un résumé complet du panier (pour l'API)
    public function getCartSummary(): array
    {
        $cart = $this->getCurrentCart();
        
        return [
            'totalQuantity' => $this->getTotalQuantity(),
            'totalPrice' => $this->getTotalPrice(),
            'totalProducts' => $cart->getTotalItems(),
            'totalHT' => $cart->getTotalHT(),
            'totalTTC' => $cart->getTotalTTC(),
            'totalTVA' => $cart->getTaxAmount(),
            'isEmpty' => $this->isEmpty(),
            'items' => array_map(function (CartItem $item) {
                return [
                    'product' => [
                        'id' => $item->getProduct()->getId(),
                        'name' => $item->getProduct()->getName(),
                        'priceHT' => $item->getProduct()->getPrice(),
                        'image' => $item->getProduct()->getImage(),
                    ],
                    'quantity' => $item->getQuantity(),
                    'unitPrice' => $item->getUnitPrice(),
                    'totalTTC' => $item->getTotal(),
                ];
            }, $this->getCartItems())
        ];
    }
}
