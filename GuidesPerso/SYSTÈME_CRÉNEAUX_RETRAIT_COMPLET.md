# 📅 Système de Créneaux de Retrait - Boucherie Eysa

## 🎯 Objectif du Système

Le système de créneaux de retrait permet aux clients de sélectionner un créneau horaire pour récupérer leur commande à la boucherie. Cette fonctionnalité améliore l'organisation du magasin et l'expérience client en évitant les files d'attente.

## 🏗️ Architecture Technique

### Vue d'ensemble
Le système est composé de plusieurs couches qui travaillent ensemble :

1. **Base de données** : Stockage des commandes avec date et créneau
2. **API Symfony** : Calcul des créneaux disponibles en temps réel
3. **Composant Vue.js** : Interface utilisateur interactive
4. **Intégration** : Page de checkout complète

### Structure des fichiers créés

```
📁 Système Créneaux de Retrait
├── 🗄️ Base de données
│   ├── src/Entity/Order.php (champs pickup ajoutés)
│   └── migrations/ (création des colonnes)
├── 
├── ⚙️ Backend Symfony
│   ├── config/pickup_slots.yaml (configuration)
│   ├── src/Service/PickupSlotService.php (logique métier)
│   ├── src/Controller/Api/PickupSlotController.php (API)
│   └── src/Repository/OrderRepository.php (requêtes)
├── 
├── 🎨 Frontend
│   ├── assets/js/pickupSlots.js (composant Vue 3)
│   ├── assets/styles/partials/pickup-slots.css
│   ├── assets/styles/partials/checkout.css
│   └── templates/cart/checkout.html.twig
└── 
└── 📋 Workflow
    └── src/Controller/CartController.php (gestion commandes)
```

## 📊 Fonctionnalités Implémentées

### ✅ Configuration Flexible
- **Créneaux configurables** : Matin (8h-12h) et Après-midi (14h-18h)
- **Jours fermés** : Dimanche après-midi automatiquement exclu
- **Limite par créneau** : Maximum 10 commandes par créneau (configurable)
- **Période d'affichage** : 7 jours à l'avance (configurable)

### ✅ API REST Complète
- `GET /api/pickup-slots` : Liste des créneaux pour 7 jours
- `GET /api/pickup-slots/{date}` : Créneaux pour une date spécifique
- `POST /api/pickup-slots/check` : Vérifier la disponibilité d'un créneau

### ✅ Interface Utilisateur Moderne
- **Sélection visuelle** : Cartes colorées selon la disponibilité
- **États dynamiques** : 
  - 🟢 Disponible (vert)
  - 🟡 Places limitées (orange)
  - 🔴 Complet (gris)
- **Responsive** : S'adapte à tous les écrans
- **Accessible** : Navigation au clavier, couleurs contrastées

### ✅ Intégration E-commerce
- **Page checkout dédiée** : Résumé commande + sélection créneau
- **Validation temps réel** : Impossible de sélectionner un créneau complet
- **Sauvegarde automatique** : Créneau lié à la commande
- **Workflow complet** : Du panier à la confirmation

## 🔧 Configuration du Système

### Fichier de configuration (`config/pickup_slots.yaml`)

```yaml
pickup_slots:
  # Nombre maximum de commandes par créneau
  max_orders_per_slot: 10
  
  # Nombre de jours affichés à l'avance
  days_ahead: 7
  
  # Jours fermés (0=dimanche, 1=lundi, ..., 6=samedi)
  closed_days: [0] # Fermé le dimanche
  
  # Créneaux horaires disponibles
  time_slots:
    matin: "8h - 12h"
    apres-midi: "14h - 18h"
  
  # Horaires spéciaux (ex: dimanche matin uniquement)
  special_hours:
    - "8h - 12h" # Dimanche matin seulement
```

### Modification facile des paramètres

Pour adapter le système aux besoins de la boucherie :

1. **Changer les horaires** : Modifier `time_slots` dans le YAML
2. **Ajuster la capacité** : Modifier `max_orders_per_slot`
3. **Modifier les jours fermés** : Ajuster `closed_days`
4. **Étendre la période** : Changer `days_ahead`

## 🚀 Comment Utiliser le Système

### Pour le Client (Frontend)

1. **Ajouter des produits au panier**
2. **Cliquer sur "Finaliser ma commande"**
3. **Voir le résumé de la commande**
4. **Sélectionner un créneau disponible**
   - Les créneaux sont colorés selon leur disponibilité
   - Le nombre de places restantes est affiché
