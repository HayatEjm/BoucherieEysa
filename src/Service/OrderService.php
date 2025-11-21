<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Service de gestion des commandes
 * 
 * Responsabilités :
 * - Conversion panier → commande
 * - Gestion du cycle de vie des commandes
 * - Calcul des totaux et taxes
 * - Validation métier
 */
class OrderService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private OrderRepository $orderRepository,
        private CartService $cartService,
        private LoggerInterface $logger,
        private PickupSlotService $pickupSlotService
    ) {}

    /**
     * Crée une nouvelle commande depuis un panier
     */
    public function createOrderFromCart(Cart $cart): Order
    {
        $this->logger->info('Création commande depuis panier', ['cart_id' => $cart->getId()]);

        $order = new Order();

        // Champs obligatoires à setter (à adapter selon ton workflow)
        $order->setPaymentMethod('non-renseigné'); // À remplacer par la vraie méthode si dispo
        $order->setPickupAt(new \DateTime()); // À remplacer par la vraie date/heure de retrait si dispo

        foreach ($cart->getCartItems() as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->setProduct($cartItem->getProduct());
            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setUnitPrice($cartItem->getUnitPrice()); // <-- Ajout obligatoire
            $orderItem->setUnitPriceHtCents($cartItem->getUnitPriceCents());

            // TVA 20% pour la viande
            $htCents = $cartItem->getTotalCents();
            $tvaCents = (int) round($htCents * 0.20);
            $ttcCents = $htCents + $tvaCents;

            $orderItem->setTotalHtCents($htCents);
            $orderItem->setTotalTvaCents($tvaCents);
            $orderItem->setTotalTtcCents($ttcCents);

            $order->addOrderItem($orderItem);
        }

        $order->recalculateTotals();

        $this->logger->info('Commande créée', [
            'order_number' => $order->getOrderNumber(),
            'total_ttc' => $order->getTotalTtc()
        ]);

        return $order;
    }

    /**
     * Traite et finalise une commande
     */
    public function processOrder(Order $order): void
    {
        $this->logger->info('Traitement commande', ['order_number' => $order->getOrderNumber()]);

        // Validation métier
        $this->validateOrder($order);

        // Génération du numéro de retrait
        $order->setPickupNumber($this->generatePickupNumber());

        // Confirmation de la commande
        $order->confirm();

        // Sauvegarde
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $this->logger->info('Commande traitée avec succès', [
            'order_number' => $order->getOrderNumber(),
            'pickup_number' => $order->getPickupNumber()
        ]);
    }

    /**
     * Valide une commande avant traitement
     */
    private function validateOrder(Order $order): void
    {
        if ($order->getOrderItems()->isEmpty()) {
            throw new \InvalidArgumentException('La commande ne peut pas être vide');
        }

        if (empty($order->getCustomerName()) || empty($order->getCustomerPhone())) {
            throw new \InvalidArgumentException('Les informations client sont obligatoires');
        }

        if (!$order->getPickupDate() || !$order->getPickupTimeSlot()) {
            throw new \InvalidArgumentException('Le créneau de retrait est obligatoire');
        }

        if ($order->getPickupDate() < new \DateTime('today')) {
            throw new \InvalidArgumentException('La date de retrait ne peut pas être dans le passé');
        }

        // Vérifier que le créneau est disponible (jour non fermé + capacité non dépassée)
        if (!$this->pickupSlotService->isSlotAvailable($order->getPickupDate(), $order->getPickupTimeSlot())) {
            throw new \InvalidArgumentException(
                'Le créneau sélectionné n\'est pas disponible. ' .
                'Veuillez choisir un autre jour ou créneau horaire.'
            );
        }
        
        // Validation du délai de préparation pour les commandes le jour même
        $now = new \DateTime();
        $pickupDate = $order->getPickupDate();
        $isToday = $pickupDate->format('Y-m-d') === $now->format('Y-m-d');
        
        if ($isToday) {
            $timeSlot = $order->getPickupTimeSlot();
            $slotDateTime = \DateTime::createFromFormat('Y-m-d H:i', $pickupDate->format('Y-m-d') . ' ' . $timeSlot);
            
            // Récupérer le délai minimum de préparation depuis la config (par défaut 2h)
            $config = $this->pickupSlotService->getConfig();
            $minPreparationHours = $config['min_preparation_hours'] ?? 2;
            
            $minTimeLimit = clone $now;
            $minTimeLimit->modify("+{$minPreparationHours} hours");
            
            if ($slotDateTime < $minTimeLimit) {
                throw new \InvalidArgumentException(
                    "Le créneau sélectionné est trop proche. " .
                    "Merci de choisir un créneau au moins {$minPreparationHours}h après l'heure actuelle pour nous laisser le temps de préparer votre commande."
                );
            }
        }

        $this->logger->info('Commande validée', ['order_number' => $order->getOrderNumber()]);
    }

    /**
     * Génère un numéro de retrait unique
     */
    private function generatePickupNumber(): string
    {
        $date = date('Ymd');
        $number = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        return "R-{$date}-{$number}";
    }

    /**
     * Trouve une commande par son numéro
     */
    public function findByOrderNumber(string $orderNumber): ?Order
    {
        return $this->orderRepository->findOneBy(['orderNumber' => $orderNumber]);
    }

    /**
     * Récupère les commandes d'une journée
     */
    public function getOrdersForDate(\DateTime $date): array
    {
        return $this->orderRepository->createQueryBuilder('o')
            ->where('DATE(o.pickupDate) = :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->orderBy('o.pickupTimeSlot', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Change le statut d'une commande
     */
    public function updateOrderStatus(Order $order, string $newStatus): void
    {
        $validStatuses = ['pending', 'confirmed', 'prepared', 'ready', 'completed', 'cancelled'];
        
        if (!in_array($newStatus, $validStatuses)) {
            throw new \InvalidArgumentException("Statut invalide: {$newStatus}");
        }

        $oldStatus = $order->getStatus();
        $order->setStatus($newStatus);
        
        $this->entityManager->flush();

        $this->logger->info('Statut commande mis à jour', [
            'order_number' => $order->getOrderNumber(),
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);
    }

    /**
     * Calcule les statistiques du jour
     */
    public function getDailyStats(\DateTime $date = null): array
    {
        $date = $date ?: new \DateTime();
        
        $qb = $this->orderRepository->createQueryBuilder('o')
            ->where('DATE(o.createdAt) = :date')
            ->setParameter('date', $date->format('Y-m-d'));

        $orders = $qb->getQuery()->getResult();

        $stats = [
            'total_orders' => count($orders),
            'total_revenue' => 0,
            'status_breakdown' => [],
        ];

        foreach ($orders as $order) {
            $stats['total_revenue'] += $order->getTotalTtc();
            $status = $order->getStatus();
            $stats['status_breakdown'][$status] = ($stats['status_breakdown'][$status] ?? 0) + 1;
        }

        return $stats;
    }
}
