# 🧾 TVA AJOUTÉE AU PANIER - BOUCHERIE EYSA

## ✅ Ce qui a été implémenté

### 📊 **Calculs TVA automatiques**

**Taux appliqué :** 5,5% (taux légal pour les produits alimentaires)

**Formules utilisées :**
```php
// Total HT = Total TTC / (1 + taux/100)
$totalHT = $totalTTC / (1 + (5.5 / 100));

// TVA = Total TTC - Total HT  
$taxAmount = $totalTTC - $totalHT;
```

### 🎨 **Affichage dans le panier**

**Avant :**
```
Articles (2) : 25,30€
Livraison : Retrait gratuit
━━━━━━━━━━━━━━━━━━━
Total : 25,30€
```

**Après :**
```
Articles (2) : 25,30€ TTC
    dont TVA (5,5%) : 1,32€
Livraison : Retrait gratuit
━━━━━━━━━━━━━━━━━━━
Total TTC : 25,30€
```

---

## 🔧 **Fichiers modifiés**

### 1. `src/Entity/Cart.php`
**Nouvelles méthodes ajoutées :**
```php
getTaxRate(): float        // Retourne 5.5
getTaxAmount(): float      // Calcule le montant de TVA
getTotalHT(): float        // Calcule le total HT
getTotalTTC(): float       // Alias de getTotal() (plus explicite)
```

### 2. `templates/cart/index.html.twig`
**Nouvelles variables utilisées :**
```twig
{{ cart.totalTTC|number_format(2, ',', ' ') }}    {# Total TTC #}
{{ cart.taxRate }}                                {# Taux TVA (5.5) #}
{{ cart.taxAmount|number_format(2, ',', ' ') }}   {# Montant TVA #}
```

### 3. `assets/styles/cart/cart.css`
**Nouveaux styles :**
```css
.summary-line.tax-info     /* Ligne TVA avec fond beige */
.tax-label                 /* Style du texte "dont TVA" */
.tax-amount                /* Style du montant de TVA */
```

### 4. `src/Controller/CartController.php`
**Variable ajoutée :**
```php
'cart' => $cart,  // Objet cart complet pour accéder aux méthodes TVA
```

---

## 🧪 **Tests à effectuer**

### 1. **Calculs automatiques**
```
✅ Ajouter des produits au panier
✅ Vérifier que la TVA se calcule automatiquement
✅ Vérifier que Total HT + TVA = Total TTC
```

### 2. **Affichage**
```
✅ La ligne TVA apparaît bien
✅ Le taux 5,5% est affiché
✅ Les montants sont à 2 décimales
✅ "TTC" apparaît partout où c'est nécessaire
```

### 3. **Responsive**
```
✅ L'affichage reste propre sur mobile
✅ La ligne TVA ne déborde pas
```

---

## 💡 **Exemple concret**

**Panier avec :**
- Côte de bœuf : 15,00€
- Saucisses : 8,50€
- **Total TTC : 23,50€**

**Calculs automatiques :**
- Total HT : 23,50€ ÷ 1,055 = **22,27€**
- TVA : 23,50€ - 22,27€ = **1,23€**
- Vérification : 22,27€ + 1,23€ = 23,50€ ✅

---

## 🎓 **Explications pour toi (développeuse junior)**

### **Pourquoi 5,5% ?**
En France, les produits alimentaires de première nécessité (viande, poisson, légumes...) bénéficient d'un taux de TVA réduit de 5,5% au lieu des 20% habituels.

### **Pourquoi calculer depuis le TTC ?**
En boucherie, on affiche toujours les prix TTC aux clients. Le calcul "à l'envers" (du TTC vers le HT) est la méthode la plus précise.

### **Pourquoi `round(, 2)` ?**
Pour éviter les problèmes d'arrondi (ex: 1,2345678€ devient 1,23€) et respecter les règles comptables.

### **Pourquoi garder l'objet `cart` ?**
Plutôt que de calculer la TVA dans le template (mauvaise pratique), on fait le calcul dans l'entité métier. C'est plus propre et réutilisable.

---

## 🚀 **Prochaines étapes possibles**

1. **Facture PDF** avec détail HT/TVA/TTC
2. **TVA par produit** si des taux différents (boissons 20%, etc.)
3. **Exemption TVA** pour les entreprises avec n° de TVA
4. **Système de remises** avec recalcul automatique

---

*📅 Implémentation TVA terminée le : 1 juillet 2025*  
*👩‍💻 Pour : Développeuse junior DWWM*  
*🎯 Objectif : Transparence fiscale et conformité légale*
