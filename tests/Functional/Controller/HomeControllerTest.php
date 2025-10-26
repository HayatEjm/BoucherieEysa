<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fonctionnel de la page d'accueil - Identique au cours
 * Vérifie que la homepage charge correctement
 */
class HomeControllerTest extends WebTestCase
{
    /**
     * Test identique à celui du cours - Charge la homepage
     */
    public function testHomepageLoads(): void
    {
        // Créer un navigateur interne Symfony
        $client = static::createClient();
        
        // Effectuer une requête GET sur la page d'accueil
        $crawler = $client->request('GET', '/');
        
        // Vérifier que la réponse est un succès (200)
        $this->assertResponseIsSuccessful();
        
        // Vérifier qu'un élément h1 contient "EYSA BOUCHERIE" (votre titre)
        $this->assertSelectorTextContains('h1', 'EYSA BOUCHERIE');
    }

    /**
     * Test supplémentaire - Vérifier la structure de votre homepage
     */
    public function testHomepageStructure(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        
        $this->assertResponseIsSuccessful();
        
        // Vérifier que les sections principales existent
        $this->assertSelectorExists('.hero-section', 'La section hero doit exister');
        $this->assertSelectorExists('.categories-section', 'La section catégories doit exister');
        $this->assertSelectorExists('.advantages-section', 'La section avantages doit exister');
    }

    /**
     * Test navigation - Vérifier que les liens fonctionnent
     */
    public function testNavigationLinks(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        
        // Cliquer sur le lien "Découvrir" (si il existe)
        $link = $crawler->selectLink('Découvrir')->link();
        $crawler = $client->click($link);
        
        // Vérifier qu'on arrive bien sur la page des catégories
        $this->assertResponseIsSuccessful();
    }
}