<?php

namespace App\Controller;

use App\Form\CheckoutFormType;
use App\Service\CartService;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * 🛒 CONTRÔLEUR CHECKOUT - Gestion de la finalisation des commandes
 * 
 * RESPONSABILITÉS :
 * - Affichage du formulaire de checkout
 * - Validation et traitement des commandes
 * - Gestion des créneaux de retrait
 * - Confirmation de commande
 * 
 * ARCHITECTURE MVC :
 * - Modèle : Order, OrderItem (via OrderService)
 * - Vue : Templates Twig
 * - Contrôleur : Cette classe
 */
#[Route('/checkout')]
class CheckoutController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {}

    /**
     * 📋 PAGE DE CHECKOUT - Formulaire de finalisation
     * 
     * ROUTE : GET /checkout
     * 
     * CE QUE JE FAIS :
     * 1. Je vérifie que le panier n'est pas vide
     * 2. Je crée le formulaire de checkout
     * 3. J'affiche le récapitulatif et le formulaire
     * 
     * TEMPLATE : templates/checkout/index.html.twig
     */
    #[Route('/', name: 'app_checkout_index', methods: ['GET'])]
    public function index(): Response
    {
        // Vérifier que le panier n'est pas vide
        if ($this->cartService->isEmpty()) {
            $this->addFlash('warning', 'Votre panier est vide. Ajoutez des produits avant de finaliser votre commande.');
            return $this->redirectToRoute('app_cart_index');
        }

        // Récupérer les informations du panier
        $cart = $this->cartService->getCurrentCart();
        $cartSummary = $this->cartService->getCartSummary();

        // Créer une nouvelle commande depuis le panier
        $order = $this->orderService->createOrderFromCart($cart);

        // Créer le formulaire
        $form = $this->createForm(CheckoutFormType::class, $order);

        return $this->render('checkout/index.html.twig', [
            'form' => $form->createView(),
            'cartSummary' => $cartSummary,
            'order' => $order,
        ]);
    }

    /**
     * 💳 TRAITEMENT DU CHECKOUT - Validation et confirmation
     * 
     * ROUTE : POST /checkout
     * 
     * CE QUE JE FAIS :
     * 1. Je valide le formulaire
     * 2. Je traite la commande via OrderService
     * 3. Je vide le panier
     * 4. Je redirige vers la confirmation
     */
    #[Route('/', name: 'app_checkout_process', methods: ['POST'])]
    public function process(Request $request): Response
    {
        // Vérifier que le panier n'est pas vide
        if ($this->cartService->isEmpty()) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart_index');
        }

        // Récupérer les informations du panier
        $cart = $this->cartService->getCurrentCart();
        $cartSummary = $this->cartService->getCartSummary();

        // Créer une nouvelle commande depuis le panier
        $order = $this->orderService->createOrderFromCart($cart);

        // Créer et traiter le formulaire
        $form = $this->createForm(CheckoutFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Créer la date/heure de retrait complète
                $pickupDate = $order->getPickupDate();
                $timeSlot = $order->getPickupTimeSlot();
                
                if ($pickupDate && $timeSlot) {
                    // Extraire l'heure de début du créneau (ex: "09:00-10:00" -> "09:00")
                    $startTime = explode('-', $timeSlot)[0];
                    $pickupDateTime = \DateTime::createFromFormat('Y-m-d H:i', 
                        $pickupDate->format('Y-m-d') . ' ' . $startTime
                    );
                    $order->setPickupDateTime($pickupDateTime);
                }

                // Traiter la commande
                $this->orderService->processOrder($order);

                // Vider le panier
                $this->cartService->clearCart();

                // Message de succès
                $this->addFlash('success', 
                    'Votre commande a été confirmée ! Numéro de retrait : ' . $order->getPickupNumber()
                );

                $this->logger->info('Commande finalisée avec succès', [
                    'order_number' => $order->getOrderNumber(),
                    'customer_name' => $order->getCustomerName(),
                    'total' => $order->getTotalTtc()
                ]);

                // Redirection vers la page de confirmation
                return $this->redirectToRoute('app_checkout_success', [
                    'orderNumber' => $order->getOrderNumber()
                ]);

            } catch (\Exception $e) {
                $this->logger->error('Erreur lors du traitement de la commande', [
                    'error' => $e->getMessage(),
                    'customer_name' => $order->getCustomerName()
                ]);

                $this->addFlash('error', 
                    'Une erreur est survenue lors du traitement de votre commande. Veuillez réessayer.'
                );
            }
        }

        // En cas d'erreur, réafficher le formulaire
        return $this->render('checkout/index.html.twig', [
            'form' => $form->createView(),
            'cartSummary' => $cartSummary,
            'order' => $order,
        ]);
    }

    /**
     * 🎉 PAGE DE CONFIRMATION - Commande réussie
     * 
     * ROUTE : GET /checkout/success/{orderNumber}
     * 
     * CE QUE JE FAIS :
     * 1. Je trouve la commande par son numéro
     * 2. J'affiche les détails de confirmation
     * 3. Je fournis les informations de retrait
     */
    #[Route('/success/{orderNumber}', name: 'app_checkout_success', methods: ['GET'])]
    public function success(string $orderNumber): Response
    {
        $order = $this->orderService->findByOrderNumber($orderNumber);

        if (!$order) {
            $this->addFlash('error', 'Commande non trouvée.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('checkout/success.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * 📋 RÉCAPITULATIF DE COMMANDE - Pour réaffichage
     * 
     * ROUTE : GET /checkout/order/{orderNumber}
     * 
     * Permet de réafficher une commande existante
     */
    #[Route('/order/{orderNumber}', name: 'app_checkout_order_details', methods: ['GET'])]
    public function orderDetails(string $orderNumber): Response
    {
        $order = $this->orderService->findByOrderNumber($orderNumber);

        if (!$order) {
            throw $this->createNotFoundException('Commande non trouvée');
        }

        return $this->render('checkout/order_details.html.twig', [
            'order' => $order,
        ]);
    }
}
