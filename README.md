# 🥩 Boucherie EYSA - Site E-commerce Click & Collect

Site web e-commerce avec système Click & Collect pour la Boucherie EYSA à Saumur.

## 📋 Description

Application web complète permettant aux clients de :
- Consulter le catalogue de produits (viandes, préparations)
- Ajouter des produits au panier avec gestion du poids
- Commander en ligne et choisir un créneau de retrait
- Gérer leur compte et consulter l'historique des commandes

Interface d'administration pour la gestion des produits, stocks et commandes.

## 🛠️ Technologies utilisées

### Backend
- **Symfony 6.4** - Framework PHP
- **Doctrine ORM** - Gestion base de données
- **Twig** - Moteur de templates
- **Symfony Security** - Authentification et autorisation

### Frontend
- **Vue.js 3** - Framework JavaScript réactif
- **Pinia** - Gestion d'état (store)
- **Webpack Encore** - Compilation des assets
- **CSS natif** - Design system personnalisé

### Base de données
- **MySQL/MariaDB** - Base de données relationnelle

### Hébergement
- **O2Switch** - Hébergement mutualisé
- **Git** - Gestion de versions
- **GitHub** - Hébergement du code

## 🚀 Fonctionnalités principales

### Côté client
- ✅ Catalogue produits organisé par catégories (Bœuf, Veau, Agneau, Volaille, Préparations)
- ✅ URLs SEO-friendly avec slugs (`/categories/boeuf`, `/product/bourguignon`)
- ✅ Panier dynamique avec gestion du poids (Vue.js + Pinia)
- ✅ Système Click & Collect avec créneaux horaires
- ✅ Délai minimum de 2h pour les commandes du jour même
- ✅ Filtrage automatique des jours fermés (dimanche/lundi)
- ✅ Gestion du stock en temps réel
- ✅ Authentification et création de compte
- ✅ Historique des commandes

### Côté administration
- ✅ Gestion des produits (CRUD complet)
- ✅ Gestion des catégories
- ✅ Suivi des stocks avec seuil d'alerte
- ✅ Validation des commandes
- ✅ Gestion des créneaux de retrait

### Aspects techniques
- ✅ Architecture MVC avec Symfony
- ✅ Composants Vue réutilisables
- ✅ API REST pour le panier
- ✅ Validation des données côté client et serveur
- ✅ Gestion des erreurs et messages utilisateur
- ✅ Responsive design (mobile, tablette, desktop)
- ✅ Système de toasts pour les notifications

## 📦 Installation

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- Node.js 16+ et npm
- MySQL/MariaDB
- Git

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone https://github.com/HayatEjm/BoucherieEysa.git
cd BoucherieEysa
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Installer les dépendances JavaScript**
```bash
npm install
```

4. **Configurer l'environnement**
```bash
cp .env .env.local
```
Éditer `.env.local` et configurer :
- `DATABASE_URL` - Connexion à la base de données
- `MAILER_DSN` - Configuration email

5. **Créer la base de données**
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

6. **Générer les slugs pour les données existantes**
```bash
php bin/console app:generate-slugs
```

7. **Compiler les assets**
```bash
npm run build
```

8. **Lancer le serveur de développement**
```bash
symfony server:start
```

Le site est accessible sur `http://localhost:8000`

## 🔧 Commandes utiles

### Développement
```bash
# Compiler les assets en mode dev avec watch
npm run watch

# Lancer le serveur Symfony
symfony server:start

# Vider le cache
php bin/console cache:clear
```

### Production
```bash
# Compiler les assets optimisés
npm run build

# Optimiser l'autoloader Composer
composer install --no-dev --optimize-autoloader

# Vider et préchauffer le cache
php bin/console cache:clear --env=prod --no-warmup
php bin/console cache:warmup --env=prod
```

### Base de données
```bash
# Créer une migration
php bin/console make:migration

# Appliquer les migrations
php bin/console doctrine:migrations:migrate

# Générer les slugs
php bin/console app:generate-slugs
```

## 📁 Structure du projet

