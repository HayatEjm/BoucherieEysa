# AUDIT CSS COMPLET - BOUCHERIE EYSA
**Date**: 8 novembre 2025  
**Statut**: Rapport complet avec plan d'action prioritaire

---

## RESUME EXECUTIF

### Problemes identifies
1. **Responsive mobile defaillant**: Images mal cadrees, texte debordant, espacements incoherents
2. **Incoherence formulaires**: Styles variables entre Login/Inscription/Contact/Checkout
3. **Emails non responsive**: Charte admin (rouge) au lieu de bordeaux/beige, aucun media query
4. **Architecture CSS**: Melange max-width/min-width, breakpoints multiples (480/768/1200)
5. **Charte graphique partielle**: Design-system bien defini mais pas utilise partout

### Impact utilisateur
- **Mobile** (35-50% du trafic): Experience degradee, difficulte lecture/navigation
- **Conversion**: Formulaires checkout/contact peu ergonomiques sur mobile
- **Branding**: Emails generiques ne refletent pas l'identite visuelle

---

## 1. PROBLEMES RESPONSIVE MOBILE

### 1.1 Images mal cadrees

**Fichiers concernes:**
- `home.css` - Hero image
- `category_products.css` - Images produits
- `partials/click_collect.css` - Visuels avantages

**Problemes specifiques:**
```css
/* Actuel - Probleme */
.hero-image img {
  height: 400px; /* Hauteur fixe force rognage */
  object-fit: cover;
}

.product-image img {
  max-width: 180px; /* Taille desktop, trop petite mobile */
}
```

**Impact mobile:**
- Hero: image coupee sur smartphones
- Produits: images minuscules (<120px lisibles)
- Ratios non preserves: viandes deformees

**Solution:**
```css
/* Mobile-first responsive */
.hero-image img {
  width: 100%;
  height: auto; /* Preserve ratio */
  max-height: 300px; /* Limite mobile */
  object-fit: cover;
}

@media (min-width: 768px) {
  .hero-image img {
    max-height: 400px; /* Desktop */
  }
}

.product-image img {
  width: 100%; /* Pleine largeur container */
  height: auto;
  aspect-ratio: 4/3; /* Ratio constant */
  object-fit: cover;
}
```

### 1.2 Texte debordant

**Fichiers concernes:**
- `auth/auth.css` - Titres formulaires
- `checkout/checkout.css` - Recapitulatif commande
- `home.css` - Hero title

**Problemes specifiques:**
```css
/* Actuel - Debordement */
.hero-title {
  font-size: 3.5rem; /* 56px trop gros mobile */
}

.auth-header h1 {
  font-size: 1.8rem; /* Pas de fallback mobile */
}
```

**Impact mobile:**
- Titres coupes horizontalement
- Scroll horizontal force
- Lisibilite nulle <375px

**Solution:**
```css
/* Base mobile */
.hero-title {
  font-size: clamp(1.75rem, 5vw, 3.5rem); /* Fluide 28-56px */
  word-wrap: break-word;
}

.auth-header h1 {
  font-size: clamp(1.25rem, 4vw, 1.8rem);
}

/* Containers */
.container,
.auth-card,
.products-container {
  max-width: 100%;
  padding: 1rem; /* Mobile */
  box-sizing: border-box;
}

@media (min-width: 768px) {
  .container { padding: 2rem; }
}
```

### 1.3 Espacements incoherents

**Problemes:**
- `padding: 2.5rem` sur `.auth-card` → trop gros mobile
- `gap: 3rem` sur grilles → force scroll
- Marges fixes au lieu de relatives

**Solution systematique:**
```css
/* Variables responsive */
:root {
  --spacing-page: 1rem;
  --spacing-card: 1rem;
  --spacing-grid-gap: 1rem;
}

@media (min-width: 768px) {
  :root {
    --spacing-page: 2rem;
    --spacing-card: 2rem;
    --spacing-grid-gap: 2rem;
  }
}

/* Utilisation */
.auth-card {
  padding: var(--spacing-card);
}

.products-grid {
  gap: var(--spacing-grid-gap);
}
```

---

## 2. INCOHERENCE FORMULAIRES

### 2.1 Styles divergents

**Fichiers:**
- `auth/auth.css` - Login/Inscription
- `contact/contact.css` - Contact
- `checkout/checkout.css` - Commande

