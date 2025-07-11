# ✅ CHECKLIST DE VÉRIFICATION - CORRECTIONS CSS URGENTES

## 🎯 Tests à Effectuer Après les Corrections

### ✅ 1. Test des Spinners (Inputs Number)

**Pages à tester :**
- [ ] `/products/1/enhanced` - Champ quantité
- [ ] `/cart` - Modification quantités panier  
- [ ] Tous les autres formulaires avec inputs number

**Vérifications :**
- [ ] Aucune flèche (spinner) visible à droite des champs number
- [ ] Le champ reste fonctionnel (on peut taper des chiffres)
- [ ] Les boutons + et - marchent toujours
- [ ] Test sur Chrome ✅ | Firefox ✅ | Safari ✅ | Edge ✅

---

### ✅ 2. Test du Min-Weight

**Page à tester :** `/products/1/enhanced`

**Vérifications :**
- [ ] La valeur initiale du champ = minWeight du produit (pas 100 fixe)
- [ ] Impossible de descendre en dessous du minimum
- [ ] Le navigateur affiche une alerte si on essaie
- [ ] Le JavaScript respecte aussi cette contrainte

**Comment tester :**
1. Aller sur la page d'un produit
2. Regarder la valeur par défaut du champ quantité
3. Essayer de mettre une valeur plus petite que le minimum
4. ✅ Le navigateur doit empêcher ou alerter

---

### ✅ 3. Test des Variables CSS

**Vérifications automatiques :**
- [ ] Aucune erreur dans la console (F12 → Console)
- [ ] Les couleurs s'affichent correctement (pas de blanc bizarre)
- [ ] Les pages se chargent sans problème

**En cas d'erreur CSS :**
```
Erreur typique : "var(--color-burgundy) is not defined"
✅ Solution : Les nouvelles variables dans corrections-urgentes.css règlent ça
```

---

### ✅ 4. Test du Contraste et Lisibilité

**Pages à tester :**
- [ ] Toutes les pages avec des boutons
- [ ] Pages avec des badges (Fresh, Local, etc.)
- [ ] Textes sur fonds colorés

**Vérifications :**
- [ ] Aucun texte blanc sur fond blanc/clair
- [ ] Tous les textes sont parfaitement lisibles
- [ ] Les boutons ont un bon contraste
- [ ] Focus visible sur les éléments (Tab pour naviguer)

**Test d'accessibilité simple :**
1. Appuyer sur Tab pour naviguer
2. ✅ Chaque élément cliquable doit être bien visible quand sélectionné
3. Utiliser les flèches directionnelles dans les menus

---

### ✅ 5. Test Responsive (Mobile)

**Vérifications sur mobile / petite fenêtre :**
- [ ] Boutons +/- de quantité pas trop petits
- [ ] Champs de saisie lisibles
- [ ] Aucun dépassement horizontal
- [ ] Textes toujours lisibles

**Comment tester :**
```
1. F12 dans le navigateur
2. Cliquer sur l'icône mobile (ou Ctrl+Shift+M)
3. Tester différentes tailles d'écran
4. Vérifier que tout reste utilisable
```

---

## 🔧 Commandes de Débogage

### Si les corrections ne s'appliquent pas :

```bash
# 1. Recompiler les assets
npm run build

# 2. Vider le cache Symfony (si nécessaire)
php bin/console cache:clear

# 3. Redémarrer le serveur
symfony server:stop
symfony server:start
```

### Si les spinners sont toujours là :

```
1. F12 → Elements
2. Sélectionner l'input number
3. Regarder les règles CSS appliquées
4. Vérifier si corrections-urgentes.css apparaît
5. Si non → problème de compilation
6. Si oui mais pas d'effet → problème de spécificité CSS
```

---

## 📋 Résultats Attendus

### ✅ Après Corrections Réussies

1. **Inputs number** : Plus de spinners, design propre
2. **Min-weight** : Valeurs dynamiques selon le produit  
3. **Variables CSS** : Aucune erreur dans la console
4. **Contraste** : Tous les textes parfaitement lisibles
5. **Mobile** : Interface utilisable sur petits écrans

### ❌ Signes de Problème

1. **Spinners encore visibles** → CSS pas appliqué
2. **Quantité fixe à 100** → Template pas mis à jour
3. **Erreurs console** → Variables CSS manquantes
4. **Textes illisibles** → Problème de contraste
5. **Déboîtage mobile** → CSS responsive défaillant

---

## 🎓 Notes pour la Développeuse

### Bonnes Pratiques Apprises

1. **Toujours tester après modification** ✅
2. **Prévoir les cas d'erreur** (variables manquantes) ✅  
3. **Documenter les changements** ✅
4. **Penser mobile dès le début** ✅
5. **Utiliser !important avec parcimonie** ⚠️

### Outils de Développement Utiles

```
F12 → Console : Voir les erreurs JavaScript/CSS
F12 → Elements : Inspecter le HTML/CSS en temps réel
F12 → Network : Vérifier que les fichiers CSS se chargent
F12 → Responsive : Tester sur différentes tailles d'écran
```

---

*💡 **Astuce** : Gardez cette checklist sous la main pour les futurs développements !*