```
BoucherieEysa/
├── assets/                    # Sources frontend
│   ├── components/            # Composants Vue.js
│   │   ├── CartBadge.vue
│   │   ├── DropdownMenu.vue
│   │   ├── ProductDetail.vue
│   │   └── SearchBar.vue
│   ├── stores/                # Stores Pinia
│   │   └── cartStore.js
│   ├── styles/                # Fichiers CSS
│   └── app.js                 # Point d'entrée JS
├── config/                    # Configuration Symfony
│   ├── packages/
│   ├── routes.yaml
│   └── pickup_slots.yaml      # Config créneaux retrait
├── migrations/                # Migrations Doctrine
├── public/                    # Fichiers publics
│   ├── build/                 # Assets compilés
│   └── images/                # Images produits
├── src/
│   ├── Command/               # Commandes console
│   │   └── GenerateSlugsCommand.php
│   ├── Controller/            # Contrôleurs
│   │   ├── HomeController.php
│   │   ├── CategoryController.php
│   │   ├── ProductController.php
│   │   └── CartController.php
│   ├── Entity/                # Entités Doctrine
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   └── User.php
│   ├── Repository/            # Repositories
│   ├── Service/               # Services métier
│   │   └── PickupSlotService.php
│   └── Twig/                  # Extensions Twig
│       └── AppExtension.php
├── templates/                 # Templates Twig
│   ├── home/
│   ├── category/
│   ├── product/
│   └── partials/
└── deploy.sh                  # Script de déploiement

```

## 🌐 Déploiement

### Sur serveur O2Switch (hébergement mutualisé)

1. **Sur votre machine locale**
```bash
git add .
git commit -m "Description des modifications"
git push origin main
```

2. **Sur le serveur (via SSH)**
```bash
ssh votre-user@vue.o2switch.net
cd ~/git/boucherie-eysa.fr
./deploy.sh
```

Le script `deploy.sh` effectue automatiquement :
- Pull depuis GitHub
- Installation des dépendances npm
- Compilation Webpack en production
- Normalisation des permissions
- Vidage et préchauffage du cache Symfony
- Application des migrations Doctrine

## 🔐 Sécurité

- ✅ Authentification sécurisée avec Symfony Security
- ✅ Validation des données côté serveur
- ✅ Protection CSRF sur les formulaires
- ✅ Hashage des mots de passe (bcrypt)
- ✅ Gestion des rôles (ROLE_USER, ROLE_ADMIN)
- ✅ Validation des créneaux de retrait côté serveur

## 📝 Configuration des créneaux

Les créneaux de retrait sont configurés dans `config/pickup_slots.yaml` :

```yaml
pickup_slots:
    min_preparation_hours: 2    # Délai minimum en heures
    slots:
        - '09:00-09:30'
        - '09:30-10:00'
        # ... autres créneaux
    closed_days: [0, 1]          # Dimanche (0) et Lundi (1)
```

## Debugging

### Erreurs courantes

**Erreur 500 sur les pages produits/catégories**
- Vérifier que les slugs sont générés : `php bin/console app:generate-slugs`
- Vider le cache : `php bin/console cache:clear`

**Menu déroulant affiche "undefined"**
- Vérifier que tous les contrôleurs incluent `slug` dans `formattedCategories`
- Recompiler les assets : `npm run build`

**Problème de permissions en production**
- Relancer le script de déploiement : `./deploy.sh`

## 📚 Documentation technique

### Gestion du panier (Vue.js + Pinia)

Le panier utilise Pinia pour la gestion d'état centralisée :
- Store : `assets/stores/cartStore.js`
- Composants : `CartBadge.vue`, `AddToCartButton.vue`
- API : Routes Symfony dans `CartController.php`

### Système de créneaux

Service dédié : `src/Service/PickupSlotService.php`
- Filtrage des créneaux passés
- Application du délai minimum (2h)
- Gestion des jours fermés (dimanche/lundi)

### URLs SEO avec slugs

- Entités : champs `slug` dans `Product` et `Category`
- Commande : `app:generate-slugs` pour générer automatiquement
- Routes : `{slug}` au lieu de `{id}` dans les contrôleurs

## 👥 Auteur

Hayat E.
GitHub: [@HayatEjm](https://github.com/HayatEjm)

## 📄 Licence
Tout droits réservés 

---

**Dernière mise à jour** : Novembre 2025
