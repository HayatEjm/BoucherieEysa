# Système de Créneaux de Retrait - Boucherie Eysa

## 🎯 Présentation Générale

Ce document présente le **système de créneaux de retrait** développé pour la boucherie Eysa. Cette fonctionnalité permet aux clients de sélectionner un créneau horaire précis pour récupérer leur commande, optimisant ainsi l'organisation de la boucherie et l'expérience client.

## 📋 Fonctionnalités Implémentées

### ✅ **Gestion des Créneaux**
- **Configuration simple** via fichier YAML
- **Horaires personnalisables** (matin/après-midi)
- **Horaires spéciaux** le dimanche (uniquement le matin)
- **Jours fermés** configurables (actuellement le lundi)
- **Limite de commandes** par créneau (actuellement 10)

### ✅ **Interface Client**
- **Composant Vue.js 3** autonome et réactif
- **Sélection intuitive** des créneaux disponibles
- **Codes couleurs** clairs :
  - 🟢 **Vert** : Disponible
  - 🟡 **Orange** : Places limitées (>80% occupé)
  - ⚫ **Gris** : Complet
- **Informations temps réel** du nombre de places restantes

### ✅ **API REST Symfony**
- **Endpoints sécurisés** pour récupérer les créneaux
- **Gestion des erreurs** complète
- **Format JSON** standardisé
- **Filtrage par période** (par défaut 7 jours)

## 🏗️ Architecture Technique

### **Backend Symfony**

#### **1. Entité Order (Base de Données)**
```php
// Nouveaux champs ajoutés à l'entité Order
private ?\DateTime $pickupDate = null;           // Date de retrait
private ?string $pickupTimeSlot = null;          // Créneau (matin/apres-midi)
```

#### **2. Configuration des Créneaux**
Fichier : `config/pickup_slots.yaml`
```yaml
pickup_slots:
    max_orders_per_slot: 10    # Limite par créneau
    days_ahead: 7              # Nombre de jours à afficher
    closed_days: [1]           # Lundi fermé (0=dimanche, 1=lundi...)
    time_slots:
        matin: "09:00-12:30"
        apres-midi: "15:30-19:15"
    special_hours:
        - "10:00-13:00"         # Dimanche matin uniquement
```

#### **3. Service PickupSlotService**
- **Logique métier** centralisée
- **Calcul des disponibilités** en temps réel
- **Gestion des horaires spéciaux**
- **Statuts automatiques** (available/limited/full)

#### **4. API REST (/api/pickup-slots)**
- `GET /api/pickup-slots` : Liste des créneaux disponibles
- `GET /api/pickup-slots/{date}` : Créneaux pour une date précise
- `POST /api/pickup-slots/check` : Vérification d'un créneau

### **Frontend Vue.js 3**

#### **Composant Autonome**
- **Chargement via CDN** (pas de compilation complexe)
- **Architecture modulaire** facilement maintenable
- **Gestion d'état réactive** avec Vue 3 Composition API

#### **Fonctionnalités du Composant**
- **Chargement asynchrone** des créneaux
- **États de chargement/erreur** gérés
- **Sélection interactive** avec feedback visuel
- **Validation côté client** avant soumission
- **Intégration transparente** avec les formulaires Symfony

## 🎨 Design & UX

### **Cohérence Visuelle**
- **Variables CSS** du design system existant
- **Couleurs harmonisées** avec la charte graphique beige/bordeaux
- **Responsive design** pour mobile et desktop
- **Animations fluides** pour l'expérience utilisateur

### **Accessibilité**
- **Navigation clavier** complète
- **Contrastes** respectés pour la lisibilité
- **Messages d'erreur** clairs et explicites
- **États visuels** distincts pour chaque statut

## 🔄 Workflow Utilisateur

### **1. Ajout au Panier**
Le client ajoute des produits dans son panier normalement.

### **2. Page Checkout**
- **Résumé de commande** avec détails et totaux
- **Sélection du créneau** via interface intuitive
- **Informations client** (nom, téléphone, email optionnel)

### **3. Sélection du Créneau**
- **Affichage automatique** des 7 prochains jours disponibles
- **Exclusion des jours fermés** (lundi)
- **Indication visuelle** du nombre de places restantes
- **Impossibilité** de sélectionner un créneau complet

### **4. Validation**
- **Vérification** que tous les champs sont remplis
- **Sauvegarde** de la commande avec le créneau choisi
- **Confirmation** et redirection

## 📱 Pages Implémentées

### **Page Panier** (`/panier`)
- Bouton "Finaliser ma commande" redirige vers checkout

### **Page Checkout** (`/panier/checkout`)
- **Résumé complet** de la commande
- **Composant créneaux** intégré
- **Formulaire client** avec validation
- **Design responsive** et professionnel

## 🛠️ Installation & Configuration

### **Prérequis**
- Symfony 6.4+
- Vue.js 3 (chargé via CDN)
- Base de données configurée

### **Configuration**
1. **Migration** automatique lors du déploiement
2. **Fichier YAML** de configuration à adapter selon les besoins
3. **Assets** à compiler : `npm run build`

## 🔧 Maintenance & Évolutions

### **Facilité de Maintenance**
- **Code commenté** et documenté
- **Architecture modulaire** facile à comprendre
- **Séparation claire** des responsabilités
- **Variables CSS** centralisées

### **Évolutions Possibles**
- **Créneaux de 30 minutes** au lieu de demi-journées
- **Notifications email** de confirmation
- **Système de réservation** en avance
- **Intégration calendrier** pour l'administrateur

## 📊 Points Forts du Système

### **🎯 Pour le Client**
- **Interface intuitive** et moderne
- **Information en temps réel** des disponibilités
- **Pas d'attente** en magasin
- **Planification facilitée**

### **🏪 Pour la Boucherie**
- **Organisation optimisée** des retraits
- **Réduction des pics d'affluence**
- **Prévisibilité** du planning
- **Gestion automatisée** des créneaux

### **👩‍💻 Pour la Développeuse**
- **Code propre** et maintenable
- **Documentation complète**
- **Architecture évolutive**
- **Technologies modernes** et pérennes

## 🎓 Présentation Jury/Client

### **Démonstration**
1. **Navigation** sur le site
2. **Ajout produits** au panier
3. **Sélection créneau** en direct
4. **Simulation** de différents états (complet, limité)
5. **Validation** du workflow complet

### **Points Techniques à Souligner**
- **API REST** bien structurée
- **Composant Vue.js** autonome
- **Design system** cohérent
- **Gestion d'erreurs** robuste
- **Code maintenable** pour une débutante

---

## 🏆 Conclusion

Ce système de créneaux de retrait représente une **solution complète et professionnelle** qui :

- ✅ **Répond aux besoins métier** de la boucherie
- ✅ **Offre une UX moderne** aux clients
- ✅ **Utilise des technologies appropriées**
- ✅ **Reste facilement maintenable**
- ✅ **Permet des évolutions futures**

Le projet démontre une **maîtrise technique solide** avec une approche pragmatique, parfaitement adaptée au contexte d'une développeuse en formation.
