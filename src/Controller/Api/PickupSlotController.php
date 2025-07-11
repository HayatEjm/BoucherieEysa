<?php

namespace App\Controller\Api;

use App\Service\PickupSlotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/pickup-slots', name: 'api_pickup_slots_')]
class PickupSlotController extends AbstractController
{
    public function __construct(
        private PickupSlotService $pickupSlotService
    ) {
    }

    /**
     * Récupère les créneaux disponibles pour une période donnée
     * 
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function getAvailableSlots(Request $request): JsonResponse
    {
        // Paramètres optionnels pour filtrer
        $startDate = $request->query->get('start_date');
        $endDate = $request->query->get('end_date');
        $days = (int) $request->query->get('days', 7); // Par défaut 7 jours

        try {
            // Si pas de dates spécifiées, on prend les X prochains jours
            if (!$startDate) {
                $startDate = new \DateTime();
            } else {
                $startDate = new \DateTime($startDate);
            }

            if (!$endDate) {
                $endDate = (clone $startDate)->modify("+{$days} days");
            } else {
                $endDate = new \DateTime($endDate);
            }

            // Récupérer les créneaux disponibles
            $availableSlots = $this->pickupSlotService->getAvailableSlotsForPeriod($startDate, $endDate);

            return $this->json([
                'success' => true,
                'data' => [
                    'slots' => $availableSlots,
                    'period' => [
                        'start' => $startDate->format('Y-m-d'),
                        'end' => $endDate->format('Y-m-d')
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des créneaux : ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Récupère les créneaux pour une date spécifique
     * 
     * @param string $date
     * @return JsonResponse
     */
    #[Route('/{date}', name: 'by_date', methods: ['GET'], requirements: ['date' => '\d{4}-\d{2}-\d{2}'])]
    public function getSlotsByDate(string $date): JsonResponse
    {
        try {
            $dateObj = new \DateTime($date);
            $slots = $this->pickupSlotService->getAvailableSlotsForDate($dateObj);

            return $this->json([
                'success' => true,
                'data' => [
                    'date' => $date,
                    'slots' => $slots
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des créneaux pour la date ' . $date . ' : ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Vérifie la disponibilité d'un créneau spécifique
     * 
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/check', name: 'check', methods: ['POST'])]
    public function checkSlotAvailability(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['date']) || !isset($data['time_slot'])) {
            return $this->json([
                'success' => false,
                'error' => 'Date et créneau horaire requis'
            ], 400);
        }

        try {
            $date = new \DateTime($data['date']);
            $timeSlot = $data['time_slot'];

            $isAvailable = $this->pickupSlotService->isSlotAvailable($date, $timeSlot);

            return $this->json([
                'success' => true,
                'data' => [
                    'date' => $data['date'],
                    'time_slot' => $timeSlot,
                    'available' => $isAvailable
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Erreur lors de la vérification : ' . $e->getMessage()
            ], 400);
        }
    }
}
