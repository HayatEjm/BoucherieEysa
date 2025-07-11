# 🎯 GUIDE SIMPLE - Modifier les cartes d'avantages

## 📐 **POUR CHANGER LES DIMENSIONS DES CARTES**

Ouvrez le fichier : `assets/styles/partials/click_collect.css`

Cherchez la section : `/* CARTES - DIMENSIONS FACILES À CHANGER */`

## ✏️ **Modifications principales**

### 🔲 **Pour avoir des cartes plus carrées :**
```css
.advantage-card {
    height: 280px;  /* ← CHANGEZ CETTE VALEUR */
    width: 100%;
}
```

### 📏 **Valeurs recommandées selon le format :**
- **Format carré** : `height: 280px`
- **Format rectangle** : `height: 250px` 
- **Format compact** : `height: 200px`

### 📦 **Pour changer l'espace intérieur :**
```css
.advantage-card {
    padding: 30px 20px;  /* ← CHANGEZ ICI */
}
```
- **Plus d'espace** : `40px 25px`
- **Moins d'espace** : `20px 15px`

### 🎯 **Pour changer l'espace entre les cartes :**
```css
.advantages-grid {
    gap: 30px;  /* ← CHANGEZ ICI */
}
```
- **Plus d'espace** : `40px`
- **Moins d'espace** : `20px`

### 📐 **Pour changer la largeur du conteneur :**
```css
.advantages-grid {
    max-width: 800px;  /* ← CHANGEZ ICI */
}
```
- **Plus large** : `900px`
- **Plus étroit** : `700px`

## 🎨 **Autres éléments faciles à modifier**

### 🔤 **Taille des icônes :**
```css
.advantage-card .advantage-icon i {
    font-size: 3rem;  /* ← CHANGEZ ICI */
}
```

### 📝 **Taille des titres :**
```css
.advantage-card h4 {
    font-size: 1.3rem;  /* ← CHANGEZ ICI */
}
```

### 📄 **Taille du texte :**
```css
.advantage-card p {
    font-size: 0.95rem;  /* ← CHANGEZ ICI */
}
```

## 💡 **CONSEIL DÉBUTANT**

1. **Changez une valeur à la fois**
2. **Sauvegardez le fichier**
3. **Rechargez la page** pour voir le résultat
4. **Ajustez si nécessaire**

## 🔄 **Pour appliquer les changements**

Après modification, lancez dans le terminal :
```bash
npm run build
```

Puis rechargez votre page `/click-collect` dans le navigateur.

---

**Le CSS est maintenant simple et commenté pour que vous puissiez facilement le personnaliser ! 🎯**
