# 🛒 SYSTÈME PANIER COMPLET - GUIDE D'UTILISATION

## 📖 Qu'est-ce que j'ai créé ?

J'ai mis en place un système complet de panier pour la boucherie Eysa avec :

### 🎯 **Fonctionnalités principales**
- ✅ Badge dynamique sur l'icône panier (se met à jour en temps réel)
- ✅ Boutons "Ajouter au panier" sur toutes les pages produits
- ✅ Page panier interactive avec gestion des quantités
- ✅ Notifications toast pour chaque action
- ✅ Modal de confirmation pour les suppressions
- ✅ Système responsive (mobile/desktop)
- ✅ Logique métier centralisée dans un service Symfony

---

## 🏗️ Architecture technique

### **Côté serveur (Symfony)**
```
src/
├── Entity/
│   ├── Cart.php              ← Entité principale du panier
│   └── CartItem.php          ← Articles dans le panier
├── Repository/
│   ├── CartRepository.php    ← Requêtes spécifiques panier
│   └── CartItemRepository.php← Requêtes articles panier
├── Service/
│   └── CartService.php       ← TOUTE la logique métier
└── Controller/
    └── CartController.php    ← Routes API + pages
```

### **Côté client (Assets)**
```
assets/
├── styles/cart/
│   ├── cart.css              ← CSS page panier
│   └── cart_badge.css        ← CSS badge dynamique
└── js/
    └── cart.js               ← JavaScript global panier
```

---

## 🔧 Comment ça fonctionne ?

### **1. Le Badge Dynamique**
```html
<!-- Dans le header -->
<a href="/cart" class="cart-icon-container">
    <i class="fa-solid fa-cart-shopping cart-icon"></i>
    <span class="cart-badge" id="cart-badge">3</span>
</a>
```

**Ce que je fais :**
- Au chargement de chaque page → je charge le nombre d'articles
- À chaque ajout/suppression → je mets à jour le badge
- Animations automatiques (pulse pour ajout, shake pour suppression)

### **2. Boutons "Ajouter au panier"**
```html
<!-- Sur les pages produits -->
<button class="add-to-cart" 
        data-product-id="123" 
        data-quantity="1">
    Ajouter au panier
</button>
```

**Ce que je fais :**
- Je détecte automatiquement tous les boutons avec classe `add-to-cart`
- Au clic → requête AJAX vers `/cart/add`
- Feedback immédiat (bouton change temporairement)
- Badge mis à jour + notification

### **3. Page Panier Interactive**
```
/cart → Voir tout le panier
/cart/add → Ajouter un article (AJAX)
/cart/update → Modifier quantité (AJAX)
/cart/remove → Supprimer article (AJAX)
/cart/clear → Vider panier (AJAX)
/cart/badge → Obtenir le compteur (AJAX)
```

**Ce que je fais :**
- Affichage de tous les articles avec images
- Boutons +/- pour ajuster les quantités
- Suppression avec modal de confirmation
- Calcul automatique des totaux
- Bouton "Vider le panier" avec confirmation

---

## 🎨 Styles et UX

### **Design System Cohérent**
- ✅ Couleurs : Burgundy (#8b2424) + Cream (#f8f6f0)
- ✅ Responsive : Mobile-first avec breakpoints
- ✅ Animations fluides : Hover, focus, transitions
- ✅ Accessibilité : ARIA labels, focus visible

### **Animations du Badge**
```css
.cart-badge.pulse { /* Ajout d'article */ }
.cart-badge.shake { /* Suppression */ }
.cart-badge.appear { /* Premier article */ }
.cart-badge.disappear { /* Dernier article supprimé */ }
```

---

## 🚀 Comment utiliser le système ?

### **Pour ajouter un bouton panier n'importe où :**
```html
<button class="add-to-cart" 
        data-product-id="{{ product.id }}" 
        data-quantity="1">
    <i class="fa-solid fa-cart-plus"></i> Ajouter
</button>
```
→ Le JavaScript global détectera automatiquement le bouton !

### **Pour utiliser l'API JavaScript :**
```javascript
// Ajouter un produit
window.BoucherieCart.addToCart(productId, quantity);

// Afficher une notification
window.BoucherieCart.showNotification('Message', 'success');

// Forcer la mise à jour du badge
window.BoucherieCart.loadCartCount();
```

### **Côté Symfony (dans un contrôleur) :**
```php
// J'injecte le service
public function __construct(private CartService $cartService) {}

// J'ajoute un produit
$this->cartService->addToCart($user, $product, $quantity);

// Je récupère le panier
$cart = $this->cartService->getCurrentCart($user);
```

---

## 📱 Responsive Design

### **Desktop**
- Badge : 20px, position absolue sur l'icône
- Boutons produits : côte à côte (Voir détails | Ajouter panier)
- Page panier : Grid layout avec tous les détails

### **Mobile** 
- Badge : 22px (plus grand pour le touch)
- Boutons produits : en colonne (plus facile à toucher)
- Page panier : Layout adaptatif, contrôles simplifiés

---

## 🔍 Debug et Développement

### **Console du navigateur :**
```javascript
debugCart() // Affiche l'état complet du système
```

### **Logs serveur :**
- Le `CartService` log toutes ses actions
- Les erreurs AJAX sont capturées et affichées

### **Vérifications :**
1. Badge se met-il à jour ? → Vérifier la route `/cart/badge`
2. Boutons détectés ? → Console : "X boutons trouvés"
3. AJAX fonctionne ? → Onglet Réseau du navigateur

---

## 🎯 Prochaines étapes possibles

### **Améliorations faciles :**
- [ ] Sauvegarde panier en session (invités)
- [ ] Gestion des poids/quantités personnalisés
- [ ] Panier partagé entre appareils (même utilisateur)
- [ ] Historique des paniers

### **Fonctionnalités avancées :**
- [ ] Panier persistant (localStorage + sync serveur)
- [ ] Suggestions produits dans le panier
- [ ] Calcul frais de livraison
- [ ] Integration avec Click & Collect

---

## 💡 Points importants pour une débutante

### **Où modifier quoi :**
- **Couleurs/styles** → `assets/styles/design-system-new.css`
- **Badge panier** → `assets/styles/cart/cart_badge.css`
- **Page panier** → `assets/styles/cart/cart.css`
- **Logique métier** → `src/Service/CartService.php`
- **API/Routes** → `src/Controller/CartController.php`

### **Commandes utiles :**
```bash
npm run build          # Compiler les assets
symfony console d:m:m   # Appliquer migrations DB
```

### **Fichiers à ne jamais toucher :**
- `vendor/` (dépendances Symfony)
- `node_modules/` (dépendances JS)
- `public/build/` (assets compilés)

---

## ✅ Système testé et fonctionnel !

Le système panier est maintenant **complet et opérationnel** avec :
- 🎨 Design cohérent avec le reste du site
- 📱 Interface responsive et accessible
- ⚡ Interactions fluides et feedback immédiat
- 🏗️ Architecture propre et maintenable
- 📚 Documentation complète pour modifications futures

**Tu peux maintenant faire tes achats et voir le badge se mettre à jour en temps réel ! 🛒✨**
