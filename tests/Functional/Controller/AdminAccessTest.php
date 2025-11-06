<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels - Contrôle d'accès admin
 * Mentionné dans le dossier DWWM section 1.3 Sécurité
 */
class AdminAccessTest extends WebTestCase
{
    /**
     * Test mentionné dans le dossier : "contrôle d'accès granulaire"
     * "Les tests fonctionnels vérifient le refus d'accès aux utilisateurs non authentifiés"
     */
    public function testRefusAccesAdminSansAuthentification(): void
    {
        $client = static::createClient();

        // Act : Tentative d'accès à l'admin sans être connecté
        $client->request('GET', '/admin/products');

        // Assert : Doit être redirigé vers la page de connexion (302) ou refusé (403)
        $this->assertTrue(
            in_array($client->getResponse()->getStatusCode(), [302, 403, 401]),
            'L\'accès admin doit être protégé pour les utilisateurs non authentifiés'
        );
    }

    /**
     * Test d'accès aux différentes sections admin sans authentification
     */
    public function testProtectionRoutesAdmin(): void
    {
        $client = static::createClient();

        $routesProtegees = [
            '/admin',
            '/admin/products',
            '/admin/categories',
            '/admin/orders',
        ];

        foreach ($routesProtegees as $route) {
            $client->request('GET', $route);
            
            $this->assertTrue(
                in_array($client->getResponse()->getStatusCode(), [302, 403, 401]),
                sprintf('La route %s doit être protégée', $route)
            );
        }
    }
}