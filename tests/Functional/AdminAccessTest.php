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
        
    // Tentative d'accès à la page d'accueil admin sans authentification
    // Note: la route '/admin/dashboard' n'existe pas; on teste la racine admin
    $client->request('GET', '/admin');
        
        // Doit être redirigé vers login (302) ou refusé (403/401)
        $loginUrl = static::getContainer()->get('router')->generate('app_login');
        $this->assertResponseRedirects($loginUrl, 302);
    }
    
    /**
     * Teste qu'un utilisateur non authentifié ne peut pas accéder à la gestion des produits
     */
    public function testAdminProductsIndexDeniedForAnonymous(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/admin/products');
        $loginUrl = static::getContainer()->get('router')->generate('app_login');
        $this->assertResponseRedirects($loginUrl, 302);
    }
    
    /**
     * Teste qu'un utilisateur non authentifié ne peut pas accéder à la gestion des commandes
     */
    public function testAdminOrdersIndexDeniedForAnonymous(): void
    {
        $client = static::createClient();
        
        $client->request('GET', '/admin/orders');
        $loginUrl = static::getContainer()->get('router')->generate('app_login');
        $this->assertResponseRedirects($loginUrl, 302);
    }
}
