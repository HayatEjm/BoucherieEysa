<?php

namespace App\Service;

use App\Repository\OrderRepository;
use DateTime;
use DateInterval;
use Symfony\Component\Yaml\Yaml;

class PickupSlotService
{
    private array $config;

    public function __construct(
        private OrderRepository $orderRepository,
        private string $projectDir
    ) {
        // Charger la configuration depuis le fichier YAML
        $configPath = $this->projectDir . '/config/pickup_slots.yaml';
        $this->config = Yaml::parseFile($configPath)['pickup_slots'];
    }

    /**
     * Récupère tous les créneaux disponibles pour les prochains jours
     */
    public function getAvailableSlots(): array
    {
        $slots = [];
        $today = new DateTime();
        
        for ($i = 0; $i < $this->config['days_ahead']; $i++) {
            $date = clone $today;
            $date->add(new DateInterval("P{$i}D"));
            
            // Skip les jours fermés
            if (in_array((int)$date->format('w'), $this->config['closed_days'])) {
                continue;
            }
            
            $daySlots = $this->getSlotsForDate($date);
            if (!empty($daySlots)) {
                $slots[] = [
                    'date' => $date->format('Y-m-d'),
                    'day_name' => $this->getFrenchDayName($date),
                    'slots' => $daySlots
                ];
            }
        }
        
        return $slots;
    }

    /**
     * Récupère les créneaux pour une date donnée
     */
    private function getSlotsForDate(DateTime $date): array
    {
        $slots = [];
        $dayOfWeek = (int)$date->format('w');
        
        // Créneaux normaux
        foreach ($this->config['time_slots'] as $slotKey => $slotTime) {
            // Vérifier horaires spéciaux pour le dimanche
            if ($dayOfWeek === 0 && $slotKey === 'apres-midi') {
                continue; // Pas d'après-midi le dimanche
            }
            
            $currentOrders = $this->countOrdersForSlot($date, $slotKey);
            $isAvailable = $currentOrders < $this->config['max_orders_per_slot'];
            
            $slots[] = [
                'key' => $slotKey,
                'time' => $slotKey === 'matin' && $dayOfWeek === 0 
                    ? $this->config['special_hours'][0] 
                    : $slotTime,
                'available' => $isAvailable,
                'current_orders' => $currentOrders,
                'max_orders' => $this->config['max_orders_per_slot'],
                'status' => $this->getSlotStatus($currentOrders)
            ];
        }
        
        return $slots;
    }

    /**
     * Compte le nombre de commandes pour un créneau donné
     */
    private function countOrdersForSlot(DateTime $date, string $timeSlot): int
    {
        return $this->orderRepository->countByDateAndTimeSlot($date, $timeSlot);
    }

    /**
     * Détermine le statut d'un créneau (available, limited, full)
     */
    private function getSlotStatus(int $currentOrders): string
    {
        $maxOrders = $this->config['max_orders_per_slot'];
        
        if ($currentOrders >= $maxOrders) {
            return 'full';
        } elseif ($currentOrders >= $maxOrders * 0.8) {
            return 'limited';
        } else {
            return 'available';
        }
    }

    /**
     * Convertit le jour en français
     */
    private function getFrenchDayName(DateTime $date): string
    {
        $days = [
            'Sunday' => 'Dimanche',
            'Monday' => 'Lundi', 
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi'
        ];
        
        return $days[$date->format('l')];
    }

    /**
     * Vérifie si un créneau est disponible
     */
    public function isSlotAvailable(DateTime $date, string $timeSlot): bool
    {
        $currentOrders = $this->countOrdersForSlot($date, $timeSlot);
        return $currentOrders < $this->config['max_orders_per_slot'];
    }

    /**
     * Récupère les créneaux disponibles pour une période donnée
     */
    public function getAvailableSlotsForPeriod(DateTime $startDate, DateTime $endDate): array
    {
        $slots = [];
        $currentDate = clone $startDate;
        
        while ($currentDate <= $endDate) {
            // Skip les jours fermés
            if (!in_array((int)$currentDate->format('w'), $this->config['closed_days'])) {
                $daySlots = $this->getSlotsForDate($currentDate);
                if (!empty($daySlots)) {
                    $slots[] = [
                        'date' => $currentDate->format('Y-m-d'),
                        'day_name' => $this->getFrenchDayName($currentDate),
                        'slots' => $daySlots
                    ];
                }
            }
            
            $currentDate->add(new DateInterval('P1D'));
        }
        
        return $slots;
    }

    /**
     * Récupère les créneaux pour une date spécifique
     */
    public function getAvailableSlotsForDate(DateTime $date): array
    {
        // Vérifier si ce n'est pas un jour fermé
        if (in_array((int)$date->format('w'), $this->config['closed_days'])) {
            return [];
        }
        
        return $this->getSlotsForDate($date);
    }
}