5. **Remplir ses informations de contact**
6. **Confirmer la commande**

### Pour le Commerçant (Backend)

1. **Visualiser les commandes** par créneau
2. **Ajuster la configuration** selon l'affluence
3. **Suivre les créneaux** les plus demandés
4. **Adapter les horaires** selon les besoins

## 🔍 Exemples d'Utilisation de l'API

### Récupérer les créneaux disponibles
```bash
GET /api/pickup-slots
```

Réponse :
```json
{
  "success": true,
  "data": {
    "slots": [
      {
        "date": "2025-07-05",
        "day_name": "Samedi",
        "slots": [
          {
            "key": "matin",
            "time": "8h - 12h",
            "available": true,
            "current_orders": 3,
            "max_orders": 10,
            "status": "available"
          },
          {
            "key": "apres-midi",
            "time": "14h - 18h",
            "available": true,
            "current_orders": 8,
            "max_orders": 10,
            "status": "limited"
          }
        ]
      }
    ]
  }
}
```

### Vérifier un créneau spécifique
```bash
POST /api/pickup-slots/check
Content-Type: application/json

{
  "date": "2025-07-05",
  "time_slot": "matin"
}
```

## 🎨 Design System Cohérent

Le système utilise les variables CSS du design system existant :

- **Couleurs** : Beige, bordeaux, noir (cohérent avec la charte)
- **Typographie** : Fonts et tailles harmonisées
- **Espacements** : Variables `--spacing-*` réutilisées
- **Transitions** : Animations fluides et modernes

## 🧪 Tests et Validation

### Tests Fonctionnels Effectués

✅ **API** : Toutes les routes fonctionnent correctement  
✅ **Interface** : Sélection et affichage des créneaux  
✅ **Base de données** : Sauvegarde des créneaux avec les commandes  
✅ **Responsive** : Adaptation mobile et desktop  
✅ **Validation** : Impossible de sélectionner un créneau complet  

### Scénarios Testés

1. **Créneau disponible** → Sélection possible
2. **Créneau limité** → Affichage du nombre de places restantes
3. **Créneau complet** → Bouton désactivé
4. **Jour fermé** → Aucun créneau affiché
5. **Mobile** → Interface adaptée

## 🔮 Évolutions Futures Possibles

### Améliorations Court Terme
- **Notifications email** : Confirmation de créneau par email
- **SMS** : Rappel la veille du retrait
- **Calendrier** : Vue calendrier pour les administrateurs

### Fonctionnalités Avancées
- **Réservation temporaire** : Bloquer un créneau pendant 15 minutes
- **Créneaux spéciaux** : Horaires étendus pour les fêtes
- **Analytics** : Statistiques sur les créneaux les plus demandés
- **Integration externe** : Synchronisation avec un système de caisse

## 👥 Pour le Jury / Client

### Points Forts Techniques

1. **Architecture modulaire** : Chaque composant a une responsabilité claire
2. **Code maintenable** : Documentation complète et structure claire
3. **Scalabilité** : Facilement extensible pour de nouvelles fonctionnalités
4. **Performance** : API optimisée, pas de surcharge
5. **UX moderne** : Interface intuitive et responsive

### Démonstration des Compétences

- **Symfony** : Controllers, Services, Entities, API REST
- **Base de données** : Doctrine ORM, migrations, relations
- **Frontend moderne** : Vue.js 3, CSS moderne, JavaScript ES6+
- **Architecture logicielle** : Séparation des responsabilités
- **Configuration** : YAML, services Symfony
- **Git** : Commits atomiques et documentation

### Valeur Métier

- **Amélioration UX** : Pas d'attente, organisation client
- **Efficacité magasin** : Répartition de la charge, prévisibilité
- **Évolutivité** : Base solide pour futures fonctionnalités
- **Professionnalisme** : Interface moderne et cohérente

## 🎯 Conclusion

Ce système de créneaux de retrait démontre une approche complète du développement web moderne :

- **Backend robuste** avec Symfony
- **Frontend interactif** avec Vue.js  
- **Base de données** bien structurée
- **API REST** documentée
- **Design cohérent** et responsive
- **Code maintenable** et évolutif

Il répond parfaitement aux besoins d'un commerce moderne tout en démontrant les compétences techniques requises pour un développeur web fullstack.

---

*Développé avec ❤️ pour la Boucherie Eysa - Juillet 2025*