**Problemes:**
```css
/* auth.css */
.form-control {
  padding: 0.75rem 1rem;
  border: 2px solid #ddd;
  border-radius: 8px;
}

/* contact.css */
.contact-input {
  padding: 12px; /* Unites differentes */
  border: 1px solid #ccc; /* Epaisseur differente */
  border-radius: 6px; /* Rayon different */
}

/* checkout.css */
input[type="text"] {
  padding: 10px 15px; /* Encore different */
  border: 1px solid #e5e5e5;
}
```

**Impact:**
- Experience utilisateur incoherente
- Apparence non professionnelle
- Maintenance difficile

**Solution: Composants partages**

Creer `assets/styles/components/forms.css`:
```css
/* === FORMULAIRES - COMPOSANTS PARTAGES === */

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  color: var(--text-primary);
  font-weight: var(--font-weight-medium);
  font-size: var(--font-size-sm);
}

.form-control,
.form-input,
.form-textarea,
.form-select {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 2px solid var(--border-color);
  border-radius: var(--border-radius-md);
  font-size: var(--font-size-base);
  font-family: var(--font-family-primary);
  transition: all 0.3s ease;
  background: var(--white);
  color: var(--text-primary);
}

.form-control:focus,
.form-input:focus,
.form-textarea:focus,
.form-select:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(139, 21, 56, 0.1);
}

.form-control.is-invalid {
  border-color: var(--danger-color);
}

.form-help {
  display: block;
  margin-top: 0.25rem;
  font-size: var(--font-size-sm);
  color: var(--text-secondary);
}

.form-error {
  display: block;
  margin-top: 0.25rem;
  font-size: var(--font-size-sm);
  color: var(--danger-color);
}

/* Responsive */
@media (max-width: 768px) {
  .form-control,
  .form-input,
  .form-textarea {
    font-size: 16px; /* Evite zoom iOS */
  }
}
```

Importer dans tous les fichiers de formulaires:
```css
/* Dans auth.css, contact.css, checkout.css */
@import '../components/forms.css';

/* Puis supprimer les definitions redondantes */
```

### 2.2 Boutons incoherents

**Problemes:**
```css
/* auth.css */
.btn-primary {
  background: linear-gradient(135deg, #8B1538, #6B1028);
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
}

/* checkout.css */
.submit-btn {
  background: #8b4513; /* Couleur differente ! */
  padding: 12px 24px; /* Unites differentes */
  border-radius: 6px;
}
```

**Solution: Systeme boutons unifie**

Creer `assets/styles/components/buttons.css`:
```css
/* === BOUTONS - SYSTEME COMPLET === */

.btn {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: var(--border-radius-md);
  font-size: var(--font-size-base);
  font-weight: var(--font-weight-semibold);
  font-family: var(--font-family-primary);
  text-decoration: none;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  white-space: nowrap;
}

/* Variantes */
.btn-primary {
  background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
  color: var(--white);
  box-shadow: 0 2px 8px rgba(139, 21, 56, 0.2);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(139, 21, 56, 0.3);
  color: var(--white);
}

.btn-secondary {
  background: var(--beige-warm);
  color: var(--text-primary);
  border: 2px solid var(--border-color);
}

.btn-secondary:hover {
  background: var(--beige-medium);
}

.btn-outline {
  background: transparent;
  color: var(--primary-color);
  border: 2px solid var(--primary-color);
}

.btn-outline:hover {
  background: var(--primary-color);
  color: var(--white);
}

/* Tailles */
.btn-sm {
  padding: 0.5rem 1rem;
  font-size: var(--font-size-sm);
}

.btn-lg {
  padding: 1rem 2rem;
  font-size: var(--font-size-lg);
}

.btn-block {
  display: block;
  width: 100%;
}

/* Etats */
.btn:disabled,
.btn.disabled {
  opacity: 0.6;
  cursor: not-allowed;
  pointer-events: none;
}

/* Responsive */
@media (max-width: 768px) {
  .btn {
    padding: 0.625rem 1.25rem;
  }
  
  .btn-block-mobile {
    display: block;
    width: 100%;
  }
}
```

---

## 3. EMAILS NON RESPONSIVE

### 3.1 Probleme charte graphique

**Fichiers concernes:**
- `templates/emails/order_confirmation.html.twig`
- `templates/emails/new_order_admin.html.twig`
- `templates/emails/password_reset.html.twig`

**Problemes actuels:**

1. **Email admin** utilise charte rouge/grise (style admin) au lieu de bordeaux/beige:
```html
<!-- Actuel -->
<style>
  .header {
    background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%); /* Rouge */
  }
</style>
```

2. **Aucun media query** = illisible mobile:
```html
<!-- Pas de @media, tableaux fixes -->
<table style="width: 650px;">
```

