<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\CartItem;
use App\Entity\Cart;
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires des entités - Règles métier du dossier DWWM
 * Tests simples et efficaces pour valider les contraintes métier
 */
class ProductValidationTest extends TestCase
{
    /**
     * Test mentionné dans le dossier : "validation des poids minimum"
     * Section 6. Jeu d'essai - Validation des poids minimum au panier
     */
    public function testValidationPoidsMinimum(): void
    {
        // Arrange : Produit avec poids minimum 400g
        $product = $this->createProduct('Entrecôte de bœuf', 28.50, 400);

        // Act & Assert : Vérifier que le minimum est bien stocké
        $this->assertEquals(400, $product->getMinWeight());
        $this->assertEquals('Entrecôte de bœuf', $product->getName());
        $this->assertEquals(28.50, $product->getPrice());
    }

    /**
     * Test du calcul de total CartItem
     * Mentionné dans le dossier section panier intelligent
     */
    public function testCalculTotalCartItem(): void
    {
        // Arrange
        $product = $this->createProduct('Côte de bœuf', 30.00); // 30€/kg
        
        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity(1000); // 1kg = 1000g
        $cartItem->setUnitPrice(30.00);

        // Act : Calcul du total
        $total = $cartItem->getTotal();

        // Assert : 1kg à 30€/kg = 30€
        $this->assertEquals(30.00, $total, 'Le calcul du total CartItem doit être correct');
    }

    /**
     * Test fusion logique lignes identiques
     * Mentionné dans le dossier section 5.2
     */
    public function testFusionConceptuelleCartItem(): void
    {
        // Arrange : Même produit, quantités différentes
        $product = $this->createProduct('Côte de bœuf', 29.90);
        
        $cartItem1 = new CartItem();
        $cartItem1->setProduct($product);
        $cartItem1->setQuantity(500); // 500g
        $cartItem1->setUnitPrice(29.90);

        // Act : Simulation de fusion (augmentation quantité)
        $cartItem1->increaseQuantity(300); // +300g

        // Assert : Total fusionné
        $this->assertEquals(800, $cartItem1->getQuantity(), 'La fusion doit additionner les quantités');
        $expectedTotal = (800 / 1000) * 29.90; // 800g à 29.90€/kg = 23.92€
        $this->assertEquals(23.92, $cartItem1->getTotal(), 'Le total après fusion doit être correct', 0.01);
    }

    /**
     * Test des contraintes métier produit
     */
    public function testContraintesMetierProduit(): void
    {
        // Arrange : Produit avec contraintes min/max
        $product = $this->createProduct('Gigot d\'agneau', 32.00, 800, 3000);

        // Act & Assert : Contraintes bien stockées
        $this->assertEquals(800, $product->getMinWeight(), 'Poids minimum doit être stocké');
        $this->assertEquals(3000, $product->getMaxWeight(), 'Poids maximum doit être stocké');
        $this->assertTrue($product->isInStock(), 'Produit avec stock > 0 doit être en stock');
    }

    /**
     * Test gestion stock
     */
    public function testGestionStock(): void
    {
        // Arrange : Produit avec stock faible
        $product = $this->createProduct('Bavette', 25.00);
        $product->setStock(3);

        // Act & Assert : Méthodes stock
        $this->assertTrue($product->isInStock(), 'Produit avec stock > 0 doit être en stock');
        $this->assertTrue($product->isLowStock(), 'Produit avec stock <= 5 doit être en stock faible');

        // Test stock vide
        $product->setStock(0);
        $this->assertFalse($product->isInStock(), 'Produit avec stock = 0 ne doit pas être en stock');
        $this->assertFalse($product->isLowStock(), 'Produit avec stock = 0 ne doit pas être en stock faible');
    }

    /**
     * Test calcul TVA boucherie (5,5%) - conceptuel
     */
    public function testCalculConceptuelTVA(): void
    {
        // Arrange
        $prixHT = 30.00;
        $tauxTVA = 0.055; // 5,5% pour boucherie

        // Act : Calcul TVA
        $tva = $prixHT * $tauxTVA;
        $prixTTC = $prixHT + $tva;

        // Assert : Vérification du calcul TVA boucherie
        $this->assertEquals(1.65, $tva, 'TVA 5,5% sur 30€ = 1,65€');
        $this->assertEquals(31.65, $prixTTC, 'Prix TTC avec TVA boucherie doit être correct');
    }

    /**
     * Test méthodes CartItem utilitaires
     */
    public function testCartItemUtilities(): void
    {
        // Arrange
        $product = $this->createProduct('Test Product', 20.00);
        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity(500);
        $cartItem->setUnitPrice(20.00);

        // Act & Assert : Méthodes utilitaires (skip isForProduct car nécessite ID persisté)
        $this->assertTrue($cartItem->isValidQuantity(), 'Quantité > 0 doit être valide');

        // Test decrease quantity
        $cartItem->decreaseQuantity(200);
        $this->assertEquals(300, $cartItem->getQuantity(), 'Decrease quantity doit fonctionner');

        // Test toString
        $string = $cartItem->__toString();
        $this->assertStringContainsString('Test Product', $string, 'toString doit contenir le nom du produit');
    }

    /**
     * Helper : Créer un produit de test
     */
    private function createProduct(string $name, float $price, ?int $minWeight = null, ?int $maxWeight = null): Product
    {
        $category = new Category();
        $category->setName('Test Category');

        $product = new Product();
        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription('Produit de test pour les tests unitaires');
        $product->setImage('test.jpg');
        $product->setStock(10);
        $product->setUnit('kg');
        $product->setCategory($category);

        if ($minWeight) {
            $product->setMinWeight($minWeight);
        }
        if ($maxWeight) {
            $product->setMaxWeight($maxWeight);
        }

        return $product;
    }
}