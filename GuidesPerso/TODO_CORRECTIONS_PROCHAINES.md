# 📝 TODO LIST - CORRECTIONS À FAIRE PROCHAINEMENT

## 🎨 **CORRECTIONS CSS URGENTES**
- [ ] **Boutons impactés** par les modifications récentes
  - Vérifier les styles des boutons sur toutes les pages
  - S'assurer que la cohérence visuelle est maintenue
  - Tester les hover effects et les transitions

- [ ] **Autres éléments CSS** à vérifier
  - Espacements et alignements
  - Couleurs cohérentes avec le design system beige/bordeaux
  - Responsive design sur mobile

## 🔍 **FONCTIONNALITÉ RECHERCHE**
- [ ] **Activer le bouton loupe** dans le header
  - Créer une page de recherche
  - Implémenter la recherche côté serveur (PHP/Symfony)
  - **Option Vue.js** : Créer un composant de recherche dynamique
  - Ajouter l'autocomplete et les suggestions
  - Gérer la recherche par catégorie et par nom de produit

## 💳 **SYSTÈME DE PAIEMENT** (À faire à la fin)
- [ ] **Intégration Stripe** 
  - Installer le bundle Stripe pour Symfony
  - Configurer les clés API (test et production)
  - Créer les contrôleurs de paiement
  - Intégrer Stripe Elements dans le checkout
  - Gérer les webhooks pour les confirmations de paiement
  - Tester les paiements en mode sandbox
  - Sécuriser les transactions
  - **Note** : Niveau de difficulté raisonnable (~6-10h), à faire quand le reste sera parfait

## 🔐 **TOKENS ET SÉCURITÉ**
- [ ] **Audit et explication des tokens** dans l'application
  - **CSRF tokens** : Protection des formulaires contre les attaques (panier, checkout)
  - **Session tokens** : Gestion des utilisateurs connectés (cookies Symfony)
  - **API tokens** : Sécurisation des appels vers tes APIs (/api/pickup-slots)
  - **Tokens futurs Stripe** : Sécurisation des paiements
  - **Action** : Faire un tour d'horizon complet avec explications claires
  - **Objectif** : Comprendre où ils sont, pourquoi ils sont là, et comment ils protègent ton site
  - **Bonus** : Guide simple "Les tokens pour les nuls" avec exemples concrets

## ✅ **VÉRIFICATIONS GLOBALES**
- [ ] **Test complet du workflow** :
  - Navigation produits (corrigée ✅)
  - Ajout au panier
  - Modification quantités
  - Checkout et créneaux
  - Responsive design
  
- [ ] **Performance et optimisation** :
  - Temps de chargement
  - Taille des assets CSS/JS
  - Images optimisées

## 🚀 **AMÉLIORATIONS FUTURES**
- [ ] **UX/UI** :
  - Animations et micro-interactions
  - Loading states
  - Messages de feedback utilisateur
  
- [ ] **Fonctionnalités avancées** :
  - Filtrages par prix/catégorie
  - Tri des produits
  - Pagination si nécessaire

- [ ] **Architecture Vue.js** (à réfléchir) :
  - Migrer vers de vrais fichiers .vue
  - Build plus sophistiqué avec webpack
  - Composants réutilisables

## 💡 **NOTES TECHNIQUES**
- **Créneaux Vue.js** : Système actuel simple et fonctionnel (JavaScript pur + CDN)
- **Garde cette approche** pour l'instant, migration possible plus tard si besoin
- **Stripe** : À prévoir pour les paiements, bien tester en mode sandbox

## 🧹 **NETTOYAGE FINAL POUR PRÉSENTATION**
- [x] **Masquer les fichiers de documentation dans Git** ✅ FAIT
  - Ajout des règles .gitignore pour masquer GuidesPerso/ et *.md
  - Les fichiers restent localement mais ne seront pas visibles sur GitHub
  - Seul README.md principal reste visible si nécessaire

- [ ] **Déplacer/organiser les fichiers de documentation** (optionnel)
  - Déplacer le dossier `GuidesPerso/` vers un dossier externe
  - Ou garder masqué avec .gitignore (solution actuelle)

- [ ] **Réécrire les commentaires en style "naturel"**
  - Supprimer les emojis et formatage très structuré
  - Style plus simple et personnel d'étudiante
  - Commentaires utiles mais pas "trop parfaits"
  - Garder quelques imperfections réalistes

- [ ] **Vérification finale "authenticité"**
  - Relire le code pour s'assurer que ça sonne "étudiant"
  - Ajuster les noms de variables si trop "professionnels"
  - Garder la qualité technique mais avec un style plus personnel

---

## 📋 **CONTEXTE ACTUEL**
✅ Navigation produits corrigée et fonctionnelle  
✅ Système de créneaux Vue.js opérationnel  
✅ Workflow panier/checkout complet  
⚠️ Quelques ajustements CSS nécessaires après les dernières modifications  
⚠️ Bouton recherche à implémenter  

## 🎯 **PRIORITÉ SUIVANTE**
1. Corrections CSS des boutons
2. Implémentation de la recherche (possiblement avec Vue.js)
3. Tests complets de l'ensemble

---

**📅 À traiter lors de la prochaine session de développement !**
