# 📁 GUIDE DES FICHIERS CSS - Qui fait quoi ?

## 🗂️ **Structure des fichiers CSS expliquée pour débutante**

Voici tous vos fichiers CSS et leur rôle précis dans votre site :

---

## 🎨 **FICHIER PRINCIPAL : Design System**

### 📄 `assets/styles/design-system-new.css`
**🎯 Rôle :** Contient toutes les couleurs, tailles, espacements de votre site
**🔧 Vous pouvez modifier :** Les 4 couleurs principales (voir `GUIDE_DESIGN_SYSTEM_DEBUTANTE.md`)
**📍 Utilisé dans :** Tous les autres fichiers CSS
**🚨 Important :** Si vous changez une couleur ici, elle change partout !

---

## 📄 **FICHIERS PAR PAGE/SECTION :**

### 🏠 `assets/styles/partials/click_collect.css`
**🎯 Rôle :** Style de la page Click & Collect uniquement
**📍 Pages concernées :** `/click-collect`
**🎨 Contient :** 
- Style de la section "Retrait" avec image de fond
- Cartes avantages (2x2)
- Planning des horaires
- FAQ avec accordéon

### 🎨 `assets/styles/partials/page_banner.css`
**🎯 Rôle :** Style du bandeau du haut (titre + badges + bouton)
**📍 Pages concernées :** Click & Collect (potentiellement d'autres pages)
**🎨 Contient :**
- Bandeau avec titre et sous-titre
- Badges inversés (texte bordeaux sur fond crème)
- Bouton "COMMENCER" centré

### 🖤 `assets/styles/partials/header.css`
**🎯 Rôle :** Style du header noir en haut de page
**📍 Pages concernées :** Toutes les pages
**🎨 Contient :**
- Fond noir du header
- Menu de navigation
- Logo et liens

### 🖤 `assets/styles/partials/footer.css`
**🎯 Rôle :** Style du footer noir en bas de page
**📍 Pages concernées :** Toutes les pages
**🎨 Contient :**
- Fond noir du footer
- Informations pratiques (Saumur, horaires, contact)
- Liens de navigation
- Réseaux sociaux

---

## 🛍️ **FICHIERS PRODUITS/CATÉGORIES :**

### 📦 `assets/styles/category/category_list.css`
**🎯 Rôle :** Style de la page liste des catégories
**📍 Pages concernées :** `/categories`
**🎨 Contient :**
- Grille des catégories
- Cartes catégories avec images
- Liens vers les produits

### 🥩 `assets/styles/product/product_list_simple.css`
**🎯 Rôle :** Style de la liste des produits
**📍 Pages concernées :** `/products`
**🎨 Contient :**
- Grille des produits
- Cartes produits avec prix
- Boutons "Voir le détail"

### 📋 `assets/styles/product/product_detail.css`
**🎯 Rôle :** Style de la page détail d'un produit
**📍 Pages concernées :** `/product/123`
**🎨 Contient :**
- Image produit en grand
- Description détaillée
- Prix et informations
- Bouton d'achat

---

## 📱 **FICHIER GÉNÉRAL :**

### 📄 `assets/styles/app.css`
**🎯 Rôle :** Styles généraux et corrections globales
**📍 Pages concernées :** Toutes les pages
**🎨 Contient :**
- Styles généraux pour tout le site
- Corrections et ajustements globaux

---

## 🔗 **COMMENT CES FICHIERS SONT LIÉS ?**

### 📥 Dans `assets/app.js` (fichier d'entrée) :
```javascript
import './styles/design-system-new.css';    ← Variables globales
import './styles/app.css';                  ← Styles généraux
import './styles/partials/header.css';      ← Header noir
import './styles/partials/footer.css';      ← Footer noir
import './styles/partials/click_collect.css'; ← Page Click & Collect
// ... et tous les autres fichiers
```

### 🔄 **Ordre d'importation important :**
1. **design-system-new.css** → Variables (couleurs, tailles...)
2. **app.css** → Styles généraux
3. **Fichiers spécifiques** → Styles par page/section

---

## ✏️ **QUELS FICHIERS VOUS POUVEZ MODIFIER ?**

### ✅ **FACILE À MODIFIER :**
- **`design-system-new.css`** → Les 4 couleurs principales
- **`click_collect.css`** → Si vous voulez changer la page Click & Collect

### ⚠️ **ATTENTION :**
- **`header.css`** et **`footer.css`** → Utilisés partout, soyez prudente
- **`app.css`** → Affects tout le site

### 🚫 **NE PAS TOUCHER :**
- **`assets/app.js`** → Configuration technique

---

## 🎯 **Exemples pratiques :**

### 💡 **"Je veux changer la couleur des boutons sur tout le site"**
→ Modifiez `--primary-color` dans `design-system-new.css`

### 💡 **"Je veux modifier uniquement la page Click & Collect"**
→ Modifiez `click_collect.css`

### 💡 **"Je veux changer le footer"**
→ Modifiez `footer.css` (attention : change sur toutes les pages)

### 💡 **"Je veux ajouter une nouvelle page"**
→ Créez un nouveau fichier CSS et ajoutez-le dans `app.js`

---

## 🚀 **Processus de modification :**

1. **Modifiez** le fichier CSS approprié
2. **Sauvegardez** le fichier
3. **Compilez** avec `npm run build`
4. **Actualisez** votre navigateur
5. **Vérifiez** le résultat

---

**Avec cette organisation, vous pouvez modifier chaque partie de votre site indépendamment ! 😊**
