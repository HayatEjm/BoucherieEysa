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
     * Retourne la configuration complète (pour accès externe)
     */
    public function getConfig(): array
    {
        return $this->config;
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
            
            $isClosed = $this->isClosedDay($date);
            $daySlots = $isClosed ? [] : $this->getSlotsForDate($date);

            $slots[] = [
                'date' => $date->format('Y-m-d'),
                'day_name' => $this->getFrenchDayName($date),
                'closed' => $isClosed,
                'slots' => $daySlots
            ];
        }
        
        return $slots;
    }

    /**
     * Récupère les créneaux pour une date donnée
     */
    private function getSlotsForDate(DateTime $date): array
    {
        $slots = [];
        $now = new DateTime();
        $isToday = $date->format('Y-m-d') === $now->format('Y-m-d');
        
        // Calculer l'heure limite si c'est aujourd'hui (maintenant + délai de préparation)
        $minTimeLimit = null;
        if ($isToday) {
            $minPreparationHours = $this->config['min_preparation_hours'] ?? 2;
            $minTimeLimit = clone $now;
            $minTimeLimit->modify("+{$minPreparationHours} hours");
        }
        
        // Parcourir chaque plage horaire (morning, afternoon)
        foreach ($this->config['time_slots'] as $periodKey => $periodConfig) {
            // Récupérer les créneaux 30min de cette plage
            $timeSlots = $periodConfig['slots'] ?? [];
            
            foreach ($timeSlots as $timeSlot) {
                // Si c'est aujourd'hui, filtrer les créneaux trop proches
                if ($isToday && $minTimeLimit) {
                    $slotDateTime = DateTime::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $timeSlot);
                    if ($slotDateTime < $minTimeLimit) {
                        // Créneau trop proche, on le saute
                        continue;
                    }
                }
                
                // Compter les commandes pour ce créneau précis (heure exacte)
                $currentOrders = $this->countOrdersForSlot($date, $timeSlot);
                $isAvailable = $currentOrders < $this->config['max_orders_per_slot'];
                
                $slots[] = [
                    'period' => $periodKey,  // 'morning' ou 'afternoon'
                    'time' => $timeSlot,     // '10:00', '10:30', etc.
                    'label' => $timeSlot,    // Affichage '10:00'
                    'available' => $isAvailable,
                    'current_orders' => $currentOrders,
                    'max_orders' => $this->config['max_orders_per_slot'],
                    'status' => $this->getSlotStatus($currentOrders)
                ];
            }
        }
        
        return $slots;
    }

    /**
     * Compte le nombre de commandes pour un créneau donné
     */
    private function countOrdersForSlot(DateTime $date, string $timeSlot): int
    {
        // Pour l'instant on compte par heure exacte (ex: "10:00")
        // Plus tard en Phase 2, on comptera via pickupSlot_id
        return $this->orderRepository->countByDateAndTime($date, $timeSlot);
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
     * Récupère les créneaux disponibles pour une période donnée
     */
    public function getAvailableSlotsForPeriod(DateTime $startDate, DateTime $endDate): array
    {
        $slots = [];
        $currentDate = clone $startDate;
        
        while ($currentDate <= $endDate) {
            $isClosed = $this->isClosedDay($currentDate);
            $daySlots = $isClosed ? [] : $this->getSlotsForDate($currentDate);

            $slots[] = [
                'date' => $currentDate->format('Y-m-d'),
                'day_name' => $this->getFrenchDayName($currentDate),
                'closed' => $isClosed,
                'slots' => $daySlots
            ];
            
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
        if ($this->isClosedDay($date)) {
            return [];
        }
        
        return $this->getSlotsForDate($date);
    }

    /**
     * Indique si une date correspond à un jour fermé
     */
    private function isClosedDay(DateTime $date): bool
    {
        return in_array((int)$date->format('w'), $this->config['closed_days']);
    }

    /**
     * Vérifie si un créneau est disponible (prend en compte les jours fermés)
     */
    public function isSlotAvailable(DateTime $date, string $timeSlot): bool
    {
        // Jour fermé => indisponible
        if ($this->isClosedDay($date)) {
            return false;
        }

        // Vérifier que le créneau horaire existe dans la config
        $slotExists = false;
        foreach ($this->config['time_slots'] as $periodConfig) {
            if (in_array($timeSlot, $periodConfig['slots'] ?? [])) {
                $slotExists = true;
                break;
            }
        }
        
        if (!$slotExists) {
            return false;
        }

        // Vérifier la capacité (Phase 2: comptera via BDD)
        $currentOrders = $this->countOrdersForSlot($date, $timeSlot);
        return $currentOrders < $this->config['max_orders_per_slot'];
    }
    
    /**
     * Récupère tous les créneaux horaires disponibles (liste simple pour select)
     */
    public function getAllTimeSlots(): array
    {
        $allSlots = [];
        
        foreach ($this->config['time_slots'] as $periodKey => $periodConfig) {
            foreach ($periodConfig['slots'] ?? [] as $timeSlot) {
                $allSlots[] = [
                    'value' => $timeSlot,
                    'label' => $timeSlot,
                    'period' => $periodConfig['label']
                ];
            }
        }
        
        return $allSlots;
    }
}
