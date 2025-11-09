<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test de sécurité : vérification que les routes admin sont protégées
 */
class AdminAccessTest extends WebTestCase
{
    /**
     * Teste qu'un utilisateur non authentifié ne peut pas accéder au dashboard admin
     */
    public function testAdminDashboardDeniedForAnonymous(): void
    {
        $client = static::createClient();
        
        // Tentative d'accès au dashboard sans authentification
        $client->request('GET', '/admin/dashboard');
        
        // Doit être redirigé vers login (302) ou refusé (403/401)
        $this->assertResponseRedirects('/login', 302);
    }
    
    /**
     * Teste qu'un utilisateur non authentifié ne peut pas accéder à la gestion des produits
     */
    public function testAdminProductsIndexDeniedForAnonymous(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/admin/products');
        
        $this->assertResponseRedirects('/login', 302);
    }
    
    /**
     * Teste qu'un utilisateur non authentifié ne peut pas accéder à la gestion des commandes
     */
    public function testAdminOrdersIndexDeniedForAnonymous(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/admin/orders');
        
        $this->assertResponseRedirects('/login', 302);
    }
}