3. **Incohérence visuelle** avec le site

### 3.2 Solution complete

**Template unifie `templates/emails/_base_email.html.twig`:**
```twig
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{% block email_title %}{% endblock %} - Boucherie Eysa</title>
    <style>
        /* Reset email clients */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #faf7f2; /* Beige light */
            color: #2c2c2c;
            -webkit-font-smoothing: antialiased;
        }
        
        /* Container principal */
        .email-wrapper {
            width: 100%;
            background-color: #faf7f2;
            padding: 20px 0;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(139, 21, 56, 0.1);
        }
        
        /* Header bordeaux */
        .email-header {
            background: linear-gradient(135deg, #8b1538 0%, #6b1028 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        
        .email-logo {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .email-header p {
            margin: 8px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        
        /* Contenu */
        .email-content {
            padding: 30px 20px;
        }
        
        /* Encadre beige */
        .info-box {
            background-color: #f5f1e8; /* Beige warm */
            border-left: 4px solid #8b1538;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        
        /* Tableaux responsive */
        .email-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .email-table th {
            background-color: #8b1538;
            color: #ffffff;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }
        
        .email-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #e8ddd0;
            font-size: 14px;
        }
        
        .email-table tr:nth-child(even) {
            background-color: #fbf9f5;
        }
        
        /* Boutons */
        .email-button {
            display: inline-block;
            background: linear-gradient(135deg, #8b1538, #6b1028);
            color: #ffffff !important;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(139, 21, 56, 0.2);
        }
        
        /* Footer */
        .email-footer {
            background-color: #2c2c2c;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 13px;
        }
        
        .email-footer a {
            color: #f5f1e8;
            text-decoration: none;
        }
        
        /* RESPONSIVE */
        @media only screen and (max-width: 600px) {
            .email-container {
                border-radius: 0;
            }
            
            .email-content {
                padding: 20px 15px;
            }
            
            .email-header {
                padding: 20px 15px;
            }
            
            .email-header h1 {
                font-size: 20px;
            }
            
            .email-table th,
            .email-table td {
                padding: 8px 4px;
                font-size: 12px;
            }
            
            .email-button {
                display: block;
                width: 100%;
                padding: 12px;
                font-size: 14px;
            }
            
            .info-box {
                padding: 15px;
            }
        }
        
        @media only screen and (max-width: 480px) {
            /* Cacher colonnes non essentielles */
            .hide-mobile {
                display: none !important;
            }
            
            .email-table {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <div class="email-logo">🥩</div>
                <h1>{% block header_title %}Boucherie Eysa{% endblock %}</h1>
                <p>{% block header_subtitle %}{% endblock %}</p>
            </div>
            
            <!-- Contenu -->
            <div class="email-content">
                {% block content %}{% endblock %}
            </div>
            
            <!-- Footer -->
            <div class="email-footer">
                <p><strong>Boucherie Eysa</strong> - Tradition et qualité</p>
                <p>Adresse | Tél: XX XX XX XX XX | <a href="mailto:contact@boucherie-eysa.fr">contact@boucherie-eysa.fr</a></p>
                <p style="margin-top: 10px; opacity: 0.8;">Une question ? Contactez-nous !</p>
            </div>
        </div>
    </div>
</body>
</html>
```

**Utilisation dans les templates:**
```twig
{# templates/emails/order_confirmation.html.twig #}
{% extends 'emails/_base_email.html.twig' %}

{% block email_title %}Confirmation de commande{% endblock %}
{% block header_title %}Commande Confirmée !{% endblock %}
{% block header_subtitle %}Merci pour votre confiance{% endblock %}

{% block content %}
    <p>Bonjour <strong>{{ order.customerName }}</strong>,</p>
    
    <div class="info-box">
        <h3 style="margin: 0 0 10px; color: #8b1538;">📋 Détails de votre commande</h3>
        <p><strong>Numéro :</strong> #{{ order.id }}</p>
        <p><strong>Total :</strong> {{ (order.totalTtcCents / 100)|number_format(2, ',', ' ') }} €</p>
    </div>
    
    <table class="email-table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th class="hide-mobile">Prix unit.</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            {% for item in order.orderItems %}
            <tr>
                <td><strong>{{ item.product.name }}</strong></td>
                <td>{{ item.quantity }} {{ item.product.unit }}</td>
                <td class="hide-mobile">{{ (item.unitPriceHtCents / 100)|number_format(2, ',', ' ') }} €</td>
                <td><strong>{{ (item.totalTtcCents / 100)|number_format(2, ',', ' ') }} €</strong></td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
{% endblock %}
```

