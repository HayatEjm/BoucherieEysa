# 📍 Localisation du CSS du bouton "Finaliser ma commande"

## 🎯 Objectif
Ce guide explique exactement où se trouve le CSS du bouton "Finaliser ma commande" du panier pour que vous puissiez modifier sa couleur facilement.

## 📁 Fichier à modifier

**Fichier :** `assets/styles/cart/cart.css`  
**Lignes :** 268-283 (environ)

## 🎨 Sélecteur CSS exact

```css
.btn-checkout {
    background: linear-gradient(135deg, var(--color-burgundy), var(--color-burgundy-dark));
    color: white;
    border: none;
    padding: 1rem 3rem;
    border-radius: var(--border-radius);
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-strong);
}
```

## 🛠️ Comment modifier la couleur

### Option 1 : Couleur unie
Pour une couleur unie rouge par exemple :
```css
.btn-checkout {
    background: #e53e3e; /* Rouge */
    /* ... reste du code inchangé ... */
}
```

### Option 2 : Dégradé personnalisé
Pour un dégradé bleu par exemple :
```css
.btn-checkout {
    background: linear-gradient(135deg, #3182ce, #2c5aa0);
    /* ... reste du code inchangé ... */
}
```

### Option 3 : Utiliser les couleurs du design system
Pour utiliser une couleur déjà définie dans le design system :
```css
.btn-checkout {
    background: var(--color-success); /* Vert */
    /* ou */
    background: var(--color-danger);  /* Rouge */
    /* ou */
    background: var(--color-warning); /* Jaune/Orange */
    /* ... reste du code inchangé ... */
}
```

## 🎨 Couleurs disponibles dans le design system

Vous pouvez voir toutes les couleurs disponibles dans le fichier :  
`assets/styles/design-system-new.css` (lignes 16-30)

- `var(--color-burgundy)` : Bordeaux principal
- `var(--color-burgundy-dark)` : Bordeaux foncé
- `var(--color-success)` : Vert de succès
- `var(--color-danger)` : Rouge de danger
- `var(--color-warning)` : Orange d'avertissement
- `var(--color-cream)` : Beige crème
- etc.

## ⚡ Après modification

1. **Sauvegarder** le fichier `cart.css`
2. **Recompiler** les assets :
   ```bash
   npm run build
   ```
3. **Rafraîchir** la page panier dans votre navigateur

## 🔍 Exemple pratique

Si vous voulez un bouton vert, modifiez la ligne comme ceci :
```css
.btn-checkout {
    background: var(--color-success); /* Au lieu du dégradé bordeaux */
    /* ... reste inchangé ... */
}
```

## ✅ État actuel

- ✅ Les styles forcés avec `!important` ont été supprimés
- ✅ Le CSS du bouton est maintenant facile à modifier
- ✅ Le bouton utilise le dégradé bordeaux par défaut
- ✅ Vous pouvez changer la couleur en modifiant uniquement la propriété `background`

---

**Note :** Cette modification n'affectera que le bouton "Finaliser ma commande" du panier. Les autres boutons (continuer les achats, vider le panier) restent inchangés.
