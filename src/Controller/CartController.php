<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 🛒 CONTROLLER CARTCONTROLLER - Je gère toutes les pages et actions du panier
 * 
 * POURQUOI CE CONTROLLER ?
 * - Je fournis toutes les routes liées au panier (/panier, /panier/add, etc.)
 * - Je fais le lien entre les formulaires/requêtes et le CartService
 * - Je retourne les pages HTML et les réponses JSON pour AJAX
 * 
 * MES ROUTES :
 * - GET /panier : Page principale du panier
 * - POST /panier/add/{id} : Ajouter un produit
 * - POST /panier/remove/{id} : Retirer un produit
 * - POST /panier/update/{id} : Modifier une quantité
 * - POST /panier/clear : Vider le panier
 * - GET /panier/count : Nombre d'articles (pour AJAX)
 */
class CartController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private EntityManagerInterface $entityManager
    ) {
        // J'injecte mes dépendances pour pouvoir travailler :
        // - CartService : pour toute la logique métier du panier
        // - EntityManager : pour récupérer les produits depuis la base
    }

    /**
     * PAGE PRINCIPALE DU PANIER - Je affiche tous les articles du panier
     * 
     * ROUTE : GET /panier
     * 
     * CE QUE JE FAIS :
     * 1. Je récupère tous les articles du panier via le CartService
     * 2. Je calcule les totaux (quantité, prix)
     * 3. J'envoie tout ça au template pour affichage
     * 
     * TEMPLATE : templates/cart/index.html.twig
     */
    #[Route('/panier', name: 'app_cart_index', methods: ['GET'])]
    public function index(): Response
    {
        // Je récupère toutes les informations du panier
        $cart = $this->cartService->getCurrentCart(); // L'objet cart complet pour la TVA
        $cartItems = $this->cartService->getCartItems();
        $totalQuantity = $this->cartService->getTotalQuantity();
        $totalPrice = $this->cartService->getTotalPrice();
        $isEmpty = $this->cartService->isEmpty();
        
        // J'envoie tout au template
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,                   // Objet cart pour les calculs TVA
            'cartItems' => $cartItems,        // Liste des articles
            'totalQuantity' => $totalQuantity, // Nombre total d'articles
            'totalPrice' => $totalPrice,       // Prix total (rétro-compatibilité)
            'isEmpty' => $isEmpty,             // True si panier vide
        ]);
    }

    /**
     * AJOUTER UN PRODUIT AU PANIER - Via AJAX ou formulaire
     * 
     * ROUTE : POST /panier/add/{id}
     * 
     * CE QUE JE FAIS :
     * 1. Je trouve le produit par son ID
     * 2. Je récupère la quantité demandée (défaut: 1)
     * 3. J'utilise le CartService pour ajouter le produit
     * 4. Je retourne une réponse JSON pour AJAX ou je redirige
     * 
     * UTILISATION :
     * - Depuis les boutons "Ajouter au panier" des pages produits
     * - Via JavaScript pour mise à jour en temps réel
     */
    #[Route('/panier/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function addProduct(int $id, Request $request): Response
    {
        // Je trouve le produit en base de données
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        
        if (!$product) {
            // Le produit n'existe pas
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['error' => 'Produit non trouvé'], 404);
            }
            
            $this->addFlash('error', 'Produit non trouvé');
            return $this->redirectToRoute('app_products');
        }
        
        // Je récupère la quantité demandée (défaut: 1)
        $quantity = (int) $request->request->get('quantity', 1);
        
        if ($quantity <= 0) {
            $quantity = 1; // Sécurité : au moins 1
        }
        
        try {
            // J'ajoute le produit au panier via le service
            $cartItem = $this->cartService->addProduct($product, $quantity);
            
            // Si c'est une requête AJAX, je retourne du JSON
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => true,
                    'message' => sprintf('%s ajouté au panier', $product->getName()),
                    'cartItem' => [
                        'productName' => $cartItem->getProduct()->getName(),
                        'quantity' => $cartItem->getQuantity(),
                        'unitPrice' => $cartItem->getUnitPrice(),
                        'total' => $cartItem->getTotal(),
                    ],
                    'cartSummary' => $this->cartService->getCartSummary(),
                ]);
            }
            
            // Sinon, message flash et redirection
            $this->addFlash('success', sprintf('%s ajouté au panier !', $product->getName()));
            
        } catch (\Exception $e) {
            // Gestion des erreurs
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['error' => $e->getMessage()], 400);
            }
            
            $this->addFlash('error', 'Erreur lors de l\'ajout au panier');
        }
        
        // Je redirige vers la page d'origine ou le panier
        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }
        
        return $this->redirectToRoute('app_cart_index');
    }

    /**
     * RETIRER UN PRODUIT DU PANIER
     * 
     * ROUTE : POST /panier/remove/{id}
     * 
     * CE QUE JE FAIS :
     * 1. Je trouve le produit par son ID
     * 2. J'utilise le CartService pour le retirer
     * 3. Je retourne une confirmation
     */
    #[Route('/panier/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    public function removeProduct(int $id, Request $request): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        
        if (!$product) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['error' => 'Produit non trouvé'], 404);
            }
            
            $this->addFlash('error', 'Produit non trouvé');
            return $this->redirectToRoute('app_cart_index');
        }
        
        try {
            $success = $this->cartService->removeProduct($product);
            
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => $success,
                    'message' => $success ? sprintf('%s retiré du panier', $product->getName()) : 'Produit non trouvé dans le panier',
                    'cartSummary' => $this->cartService->getCartSummary(),
                ]);
            }
            
            if ($success) {
                $this->addFlash('success', sprintf('%s retiré du panier', $product->getName()));
            } else {
                $this->addFlash('warning', 'Ce produit n\'était pas dans votre panier');
            }
            
        } catch (\Exception $e) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['error' => $e->getMessage()], 400);
            }
            
            $this->addFlash('error', 'Erreur lors de la suppression');
        }
        
        return $this->redirectToRoute('app_cart_index');
    }

    /**
     * MODIFIER LA QUANTITÉ D'UN PRODUIT
     * 
     * ROUTE : POST /panier/update/{id}
     * 
     * CE QUE JE FAIS :
     * 1. Je récupère la nouvelle quantité depuis le formulaire
     * 2. J'utilise le CartService pour mettre à jour
     * 3. Je retourne la confirmation
     */
    #[Route('/panier/update/{id}', name: 'app_cart_update', methods: ['POST'])]
    public function updateQuantity(int $id, Request $request): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        
        if (!$product) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['error' => 'Produit non trouvé'], 404);
            }
            
            $this->addFlash('error', 'Produit non trouvé');
            return $this->redirectToRoute('app_cart_index');
        }
        
        $newQuantity = (int) $request->request->get('quantity', 1);
        
        try {
            $success = $this->cartService->updateProductQuantity($product, $newQuantity);
            
            if ($request->isXmlHttpRequest()) {
                $message = $newQuantity === 0 
                    ? sprintf('%s retiré du panier', $product->getName())
                    : sprintf('Quantité de %s mise à jour', $product->getName());
                
                return new JsonResponse([
                    'success' => $success,
                    'message' => $message,
                    'cartSummary' => $this->cartService->getCartSummary(),
                ]);
            }
            
            if ($success) {
                $message = $newQuantity === 0 
                    ? sprintf('%s retiré du panier', $product->getName())
                    : sprintf('Quantité de %s mise à jour', $product->getName());
                $this->addFlash('success', $message);
            }
            
        } catch (\Exception $e) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['error' => $e->getMessage()], 400);
            }
            
            $this->addFlash('error', 'Erreur lors de la mise à jour');
        }
        
        return $this->redirectToRoute('app_cart_index');
    }

    /**
     * VIDER COMPLÈTEMENT LE PANIER
     * 
     * ROUTE : POST /panier/clear
     * 
     * CE QUE JE FAIS :
     * 1. J'utilise le CartService pour vider le panier
     * 2. Je confirme à l'utilisateur
     */
    #[Route('/panier/clear', name: 'app_cart_clear', methods: ['POST'])]
    public function clearCart(Request $request): Response
    {
        try {
            $this->cartService->clearCart();
            
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Panier vidé',
                    'cartSummary' => $this->cartService->getCartSummary(),
                ]);
            }
            
            $this->addFlash('success', 'Votre panier a été vidé');
            
        } catch (\Exception $e) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['error' => $e->getMessage()], 400);
            }
            
            $this->addFlash('error', 'Erreur lors du vidage du panier');
        }
        
        return $this->redirectToRoute('app_cart_index');
    }

    /**
     * RÉCUPÉRER LE NOMBRE D'ARTICLES - Pour mise à jour AJAX du badge
     * 
     * ROUTE : GET /panier/count
     * 
     * CE QUE JE FAIS :
     * 1. Je retourne juste le nombre d'articles en JSON
     * 2. Utilisé par JavaScript pour mettre à jour le badge en temps réel
     */
    #[Route('/panier/count', name: 'app_cart_count', methods: ['GET'])]
    public function getCartCount(): JsonResponse
    {
        try {
            return new JsonResponse([
                'count' => $this->cartService->getTotalQuantity(),
                'isEmpty' => $this->cartService->isEmpty(),
                'total' => $this->cartService->getTotalPrice(),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * RÉCUPÉRER LE RÉSUMÉ COMPLET - Pour affichage modal ou popup
     * 
     * ROUTE : GET /panier/summary
     * 
     * CE QUE JE FAIS :
     * 1. Je retourne un résumé complet du panier en JSON
     * 2. Utilisé pour les popups "panier" ou les modales
     */
    #[Route('/panier/summary', name: 'app_cart_summary', methods: ['GET'])]
    public function getCartSummary(): JsonResponse
    {
        try {
            return new JsonResponse($this->cartService->getCartSummary());
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * REDIRECTION VERS LE NOUVEAU CHECKOUT
     * 
     * ROUTE : GET/POST /panier/checkout
     * 
     * Cette route redirige vers le nouveau système de checkout
     */
    #[Route('/panier/checkout', name: 'app_cart_checkout', methods: ['GET', 'POST'])]
    public function checkout(Request $request): Response
    {
        // Redirection vers le nouveau système de checkout
        return $this->redirectToRoute('app_checkout_index');
    }

    /**
     * TRAITER LA COMMANDE FINALE - Je sauvegarde la commande avec le créneau
     */
}
