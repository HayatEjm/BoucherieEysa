<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test de l'API des créneaux de retrait
 */
class PickupSlotsApiTest extends WebTestCase
{
    /**
     * Teste que l'API retourne une structure JSON valide pour les créneaux disponibles
     */
    public function testGetPickupSlotsReturnsValidJson(): void
    {
        $client = static::createClient();
        
        // Requête GET sur l'endpoint API
        $client->request('GET', '/api/pickup-slots');
        
        // Vérification du code HTTP 200
        $this->assertResponseIsSuccessful();
        
        // Vérification que la réponse est du JSON
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        
        // Décodage et vérification de la structure
        $data = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('success', $data);
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('data', $data);
    }
    
    /**
     * Teste que l'API de vérification de créneaux gère les paramètres manquants
     */
    public function testCheckPickupSlotHandlesMissingParameters(): void
    {
        $client = static::createClient();
        
        // Requête POST sans paramètres (ou avec paramètres invalides)
        $client->request('POST', '/api/pickup-slots/check', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([]));
        
        // Peut retourner 200 avec success:false ou 400 Bad Request
        $response = $client->getResponse();
        
        $this->assertTrue(
            $response->isSuccessful() || $response->getStatusCode() === 400,
            'L\'API doit retourner 200 ou 400 pour paramètres manquants'
        );
        
        $data = json_decode($response->getContent(), true);
        
        // Vérification structure réponse d'erreur
        $this->assertIsArray($data);
        $this->assertArrayHasKey('success', $data);
        
        // Si succès false, doit contenir un message d'erreur
        if (!$data['success']) {
            $this->assertArrayHasKey('error', $data);
        }
    }
    
    /**
     * Teste que l'API retourne une structure correcte avec des paramètres valides
     */
    public function testCheckPickupSlotWithValidParameters(): void
    {
        $client = static::createClient();
        
        $tomorrow = (new \DateTime('+1 day'))->format('Y-m-d');
        
        $client->request('POST', '/api/pickup-slots/check', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'date' => $tomorrow,
            'timeSlot' => '10:00-11:00'
        ]));
        
        $this->assertResponseIsSuccessful();
        
        $data = json_decode($client->getResponse()->getContent(), true);
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('data', $data);
    }
}
