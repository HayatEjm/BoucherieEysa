# 🖼️ Activation de l'image de fond - Section Retrait

## 🎯 Option : Ajouter une image de fond à la section "Retrait"

Pour rendre la page encore plus vivante et visuelle, vous pouvez activer une image de fond sur la section retrait.

## 🔧 Instructions d'activation

### Étape 1 : Modifier le CSS
Dans le fichier `assets/styles/partials/click_collect.css`, remplacez :

```css
/* Version avec image de fond (optionnelle - à activer plus tard) */
.pickup-section.with-background {
    background: linear-gradient(
        135deg,
        rgba(245, 238, 220, 0.95) 0%,
        rgba(233, 223, 196, 0.95) 100%
    );
    /* Image de fond à ajouter plus tard : url('/images/preparation.jpg') center/cover; */
    /* background-attachment: fixed; */
}
```

**Par :**

```css
/* Version avec image de fond activée */
.pickup-section.with-background {
    background: linear-gradient(
        135deg,
        rgba(245, 238, 220, 0.95) 0%,
        rgba(233, 223, 196, 0.95) 100%
    ),
    url('/images/preparation.jpg') center/cover;
    background-attachment: fixed;
    min-height: 600px;
}
```

### Étape 2 : Recompiler les assets
```bash
npm run build
```

## 🖼️ Images alternatives disponibles

### Images actuellement présentes
- `preparation.jpg` ✅ **Recommandée** - Thématique préparation des commandes
- `boeuf.jpg` - Viande de bœuf
- `agneau.jpg` - Viande d'agneau  
- `veau.jpg` - Viande de veau
- `volaille.jpg` - Volaille

### 💡 Suggestions d'images personnalisées
1. **Photo de la devanture** de la boucherie
2. **Client récupérant sa commande** au comptoir
3. **Boucher préparant une commande** Click & Collect
4. **Vue d'ensemble du magasin** avec ambiance chaleureuse

## 🎨 Personnalisation avancée

### Ajuster l'opacité de l'overlay
Pour plus ou moins de contraste :

```css
/* Plus transparent (image plus visible) */
rgba(245, 238, 220, 0.85) 0%,
rgba(233, 223, 196, 0.85) 100%

/* Plus opaque (texte plus lisible) */
rgba(245, 238, 220, 0.98) 0%,
rgba(233, 223, 196, 0.98) 100%
```

### Alternative sans parallaxe
Si `background-attachment: fixed` pose des problèmes sur mobile :

```css
.pickup-section.with-background {
    background: linear-gradient(/*...*/),
                url('/images/preparation.jpg') center/cover;
    background-attachment: scroll; /* Au lieu de fixed */
}
```

### Version dégradé uniquement
Pour un effet plus subtil sans image :

```css
.pickup-section.with-background {
    background: linear-gradient(
        135deg,
        var(--beige-light) 0%,
        var(--beige-medium) 50%,
        rgba(139, 0, 0, 0.1) 100%
    );
}
```

## 📱 Test responsive

Après activation, testez sur :
- **Desktop** - Image fixe en parallaxe
- **Tablette** - Image adaptée à la largeur
- **Mobile** - Image optimisée pour petit écran

## ⚠️ Notes importantes

1. **Performance** : L'image de fond peut impacter légèrement le temps de chargement
2. **Accessibilité** : Vérifiez le contraste du texte sur l'image
3. **SEO** : Optimisez l'image (format WebP, compression)
4. **Mobile** : L'effet parallaxe peut être désactivé automatiquement sur mobile

## 🎯 Résultat attendu

Avec l'image de fond activée :
- ✅ **Page plus vivante** et visuelle
- ✅ **Cohérence** avec l'ambiance chaleureuse de la boucherie  
- ✅ **Différenciation** de cette section importante
- ✅ **Professionnalisme** renforcé

---

💡 **Conseil** : Commencez par tester sans image de fond, puis activez-la si vous souhaitez plus d'impact visuel.
