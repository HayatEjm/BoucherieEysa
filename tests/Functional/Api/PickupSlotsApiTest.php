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

            // Nouveau contrat: chaque jour inclut un indicateur closed
            if (!empty($response['data']['slots'])) {
                $premierJour = $response['data']['slots'][0];
                $this->assertArrayHasKey('date', $premierJour);
                $this->assertArrayHasKey('day_name', $premierJour);
                $this->assertArrayHasKey('closed', $premierJour);
                $this->assertArrayHasKey('slots', $premierJour);
            }
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
            $premierJour = $response['data']['slots'][0];
            $this->assertArrayHasKey('closed', $premierJour, 'Le champ closed doit exister');

            if ($premierJour['closed'] === true) {
                $this->assertIsArray($premierJour['slots'], 'Slots doit être un tableau même fermé');
                $this->assertEmpty($premierJour['slots'], 'Jour fermé => slots vide');
            } else {
                if (!empty($premierJour['slots'])) {
                    $premierSlot = $premierJour['slots'][0];
                    $this->assertArrayHasKey('key', $premierSlot);
                    $this->assertArrayHasKey('time', $premierSlot);
                    $this->assertArrayHasKey('available', $premierSlot);
                    $this->assertArrayHasKey('status', $premierSlot);
                }
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

    public function testApiInclutJoursFermes(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/pickup-slots');
        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('slots', $data['data']);

        $foundClosed = false;
        foreach ($data['data']['slots'] as $jour) {
            if (isset($jour['closed']) && $jour['closed'] === true) {
                $foundClosed = true;
                $this->assertIsArray($jour['slots']);
                $this->assertEmpty($jour['slots']);
                break;
            }
        }
        $this->assertTrue($foundClosed, 'Au moins un jour fermé devrait apparaître (dimanche ou lundi)');
    }
}