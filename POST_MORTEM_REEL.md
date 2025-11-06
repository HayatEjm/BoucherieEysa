## 12. Résolution de problèmes — post-mortem

Cette section présente les principaux incidents rencontrés pendant le développement de **Boucherie Eysa**, ainsi que la manière dont je les ai détectés, analysés et corrigés. L'objectif n'est pas de masquer les problèmes, mais au contraire de montrer la démarche de diagnostic et d'amélioration continue.

J'ai retenu trois incidents représentatifs :

1. Une **erreur 500** sur la méthode `getSubtotal()` du panier (méthode inexistante).
2. Un problème de **validation des poids minimum** contournables côté client.
3. Un incident de **fusion automatique** des lignes de panier identiques mal gérée.

---

### 12.1 Incident 1 — Erreur 500 sur `getSubtotal()` dans CartItem

#### Symptôme

Lors des tests du système de panier, j'ai rencontré une **erreur 500** récurrente :

```
Call to undefined method App\Entity\CartItem::getSubtotal()
```

L'application plantait systématiquement lors du calcul des totaux dans les templates Twig et les contrôleurs.

#### Analyse de la cause racine

En analysant l'entité `CartItem`, je me suis aperçue que :

- Les templates utilisaient `{{ item.subtotal }}` (référence à `getSubtotal()`)
- Mais l'entité n'avait que la méthode `getTotal()`
- Il y avait une **incohérence dans la nomenclature** entre ce qui était attendu et ce qui était implémenté

#### Correction apportée

J'ai standardisé sur la méthode `getTotal()` partout dans le code :

```php
// Dans CartItem.php - Méthode existante conservée
public function getTotal(): float
{
    return ($this->quantity / 1000) * $this->unitPrice;
}
```

```twig
{# Dans les templates - Correction des appels #}
{# Avant : {{ item.subtotal }} #}
{# Après : {{ item.total }} #}
<span class="cart-item-total">{{ item.total|number_format(2, ',', ' ') }} €</span>
```

#### Prévention et tests

J'ai ajouté un test unitaire pour vérifier la cohérence des calculs :

```php
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
```

### 12.2 Incident 2 — Validation des poids minimum contournables

#### Symptôme

Pendant les tests de validation métier, j'ai découvert qu'un utilisateur pouvait :

1. Sélectionner un poids valide (ex: 500g) sur une page produit
2. Modifier la valeur dans l'inspecteur du navigateur (ex: 50g)
3. Ajouter au panier un produit en dessous du poids minimum requis

Cela contournait complètement les règles métier de la boucherie (poids minimum par produit).

#### Analyse de la cause racine

La validation était **uniquement côté client** (JavaScript) :

- Le composant Vue.js `ProductDetail` vérifiait le `minWeight`
- Mais le contrôleur `CartController` ne revalidait pas côté serveur
- Un utilisateur expérimenté pouvait facilement contourner ces validations

#### Correction apportée

J'ai ajouté une **double validation** dans `CartService::addProduct()` :

```php
public function addProduct(Product $product, int $quantity = 1): CartItem {
    // Validation poids minimum côté serveur
    if ($product->getMinWeight() && $quantity < $product->getMinWeight()) {
        throw new \InvalidArgumentException(
            sprintf('Minimum requis : %dg pour %s', 
                $product->getMinWeight(), 
                $product->getName()
            )
        );
    }
    // ... reste de la logique
}
```

#### Prévention et tests

Ajout d'un test unitaire dédié à cette validation :

```php
/**
 * Test validation poids minimum mentionné dans le dossier
 */
public function testValidationPoidsMinimum(): void
{
    // Arrange : Produit avec poids minimum 200g
    $product = $this->createProduct('Steak de bœuf', 28.50);
    $product->setMinWeight(200);

    // Act & Assert : Tentative d'ajout sous le minimum
    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Minimum requis : 200g');
    
    $this->cartService->addProduct($product, 150); // 150g < 200g minimum
}
```

