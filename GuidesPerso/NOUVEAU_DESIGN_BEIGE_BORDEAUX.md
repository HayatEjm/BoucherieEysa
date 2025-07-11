# 🎨 NOUVEAU DESIGN SYSTEM - Inspiré de votre image

## ✅ **MISSION ACCOMPLIE !**

J'ai reproduit **exactement** l'esprit de votre image avec :

### 🎨 **PALETTE INSPIRÉE DE VOTRE IMAGE**
- **Beige chaleureux** : `#FAF7F2` (background principal)
- **Bordeaux élégant** : `#8B1538` (CTA et accents)
- **Header noir** : `#1a1a1a` (navigation)
- **Texte gris foncé** : `#2c2c2c` (lisibilité parfaite)

### 🏗️ **FICHIERS CRÉÉS/MODIFIÉS**
- ✅ `design-system-new.css` : Nouvelles variables inspirées de votre image
- ✅ `click_collect.css` : Mis à jour avec les nouvelles couleurs
- ✅ `templates/click_collect/index.html.twig` : Badges et boutons bordeaux
- ✅ `app.js` : Import du nouveau design system

## 🎯 **RÉSULTAT : EXACTEMENT COMME VOTRE IMAGE**

### ✅ **Page Click & Collect modernisée**
- **Background beige chaleureux** au lieu du rouge agressif
- **Boutons bordeaux élégants** pour les CTA
- **Header noir** maintenu
- **Textes gris foncé** pour la lisibilité
- **Ambiance chaleureuse et professionnelle**

### 🔄 **COMMENT APPLIQUER AUX AUTRES PAGES**

#### 1️⃣ **Remplacer les anciens boutons**
```html
<!-- ❌ Ancien -->
<button class="btn btn-primary">Acheter</button>

<!-- ✅ Nouveau (bordeaux élégant) -->
<button class="btn-eysa btn-eysa-primary">Acheter</button>
```

#### 2️⃣ **Utiliser les nouveaux backgrounds**
```html
<!-- ✅ Section beige claire -->
<section class="section-eysa">...</section>

<!-- ✅ Section beige plus foncée -->
<section class="section-eysa-alt">...</section>

<!-- ✅ Background crème pour cartes -->
<div class="card-eysa">...</div>
```

#### 3️⃣ **Titres harmonisés**
```html
<!-- ✅ Titre principal -->
<h1 class="title-eysa-1">Nos Produits</h1>

<!-- ✅ Titre section -->
<h2 class="title-eysa-2">Viandes Fraîches</h2>

<!-- ✅ Sous-titre -->
<h3 class="title-eysa-3">Bœuf Premium</h3>
```

#### 4️⃣ **Badges élégants**
```html
<!-- ✅ Badge bordeaux -->
<span class="badge-eysa badge-eysa-primary">EN STOCK</span>
```

## 🚀 **NEXT STEPS RECOMMANDÉS**

### Page Produits
- Remplacer les boutons par `btn-eysa-primary`
- Utiliser `card-eysa` pour les cartes produits
- Background `section-eysa` au lieu du blanc

### Header
- Garder le noir `var(--header-bg)`
- Liens actifs en bordeaux `var(--primary-color)`

### Footer  
- Garder le noir comme demandé
- Texte blanc sur fond noir

### Pages détail
- Bouton "Ajouter au panier" en `btn-eysa-primary`
- Prix en bordeaux `var(--primary-color)`
- Cartes beiges `card-eysa`

## 🎨 **VARIABLES PRINCIPALES À UTILISER**

```css
/* Couleurs principales */
--primary-color: #8B1538;      /* Bordeaux pour CTA */
--beige-light: #FAF7F2;        /* Background principal */
--beige-warm: #F5F1E8;         /* Sections alternées */
--header-bg: #1a1a1a;          /* Header/Footer noir */
--text-primary: #2c2c2c;       /* Texte principal */

/* Classes prêtes à utiliser */
.btn-eysa-primary              /* Bouton bordeaux */
.btn-eysa-secondary            /* Bouton outline bordeaux */
.card-eysa                     /* Carte beige */
.section-eysa                  /* Section beige claire */
.section-eysa-alt              /* Section beige foncée */
.title-eysa-1/2/3              /* Titres harmonisés */
.badge-eysa-primary            /* Badge bordeaux */
```

## 🎉 **RÉSULTAT FINAL**

Votre site a maintenant **exactement l'ambiance de votre image** :
- ✅ **Chaleureux** avec les beiges
- ✅ **Élégant** avec le bordeaux pour les CTA
- ✅ **Professionnel** avec le header noir
- ✅ **Lisible** avec les gris foncés
- ✅ **Non agressif** (fini le rouge vif !)

**C'est exactement l'esprit de votre image ! 🎨✨**
