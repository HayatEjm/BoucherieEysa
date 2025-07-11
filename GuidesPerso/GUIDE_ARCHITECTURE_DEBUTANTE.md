# 🏗️ GUIDE D'ARCHITECTURE POUR DÉBUTANTE - BOUCHERIE EYSA

> 📚 **Ce guide t'explique simplement comment ton site fonctionne et où modifier quoi !**

## 🎯 TABLE DES MATIÈRES
- [🗂️ Organisation des fichiers](#organisation-des-fichiers)
- [🎨 Modifier l'apparence (CSS)](#modifier-lapparence-css)
- [📄 Modifier le contenu (HTML/Twig)](#modifier-le-contenu-htmltwig)
- [⚙️ Logique métier (PHP/Symfony)](#logique-métier-phpsymfony)
- [🛒 Système de panier](#système-de-panier)
- [🔄 Flux complet d'une page](#flux-complet-dune-page)
- [📝 Que faire quand tu veux...](#que-faire-quand-tu-veux)

---

## 🗂️ ORGANISATION DES FICHIERS

### 📁 **Structure principale**
```
BoucherieEysa/
├── assets/              ← Tes fichiers CSS et JS
├── src/                 ← Logique PHP (contrôleurs, entités...)
├── templates/           ← Tes pages HTML (Twig)
├── public/              ← Images, fichiers publics
└── GuidesPerso/         ← Tes guides (comme celui-ci !)
```

### 🎨 **Dossier `assets/` - TON DESIGN**
```
assets/
├── styles/
│   ├── app.css                    ← FICHIER PRINCIPAL qui importe tout
│   ├── design-system-new.css      ← Couleurs et variables globales
│   ├── home.css                   ← Page d'accueil
│   ├── partials/
│   │   ├── header.css             ← Header (navigation)
│   │   ├── footer.css             ← Footer
│   │   └── click_collect.css      ← Section Click & Collect
│   ├── product/
│   │   ├── product_detail.css     ← Page produit (calculateur, etc.)
│   │   └── product_list_simple.css ← Liste des produits
│   └── cart/
│       ├── cart.css               ← Page panier
│       └── cart_badge.css         ← Badge compteur panier
└── js/
    └── cart.js                    ← Fonctions JavaScript du panier
```

### 📄 **Dossier `templates/` - TES PAGES**
```
templates/
├── base.html.twig               ← Template de base (header, footer)
├── home/
│   └── index.html.twig          ← Page d'accueil
├── product/
│   ├── product_list.html.twig   ← Liste des produits
│   └── product_detail.html.twig ← Détail d'un produit
├── cart/
│   └── index.html.twig          ← Page du panier
└── partials/
    ├── header.html.twig         ← Header réutilisable
    └── footer.html.twig         ← Footer réutilisable
```

### ⚙️ **Dossier `src/` - LA LOGIQUE**
```
src/
├── Controller/              ← Routes et logique des pages
│   ├── HomeController.php   ← Page d'accueil
│   ├── ProductController.php ← Pages produits
│   └── CartController.php   ← Gestion du panier
├── Entity/                  ← Tes "objets" (Produit, Panier...)
│   ├── Product.php          ← Définition d'un produit
│   ├── Cart.php             ← Définition d'un panier
│   └── CartItem.php         ← Article dans le panier
├── Repository/              ← Requêtes base de données
└── Service/                 ← Services (panier, AWS...)
    └── CartService.php      ← Logique du panier
```

---

## 🎨 MODIFIER L'APPARENCE (CSS)

### 🌈 **Changer les couleurs globales**
📁 **Fichier :** `assets/styles/design-system-new.css`
```css
:root {
    --color-primary: #8B0000;     ← Rouge principal
    --color-secondary: #F5E6D3;   ← Beige/crème
    --color-text: #2C1810;        ← Texte principal
}
```

### 🏠 **Modifier la page d'accueil**
📁 **Fichier :** `assets/styles/home.css`
- Section hero, avantages, etc.

### 🧭 **Modifier le header/navigation**
📁 **Fichiers :**
- **CSS :** `assets/styles/partials/header.css`
- **HTML :** `templates/partials/header.html.twig`

### 🛒 **Modifier le panier**
📁 **Fichiers :**
- **CSS :** `assets/styles/cart/cart.css`
- **HTML :** `templates/cart/index.html.twig`
- **Badge :** `assets/styles/cart/cart_badge.css`

### 📦 **Modifier la page produit**
📁 **Fichiers :**
- **CSS :** `assets/styles/product/product_detail.css`
- **HTML :** `templates/product/product_detail.html.twig`

> 💡 **IMPORTANT :** Après toute modification CSS, lance `npm run build` !

---

## 📄 MODIFIER LE CONTENU (HTML/TWIG)

### 🏠 **Page d'accueil**
📁 **Fichier :** `templates/home/index.html.twig`
- Titre principal, sections, textes...

### 📦 **Page produit**
📁 **Fichier :** `templates/product/product_detail.html.twig`
- Description, calculateur "⚖️ Pesez votre viande", boutons...

### 🛒 **Page panier**
📁 **Fichier :** `templates/cart/index.html.twig`
- Affichage des articles, total, TVA...

### 🧭 **Navigation (menu)**
📁 **Fichier :** `templates/partials/header.html.twig`
- Liens du menu, logo, etc.

---

## ⚙️ LOGIQUE MÉTIER (PHP/SYMFONY)

### 🎯 **Contrôleurs - Tes "pages"**

#### 🏠 **HomeController.php**
```php
#[Route('/', name: 'app_home')]
public function index(): Response
```
→ Gère la page d'accueil à l'URL `/`

#### 📦 **ProductController.php**
```php
#[Route('/products', name: 'app_products')]      ← Liste produits
#[Route('/product/{id}', name: 'app_product_show')] ← Détail produit
```

#### 🛒 **CartController.php**
```php
#[Route('/panier', name: 'app_cart_index')]           ← Voir le panier
#[Route('/panier/add/{id}', name: 'app_cart_add')]    ← Ajouter produit
#[Route('/panier/remove/{id}', name: 'app_cart_remove')] ← Supprimer
```

### 📊 **Entités - Tes "objets"**

#### 📦 **Product.php**
- Propriétés : nom, prix, description, image...
- Méthodes : getters/setters

#### 🛒 **Cart.php**
- Propriétés : articles, total, statut...
- **Méthodes importantes :**
  - `getTotalPrice()` : Prix total HT
  - `getTotalTTC()` : Prix total TTC (avec TVA)
  - `getTaxAmount()` : Montant de la TVA
  - `isEmpty()` : Panier vide ?

#### 🛍️ **CartItem.php**
- Un article dans le panier
- Propriétés : produit, quantité, total...

---

## 🛒 SYSTÈME DE PANIER

### 🔄 **Comment ça marche**
1. **Clic "Ajouter au panier"** → Route `/panier/add/{id}`
2. **CartController** reçoit la demande
3. **CartService** gère la logique (ajouter, calculer...)
4. **Sauvegarde** en base de données
5. **Réponse JSON** pour mettre à jour la page
6. **JavaScript** met à jour le badge et affichage

### 📁 **Fichiers du panier**
```
🎯 LOGIQUE
├── src/Controller/CartController.php     ← Routes du panier
├── src/Service/CartService.php          ← Logique métier
├── src/Entity/Cart.php                  ← Objet panier
└── src/Entity/CartItem.php             ← Article panier

🎨 AFFICHAGE
├── templates/cart/index.html.twig       ← Page panier
├── assets/styles/cart/cart.css          ← Styles panier
├── assets/styles/cart/cart_badge.css    ← Badge compteur
└── assets/js/cart.js                    ← Interactions JS
```

### 💰 **TVA (5,5%)**
La TVA est calculée automatiquement :
- **Méthode :** `Cart::getTaxAmount()`
- **Affichage :** Ligne séparée dans le panier
- **Format :** Prix TTC + ligne TVA

---

## 🔄 FLUX COMPLET D'UNE PAGE

### 📦 **Exemple : Afficher un produit**
```
1. Utilisateur clique sur un produit
   ↓
2. URL : /product/5
   ↓
3. ProductController::show($product)
   ↓
4. Récupère le produit en base (Product.php)
   ↓
5. Passe les données au template
   ↓
6. templates/product/product_detail.html.twig
   ↓
7. Applique les styles CSS
   ↓
8. Page affichée avec calculateur, boutons...
```

### 🛒 **Exemple : Ajouter au panier**
```
1. Clic "Ajouter au panier"
   ↓
2. JavaScript (cart.js) envoie requête AJAX
   ↓
3. Route : /panier/add/5
   ↓
4. CartController::add($product)
   ↓
5. CartService gère l'ajout
   ↓
6. Sauvegarde en base (Cart.php, CartItem.php)
   ↓
7. Retour JSON avec nouveau total
   ↓
8. JavaScript met à jour l'affichage
```

---

## 📝 QUE FAIRE QUAND TU VEUX...

### 🎨 **Changer une couleur**
1. ✏️ Modifier `assets/styles/design-system-new.css`
2. 🔨 Lancer `npm run build`
3. ✅ Actualiser la page

### 📝 **Modifier un texte**
1. ✏️ Trouver le bon template `.html.twig`
2. 📝 Modifier le texte
3. ✅ Actualiser la page (pas besoin de build)

### 🛒 **Modifier le panier**
- **Affichage :** `templates/cart/index.html.twig`
- **Styles :** `assets/styles/cart/cart.css`
- **Logique :** `src/Controller/CartController.php`
- **Calculs :** `src/Entity/Cart.php`

### 📦 **Ajouter un nouveau produit**
1. 🗄️ Ajouter en base de données
2. 🖼️ Ajouter image dans `public/images/`
3. ✅ Le produit apparaît automatiquement

### 🎯 **Créer une nouvelle page**
1. 📁 Nouveau contrôleur dans `src/Controller/`
2. 📄 Nouveau template dans `templates/`
3. 🎨 Nouveau CSS dans `assets/styles/`
4. 📝 Ajouter import dans `assets/styles/app.css`

### 🚨 **Problème d'affichage**
1. 🔍 Vérifier le CSS dans les outils développeur
2. 🔨 Relancer `npm run build`
3. 🧹 Vider le cache : `php bin/console cache:clear`

### 🛠️ **Débugger**
- **Logs Symfony :** `var/log/dev.log`
- **Erreurs PHP :** Affiché dans le navigateur
- **Console navigateur :** F12 → Console

---

## 🎯 RACCOURCIS UTILES

### 🔨 **Commandes fréquentes**
```bash
npm run build          # Compiler les assets CSS/JS
npm run watch          # Mode développement (auto-compile)
php bin/console cache:clear  # Vider le cache
```

### 📁 **Fichiers les plus modifiés**
- `assets/styles/design-system-new.css` → Couleurs
- `templates/partials/header.html.twig` → Navigation
- `templates/home/index.html.twig` → Page d'accueil
- `assets/styles/home.css` → Styles page d'accueil

---

## 💡 CONSEILS DE DÉBUTANTE

### ✅ **Bonnes pratiques**
- 🔨 Toujours `npm run build` après modification CSS
- 💾 Sauvegarder avant gros changements
- 🧪 Tester sur mobile (mode responsive)
- 📝 Un fichier CSS par fonctionnalité

### ⚠️ **Attention à...**
- Ne pas modifier `public/build/` (généré automatiquement)
- Respecter l'indentation dans les templates Twig
- Toujours vérifier les logs en cas d'erreur

### 🆘 **En cas de problème**
1. 🔨 `npm run build`
2. 🧹 `php bin/console cache:clear`
3. 🔄 Actualiser la page
4. 🔍 Vérifier les logs

---

## 🎉 CONCLUSION

Ton site Boucherie Eysa est maintenant **bien organisé et documenté** ! 

Chaque fichier a sa fonction, et tu sais maintenant :
- 🎨 Où modifier l'apparence
- 📝 Où changer les textes
- ⚙️ Comment le panier fonctionne
- 🔧 Que faire en cas de problème

**Tu es prête à être autonome !** 🚀

> 📚 **Ce guide évolue avec ton projet.** N'hésite pas à le compléter au fur et à mesure !