### 12.3 Incident 3 — Fusion automatique des lignes mal gérée

#### Symptôme

Lors des tests du panier intelligent, j'ai constaté des comportements incohérents :

- Même produit ajouté plusieurs fois : parfois fusionné, parfois lignes séparées
- Quantités totales incorrectes après fusion
- Interface affichant des doublons alors que la logique métier prévoyait une fusion

#### Analyse de la cause racine

Le problème venait de la logique de recherche dans `CartService` :

- La méthode cherchait un `CartItem` existant pour le même produit
- Mais la comparaison n'était pas fiable selon les cas
- La fusion n'était pas systématiquement appliquée

#### Correction apportée

J'ai refondu la logique de fusion dans `CartService::addProduct()` :

```php
// Recherche d'un article existant pour ce produit
$existingItem = $this->cartItemRepository->findByCartAndProduct($cart, $product);

if ($existingItem) {
    // Fusion : augmentation de la quantité existante
    $existingItem->increaseQuantity($quantity);
    $cartItem = $existingItem;
} else {
    // Création : nouveau CartItem
    $cartItem = new CartItem();
    $cartItem->setCart($cart);
    $cartItem->setProduct($product);
    $cartItem->setQuantity($quantity);
    $cartItem->setUnitPrice($product->getPrice());
    $cart->addCartItem($cartItem);
}

$this->updateCartTotal($cart);
```

#### Prévention et tests

Test de fusion spécifique ajouté :

```php
/**
 * Test fusion logique lignes identiques
 * Mentionné dans le dossier section 5.2
 */
public function testFusionConceptuelleCartItem(): void
{
    // Arrange : Même produit, quantités différentes
    $product = $this->createProduct('Côte de bœuf', 29.90);
    
    // Act : Ajouts successifs du même produit
    $this->cartService->addProduct($product, 500); // 500g
    $this->cartService->addProduct($product, 300); // +300g
    
    $items = $this->cartService->getCartItems();
    
    // Assert : Fusion automatique
    $this->assertEquals(1, count($items), 'Même produit doit être fusionné en une ligne');
    $this->assertEquals(800, $items[0]->getQuantity(), 'Quantités doivent être additionnées');
    
    $expectedTotal = (800 / 1000) * 29.90; // 800g à 29.90€/kg = 23.92€
    $this->assertEquals(23.92, $items[0]->getTotal(), 'Total après fusion doit être correct', 0.01);
}
```

---

### 12.4 Enseignements transversaux

Ces trois incidents m'ont apporté plusieurs enseignements importants :

#### **Toujours implémenter une validation serveur**
La validation côté client améliore l'UX, mais **seul le serveur** garantit l'intégrité métier. Chaque règle critique doit être validée côté backend.

#### **Les erreurs 500 révèlent souvent des problèmes de contrat**
L'erreur `getSubtotal()` inexistante montrait une **incohérence de nomenclature** entre les différentes couches de l'application.

#### **Les tests unitaires sont indispensables**
Chaque incident résolu a donné lieu à un **test de non-régression** :
- `testCalculTotalCartItem()` pour les calculs
- `testValidationPoidsMinimum()` pour les règles métier
- `testFusionConceptuelleCartItem()` pour la logique de fusion

#### **La documentation des incidents fait gagner du temps**
Formaliser chaque problème sous forme de post-mortem (symptôme → cause → correction → test) permet :
- De **capitaliser sur l'expérience**
- De **préparer efficacement** ce type de dossier académique
- D'**éviter la reproduction** des mêmes erreurs

#### **L'architecture doit être pensée pour la testabilité**
Les services métier centralisés (`CartService`, `PickupSlotService`) permettent de **tester la logique** indépendamment des contrôleurs et de l'interface.

Ces résolutions de problèmes participent à la maturité globale du projet et montrent que **Boucherie Eysa** n'est pas seulement un exercice académique, mais une application construite avec **une démarche professionnelle** de détection, analyse et correction des incidents.