---

## 4. ARCHITECTURE CSS A NORMALISER

### 4.1 Probleme breakpoints multiples

**Etat actuel:**
```css
/* Melange dans differents fichiers */
@media (max-width: 480px) { /* Mobile */
@media (max-width: 768px) { /* Tablet */
@media (max-width: 1200px) { /* Desktop */
@media (min-width: 768px) { /* Mobile-first */
@media screen and (max-width: 768px) { /* Variante */
```

**Problemes:**
- Logique desktop-first (max-width) majoritaire
- Breakpoints non standardises
- Conflits de specificite

**Solution: Mobile-first systematique**

Dans `design-system.css`:
```css
/* === BREAKPOINTS STANDARDISES === */
:root {
  --breakpoint-sm: 480px;   /* Petit mobile */
  --breakpoint-md: 768px;   /* Tablette */
  --breakpoint-lg: 1024px;  /* Desktop */
  --breakpoint-xl: 1280px;  /* Large desktop */
}

/* Usage mobile-first UNIQUEMENT */
/* Base = mobile par defaut */
.container {
  padding: 1rem;
  max-width: 100%;
}

/* Tablette et + */
@media (min-width: 768px) {
  .container {
    padding: 2rem;
    max-width: 768px;
  }
}

/* Desktop et + */
@media (min-width: 1024px) {
  .container {
    max-width: 1024px;
  }
}

/* Large desktop */
@media (min-width: 1280px) {
  .container {
    max-width: 1200px;
  }
}
```

**Regle stricte:**
- Jamais de `max-width` dans `@media`
- Toujours `min-width` (mobile-first)
- 4 breakpoints max

### 4.2 Utilisation incomplete design-system

**Variables definies mais ignorees:**
```css
/* design-system.css definit */
:root {
  --primary-color: #8b1538;
  --border-radius-md: 8px;
  --spacing-xl: 2rem;
}

/* Mais fichiers utilisent valeurs en dur */
/* home.css */
.hero-title {
  color: #8b1538; /* Au lieu de var(--primary-color) */
}

/* auth.css */
.auth-card {
  border-radius: 12px; /* Au lieu de var(--border-radius-lg) */
}
```

**Impact:**
- Impossible de changer la charte globalement
- Maintenance complexe
- Risque incohérences

**Solution: Audit et remplacement systematique**

Script de verification:
```bash
# Trouver toutes les couleurs en dur
grep -r "#8b1538\|#8B1538" assets/styles/ --exclude-dir=node_modules

# Trouver tous les border-radius fixes
grep -r "border-radius: [0-9]" assets/styles/ --exclude-dir=node_modules
```

Puis remplacer systematiquement:
```css
/* Avant */
color: #8b1538;
background: #faf7f2;
border-radius: 8px;
padding: 2rem;

/* Apres */
color: var(--primary-color);
background: var(--beige-light);
border-radius: var(--border-radius-md);
padding: var(--spacing-xl);
```

---

## 5. PLAN D'ACTION PRIORITAIRE

### Phase 1: URGENT (Cette semaine)

#### 1.1 Template email responsive (4h)
1. Creer `templates/emails/_base_email.html.twig` (1h)
2. Migrer `order_confirmation.html.twig` (1h)
3. Migrer `new_order_admin.html.twig` (charte bordeaux) (1h)
4. Migrer `password_reset.html.twig` (30min)
5. Tests mobiles (Gmail/Outlook/Apple Mail) (30min)

**Fichiers a modifier:**
- `templates/emails/_base_email.html.twig` (nouveau)
- `templates/emails/order_confirmation.html.twig`
- `templates/emails/new_order_admin.html.twig`
- `templates/emails/password_reset.html.twig`

#### 1.2 Composants formulaires unifies (3h)
1. Creer `assets/styles/components/forms.css` (1h)
2. Creer `assets/styles/components/buttons.css` (1h)
3. Importer dans auth/contact/checkout (30min)
4. Tester tous les formulaires (30min)

**Fichiers a modifier:**
- `assets/styles/components/forms.css` (nouveau)
- `assets/styles/components/buttons.css` (nouveau)
- `assets/styles/auth/auth.css` (import + suppression redondances)
- `assets/styles/contact/contact.css` (idem)
- `assets/styles/checkout/checkout.css` (idem)
- `assets/app.js` (ajouter imports)

