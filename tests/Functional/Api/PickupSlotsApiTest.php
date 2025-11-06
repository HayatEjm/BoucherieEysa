<?php

namespace App\Tests\Functional\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels API - Créneaux de retrait
 * Mentionné dans le dossier DWWM section 5.3 et 4.6
 */
class PickupSlotsApiTest extends WebTestCase
{
    /**
     * Test mentionné dans le dossier : "validation des créneaux"
     * Section API et contrats - endpoint /api/pickup-slots
     */
    public function testApiPickupSlotsRetourneJson(): void
    {
        $client = static::createClient();

        // Act : Appel de l'API des créneaux
        $client->request('GET', '/api/pickup-slots');

        // Assert : Réponse JSON valide
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $response = json_decode($client->getResponse()->getContent(), true);
        
        // Vérifier la structure de base mentionnée dans le dossier
        $this->assertArrayHasKey('success', $response, 'La réponse doit avoir un champ success');
        
        if ($response['success']) {
            $this->assertArrayHasKey('data', $response, 'Une réponse réussie doit avoir un champ data');
            $this->assertArrayHasKey('slots', $response['data'], 'Les données doivent contenir les slots');
        }
    }

    /**
     * Test de la structure des créneaux selon le dossier
     */
    public function testStructureCreneauxConformeAuDossier(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/pickup-slots');

        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);

        if ($response['success'] && !empty($response['data']['slots'])) {
            $premierCreneau = $response['data']['slots'][0];
            
            // Vérifier la structure mentionnée dans le dossier section 4.6
            $this->assertArrayHasKey('date', $premierCreneau, 'Chaque créneau doit avoir une date');
            $this->assertArrayHasKey('day_name', $premierCreneau, 'Chaque créneau doit avoir un nom de jour');
            
            if (isset($premierCreneau['slots']) && !empty($premierCreneau['slots'])) {
                $premierSlot = $premierCreneau['slots'][0];
                $this->assertArrayHasKey('key', $premierSlot, 'Chaque slot doit avoir une clé');
                $this->assertArrayHasKey('time', $premierSlot, 'Chaque slot doit avoir une heure');
                $this->assertArrayHasKey('available', $premierSlot, 'Chaque slot doit avoir un statut disponible');
            }
        }
    }

    /**
     * Test gestion d'erreur API
     */
    public function testApiGestionErreur(): void
    {
        $client = static::createClient();

        // Test avec paramètre invalide
        $client->request('GET', '/api/pickup-slots?days=invalid');
        
        // L'API doit soit gérer l'erreur gracieusement, soit retourner une erreur claire
        $this->assertTrue(
            in_array($client->getResponse()->getStatusCode(), [200, 400]),
            'L\'API doit gérer les paramètres invalides'
        );
    }
}