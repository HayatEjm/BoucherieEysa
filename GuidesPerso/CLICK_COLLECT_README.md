# 🛒 CLICK & COLLECT - Documentation

## 📝 Vue d'ensemble

Le module Click & Collect permet aux clients de la Boucherie Eysa de commander en ligne et de récupérer leurs produits directement en magasin. Cette solution moderne améliore l'expérience client et optimise l'organisation du magasin.

## 🎯 Fonctionnalités implémentées

### ✅ Composant réutilisable
- **Fichier** : `templates/partials/click_collect_steps.html.twig`
- **Style** : `assets/styles/partials/click_collect.css`
- **Usage** : Section 3 étapes (Commander → Préparer → Récupérer)
- **Réutilisable** : Peut être inclus sur n'importe quelle page

### ✅ Page dédiée complète
- **URL** : `/click-collect`
- **Contrôleur** : `src/Controller/ClickCollectController.php`
- **Template** : `templates/click_collect/index.html.twig`
- **Sections** :
  - Hero avec présentation du service
  - Étapes détaillées (réutilise le composant)
  - Avantages avec icônes
  - Informations pratiques (horaires, adresse)
  - FAQ avec accordéon
  - Call-to-action final

### ✅ Intégration navigation
- **Lien menu** : "CLICK & COLLECT" dans `templates/partials/header.html.twig`
- **Route** : `app_click_collect`

## 🏗️ Structure des fichiers

```
├── src/Controller/
│   └── ClickCollectController.php          # Contrôleur de la page
├── templates/
│   ├── click_collect/
│   │   └── index.html.twig                 # Template page complète
│   └── partials/
│       ├── click_collect_steps.html.twig   # Composant réutilisable
│       └── header.html.twig                # Menu avec lien C&C
└── assets/styles/partials/
    └── click_collect.css                   # Tous les styles
```

## 🎨 Design et UX

### Principes appliqués
- **Mobile First** : Responsive sur tous les écrans
- **Accessibilité** : ARIA labels, contraste suffisant
- **Performance** : CSS optimisé, animations fluides
- **Cohérence** : Utilise Bootstrap et les couleurs de la marque

### Palette de couleurs
- **Primaire** : `#007bff` (bleu Bootstrap)
- **Succès** : `#28a745` (vert)
- **Info** : `#17a2b8` (bleu-cyan)
- **Texte** : `#2c3e50` (gris foncé)
- **Fond** : `#f8f9fa` (gris très clair)

## 🛠️ Comment utiliser

### 1. Afficher le composant sur une page
```twig
{# Dans n'importe quel template #}
{% include 'partials/click_collect_steps.html.twig' %}
```

### 2. Personnaliser les styles
```css
/* Dans votre CSS personnalisé */
.click-collect-section {
    /* Vos modifications */
}
```

### 3. Ajouter de nouvelles sections
```twig
{# Dans templates/click_collect/index.html.twig #}
<section class="ma-nouvelle-section py-5">
    <div class="container">
        <!-- Votre contenu -->
    </div>
</section>
```

## 📱 Responsive Design

### Points de rupture
- **Desktop** : `> 768px` - Grille 3 colonnes
- **Tablet** : `≤ 768px` - Grille 2 colonnes
- **Mobile** : `≤ 480px` - Grille 1 colonne

### Tests recommandés
- iPhone SE (375px)
- iPad (768px)
- Desktop (1200px+)

## 🔧 Maintenance et évolutions

### Pour ajouter une FAQ
```twig
{# Dans templates/click_collect/index.html.twig #}
<div class="accordion-item">
    <h3 class="accordion-header">
        <button class="accordion-button collapsed" type="button" 
                data-bs-toggle="collapse" data-bs-target="#faqN">
            <i class="fas fa-question-circle me-2 text-primary"></i>
            Votre nouvelle question ?
        </button>
    </h3>
    <div id="faqN" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Votre réponse ici...
        </div>
    </div>
</div>
```

### Pour modifier les horaires
```twig
{# Dans templates/click_collect/index.html.twig, section practical-info #}
<li><strong>Nouveau jour :</strong> Nouveaux horaires</li>
```

### Pour ajouter un avantage
```twig
{# Dans templates/click_collect/index.html.twig, section advantages #}
<div class="col-md-4">
    <div class="advantage-card text-center h-100 p-4 bg-white rounded shadow-sm">
        <div class="advantage-icon mb-3">
            <i class="fas fa-votre-icone text-primary" style="font-size: 3rem;"></i>
        </div>
        <h4 class="mb-3">Votre titre</h4>
        <p class="text-muted">Votre description...</p>
    </div>
</div>
```

## 🎯 Prochaines étapes recommandées

### Court terme
- [ ] Ajouter la gestion des créneaux de retrait
- [ ] Intégrer le système de notifications (SMS/Email)
- [ ] Ajouter un calculateur de temps de préparation

### Moyen terme
- [ ] Connecter avec le système de caisse
- [ ] Ajouter la géolocalisation pour l'itinéraire
- [ ] Créer un tableau de bord boucher

### Long terme
- [ ] Application mobile dédiée
- [ ] Système de fidélité intégré
- [ ] Analytics et rapports avancés

## 🐛 Débogage

### Problèmes courants

#### Styles ne s'appliquent pas
```bash
# Recompiler les assets
npm run dev
# ou en mode watch
npm run watch
```

#### Route non trouvée
```bash
# Vérifier les routes
php bin/console debug:router | grep click
```

#### Template non trouvé
- Vérifier le chemin : `templates/click_collect/index.html.twig`
- Vérifier la méthode du contrôleur : `return $this->render('click_collect/index.html.twig')`

## 📚 Ressources

### Documentation utilisée
- [Symfony Routing](https://symfony.com/doc/current/routing.html)
- [Twig Templates](https://twig.symfony.com/doc/3.x/)
- [Bootstrap Components](https://getbootstrap.com/docs/5.1/components/)
- [Font Awesome Icons](https://fontawesome.com/icons)

### Code commenté
Tous les fichiers sont largement commentés pour faciliter la compréhension et la maintenance. Les commentaires expliquent :
- Le rôle de chaque section
- Les choix de design
- Les points d'amélioration possibles
- Les bonnes pratiques appliquées

---

*Développé avec ❤️ pour la Boucherie Eysa*
