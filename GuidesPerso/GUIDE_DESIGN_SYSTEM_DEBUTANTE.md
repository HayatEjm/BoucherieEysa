# 🎨 GUIDE DÉBUTANTE - Design System Simplifié

## 🔧 **Comment modifier les couleurs principales ?**

Dans le fichier `assets/styles/design-system-new.css`, vous n'avez besoin de changer que **4 couleurs principales** :

### 🔴 **Pour changer le bordeaux (boutons, liens) :**
```css
--primary-color: #8B1538;     ← CHANGEZ ICI pour modifier tous les boutons "Commander"
```

### 🟤 **Pour changer le beige (fond de page) :**
```css
--beige-light: #FAF7F2;       ← CHANGEZ ICI pour modifier le fond général
```

### ⚫ **Pour changer le noir (header/footer) :**
```css
--header-bg: #1a1a1a;         ← CHANGEZ ICI pour modifier la couleur du header/footer
```

### ⚫ **Pour changer le gris des textes :**
```css
--text-primary: #2c2c2c;      ← CHANGEZ ICI pour modifier la couleur des textes
```

---

## 📁 **Où sont utilisées ces couleurs ?**

### 🔴 **Bordeaux (--primary-color)** est utilisé dans :
- ✅ `click_collect.css` → Boutons "Commander", icônes
- ✅ `page_banner.css` → Bouton du bandeau
- ✅ `footer.css` → Liens du footer
- ✅ `category_list.css` → Liens des catégories

### 🟤 **Beige (--beige-light)** est utilisé dans :
- ✅ Fond général de toutes les pages
- ✅ `click_collect.css` → Arrière-plans des sections
- ✅ Cartes produits et catégories

### ⚫ **Noir (--header-bg)** est utilisé dans :
- ✅ `header.css` → Couleur du header
- ✅ `footer.css` → Couleur du footer

---

## 🎯 **Exemple pratique : Changer en bleu**

Si vous voulez passer du bordeaux au bleu :

### Avant (bordeaux) :
```css
--primary-color: #8B1538;
```

### Après (bleu) :
```css
--primary-color: #1E40AF;
```

**Résultat :** Tous vos boutons "Commander" et liens importants deviennent bleus automatiquement !

---

## 📝 **Les autres variables expliquées**

### 📏 **Tailles de texte** - Si vous voulez agrandir/réduire :
```css
--font-size-base: 1rem;       ← Texte normal (16px)
--font-size-lg: 1.125rem;     ← Texte important (18px) 
--font-size-2xl: 1.5rem;      ← Titres de section (24px)
```

### 📐 **Espacements** - Si vous voulez plus/moins d'espace :
```css
--spacing-md: 1rem;           ← Espacement normal (16px)
--spacing-xl: 2rem;           ← Grand espacement (32px)
--spacing-3xl: 4rem;          ← Très grand espacement (64px)
```

### 🔘 **Coins arrondis** - Si vous voulez plus/moins arrondi :
```css
--border-radius-md: 8px;      ← Coins moyennement arrondis
--border-radius-lg: 12px;     ← Coins bien arrondis
```

---

## ⚠️ **Règles importantes :**

### ✅ **À FAIRE :**
- Changez uniquement les **valeurs** (après les `:`)
- Gardez toujours les `;` à la fin
- Utilisez des couleurs hexadécimales (#123456) ou des noms (red, blue)

### ❌ **À NE PAS FAIRE :**
- Ne supprimez jamais les `--` au début
- Ne supprimez pas les `var()` dans les autres fichiers
- Ne changez pas les noms des variables

---

## 🚀 **Test de vos modifications :**

Après chaque modification :
1. Sauvegardez le fichier
2. Dans le terminal : `npm run build`
3. Actualisez votre navigateur
4. Vérifiez que tout fonctionne

---

## 💡 **Suggestions de couleurs harmonieuses :**

### 🟢 **Thème nature (vert/beige) :**
```css
--primary-color: #065f46;     /* Vert forêt */
--beige-light: #f0fdf4;       /* Vert très clair */
```

### 🔵 **Thème mer (bleu/sable) :**
```css
--primary-color: #0369a1;     /* Bleu océan */
--beige-light: #f0f9ff;       /* Bleu très clair */
```

### 🟠 **Thème chaleureux (orange/crème) :**
```css
--primary-color: #ea580c;     /* Orange chaleureux */
--beige-light: #fffbeb;       /* Orange très clair */
```

---

**Le design system peut sembler complexe, mais vous n'avez besoin de toucher qu'à ces 4 couleurs principales ! 😊**