#### 1.3 Corrections mobile critiques (3h)
1. Images responsive (hero + produits) (1h)
2. Textes fluides (clamp) (1h)
3. Espacements adaptatifs (1h)

**Fichiers a modifier:**
- `assets/styles/home.css`
- `assets/styles/category/category_products.css`
- `assets/styles/product/product_detail.css`
- `assets/styles/partials/click_collect.css`

### Phase 2: IMPORTANT (Semaine suivante)

#### 2.1 Migration mobile-first complete (6h)
1. Standardiser breakpoints (2h)
2. Convertir tous les `@media (max-width)` en `min-width` (3h)
3. Tests regression desktop/tablet/mobile (1h)

**Fichiers concernes:** Tous les `.css` avec media queries

#### 2.2 Variables design-system partout (4h)
1. Audit des valeurs en dur (1h)
2. Remplacement systematique (2h)
3. Verification visuelle (1h)

### Phase 3: AMELIORATIONS (Optionnel)

#### 3.1 Grilles responsive avancees
- CSS Grid/Flexbox adaptatifs
- Container queries (si support navigateur)

#### 3.2 Animations et transitions
- Coherence timing
- Respect prefers-reduced-motion

#### 3.3 Mode sombre (optionnel)
- Variables CSS pour theming
- Detection prefers-color-scheme

---

## 6. CHECKLIST DE VALIDATION

### Avant chaque merge

- [ ] Tester sur 3 tailles: Mobile (375px), Tablet (768px), Desktop (1280px)
- [ ] Verifier emails sur Gmail/Outlook/Apple Mail (mobile + desktop)
- [ ] Valider avec Lighthouse (Performance/Accessibility)
- [ ] Pas de scroll horizontal force
- [ ] Texte lisible sans zoom
- [ ] Boutons/liens cliquables (44px min)
- [ ] Formulaires utilisables sur mobile (inputs 16px min iOS)

### Tests navigateurs

- [ ] Chrome (desktop + mobile)
- [ ] Firefox
- [ ] Safari (iOS + macOS)
- [ ] Edge

---

## 7. COMMANDES UTILES

### Build et deploiement
```bash
# Local: build dev avec watch
npm run dev

# Production: build optimise
npm run build

# Deploiement serveur
./deploy.sh
```

### Tests responsive
```bash
# Chrome DevTools: Toggle device toolbar (Ctrl+Shift+M)
# Tailles a tester:
# - iPhone SE (375x667)
# - iPhone 12 Pro (390x844)
# - iPad (768x1024)
# - Desktop (1280x720)
```

### Validation emails
```bash
# Test local email (si Mailtrap/Mailhog configure)
php bin/console app:test-mail votre@email.com

# Verifier rendu:
# https://www.emailonacid.com/ (payant)
# https://litmus.com/ (payant)
# Ou Gmail/Outlook directement
```

---

## 8. RESSOURCES ET DOCUMENTATION

### Guides CSS responsive
- [MDN: Responsive Design](https://developer.mozilla.org/fr/docs/Learn/CSS/CSS_layout/Responsive_Design)
- [CSS Tricks: Complete Guide to Flexbox](https://css-tricks.com/snippets/css/a-guide-to-flexbox/)
- [CSS Tricks: Complete Guide to Grid](https://css-tricks.com/snippets/css/complete-guide-grid/)

### Email HTML
- [Can I Email](https://www.caniemail.com/) - Support CSS dans clients mail
- [Email on Acid](https://www.emailonacid.com/blog/) - Bonnes pratiques
- [Litmus Email Checklist](https://litmus.com/community/checklists/email-checklist)

### Outils
- [Responsive Breakpoints Generator](https://www.responsivebreakpoints.com/)
- [Clamp Calculator](https://royalfig.github.io/fluid-typography-calculator/)
- [Contrast Checker](https://webaim.org/resources/contrastchecker/)

---

## 9. NOTES FINALES

### Points d'attention
1. **iOS Safari**: Inputs <16px forcent zoom → toujours 16px min
2. **Outlook**: Support CSS limite → tableaux pour layout emails
3. **Gradients**: Preferer variables CSS pour maintenance
4. **Images**: WebP avec fallback JPEG/PNG pour compatibilite

### Maintenance continue
- Documenter chaque nouveau composant
- Tester mobile AVANT desktop
- Utiliser variables design-system
- Respecter breakpoints standardises

---

**Prochaine etape:** Commencer Phase 1.1 (Template email responsive)  
**Temps estime total Phase 1:** 10 heures  
**Impact immediat:** Emails professionnels + formulaires coherents + mobile utilisable
