# 📋 DOSSIER PROJET BOUCHERIE EYSA - Version Ajustée

## 0. Préambule — Introduction

J'aime les défis techniques concrets ! Mon entourage proche comprend plusieurs commerçants de proximité qui peinent à digitaliser leurs services sans perdre leur identité artisanale. C'est là que j'ai trouvé l'inspiration qui me permet de vous présenter aujourd'hui le projet **Boucherie Eysa**. Ce projet m'a permis de mettre en application les connaissances et les compétences acquises lors de ma formation DWWM au CNAM. Il s'agit d'un site web transactionnel qui modernise le service d'une boucherie traditionnelle. J'ai choisi de nommer ce projet Boucherie Eysa : Commander, Choisir, Récupérer.

Il existe aujourd'hui un engouement certain autour des solutions de click & collect pour les commerces de proximité. Toutes répondent à un besoin de flexibilité, d'optimisation du temps et de modernisation des parcours d'achat. Boucherie Eysa permet lui aussi de commander ses produits carnés en amont, de choisir un créneau de retrait adapté à ses contraintes, de récupérer sa commande préparée sans attente, tout en conservant le contact humain et la qualité artisanale.

La commande en ligne est une activité pratique qui nous invite à anticiper nos besoins et optimise nos déplacements. C'est un pont vers la modernité sans renier la tradition, mais il s'agit notamment d'une démarche volontaire. Libre à nous de choisir entre le parcours traditionnel et cette nouvelle expérience digitale. Bonne navigation et beaux achats avec Boucherie Eysa !

## Spécifications techniques et évolution architecturale

### Symfony 6.4 : le socle de départ
J'ai choisi Symfony comme framework backend principal en raison de sa maturité et de sa philosophie explicite. Symfony est reconnu pour sa robustesse et ses composants découplés, ce qui me permet de développer rapidement des fonctionnalités sécurisées. Mon approche initiale était de créer un site web traditionnel avec rendu serveur Twig, suivant les bonnes pratiques MVC. Cette base solide m'a permis d'implémenter rapidement le catalogue, le système de panier, la gestion des commandes et le back-office d'administration.

L'architecture Symfony favorise la séparation des responsabilités : contrôleurs fins, services métier testables, entités Doctrine bien structurées. Cette organisation facilite la maintenance et l'évolution de l'application à long terme. Grâce à son écosystème riche (Security, Validator, Mailer), j'ai pu intégrer facilement les fonctionnalités critiques comme la protection CSRF, les validations serveur et les e-mails transactionnels.

### Vue.js 3 : l'enrichissement progressif
L'ajout de Vue.js dans le projet découle de ma volonté d'améliorer la fluidité de l'expérience utilisateur sur les interactions critiques. Au lieu de tout refaire en SPA, j'ai opté pour une approche hybride : rendu serveur Twig pour les pages structurantes, et îlots Vue.js pour les micro-interactions qui en valent vraiment la peine.

Cette approche me permet d'obtenir le meilleur des deux mondes : premier affichage rapide grâce au rendu serveur, référencement optimal, et expérience utilisateur enrichie là où c'est pertinent. Le composant `PickupSlotSelector` illustre parfaitement cette philosophie : il gère la sélection de créneaux en temps réel, actualise les disponibilités sans rechargement et grise automatiquement les créneaux complets, tout en s'intégrant parfaitement avec les formulaires Symfony existants.

### Migration vers une architecture API complète : la vision V2
À terme, j'envisage de migrer l'ensemble vers une architecture découplée avec API REST complète et frontend Vue.js. Cette évolution permettrait de créer une Progressive Web App (PWA) offrant une expérience native sur mobile, avec possibilité d'ajout au bureau et fonctionnement hors-ligne partiel.

L'API Symfony serait étendue pour couvrir l'ensemble des opérations (catalogue, panier, commandes, authentification) avec documentation OpenAPI complète. Le frontend Vue deviendrait une Single Page Application consommant exclusivement cette API. Cette architecture faciliterait également le développement d'une future application mobile native (React Native ou Flutter) utilisant la même API.

### MySQL et Doctrine ORM
J'ai retenu MySQL comme système de gestion de base de données relationnelle en raison de sa fiabilité et de sa performance dans un contexte transactionnel. Doctrine ORM me permet de travailler avec une approche orientée objets tout en conservant le contrôle sur les requêtes et les migrations. Cette combinaison garantit l'intégrité des données critiques comme les commandes et les créneaux de retrait.

### Git et GitHub : organisation et traçabilité
Pour la gestion de version du projet Boucherie Eysa, j'utilise Git en conjonction avec GitHub. Cette organisation me permet de suivre précisément l'évolution du code avec des commits atomiques et des messages structurés suivant la convention Conventional Commits. Même en travaillant seul, cette discipline m'assure une traçabilité complète des modifications et facilite les démonstrations d'évolution au jury.

Les branches par fonctionnalité me permettent d'isoler le développement de chaque feature avant integration en develop, garantissant ainsi la stabilité de la branche principale. L'historique Git devient un véritable journal de bord du projet, documentant chaque décision technique et chaque amélioration.

## Conclusion sur les choix technologiques

Ces choix technologiques résultent d'une réflexion approfondie sur les besoins spécifiques d'une boucherie moderne. Ils visent à garantir une expérience utilisateur optimale tout en facilitant le développement et la maintenance. L'approche hybride actuelle (Symfony + îlots Vue) constitue une étape intermédiaire intelligente vers une architecture API complète, permettant d'évoluer progressivement sans disruption.

Cette stratégie d'évolution technique reflète une approche pragmatique : livrer rapidement de la valeur avec des technologies maîtrisées, puis faire évoluer l'architecture selon les retours utilisateurs et les besoins identifiés. L'objectif final reste de proposer la meilleure expérience possible aux clients de la boucherie, en alliant modernité technique et simplicité d'usage.

📎 **Annexe A :** captures d'écran du parcours utilisateur complet
📎 **Annexe F :** interfaces du back-office et exemples d'e-mails transactionnels

---

## 1. Compétences du référentiel couvertes

### 1.1 Développement Front-End Sécurisé

**Rendu serveur optimisé :** J'ai privilégié le rendu côté serveur avec **Twig** pour garantir un premier affichage rapide (LCP < 2,5s sur mobile) et un HTML sémantique. Cette approche favorise l'accessibilité et le référencement, tout en conservant une structure lisible pour les développeurs.

**Îlots Vue.js 3 ciblés :** J'ai enrichi l'expérience utilisateur avec des composants Vue.js uniquement là où la réactivité apporte une vraie valeur. Le composant `PickupSlotSelector` illustre cette approche : il gère la sélection de créneaux en temps réel, actualise les disponibilités sans rechargement et grise automatiquement les créneaux complets.

**Accessibilité et responsive :** Interface mobile-first avec cibles tactiles suffisantes (min 44px), contrastes respectant WCAG 2.1, navigation au clavier fonctionnelle et libellés explicites. Les validations côté client accompagnent l'utilisateur mais ne remplacent jamais les vérifications serveur.

### 1.2 Développement Back-End Robuste

**Architecture MVC maîtrisée :** Les contrôleurs restent fins et délèguent la logique métier aux services. Par exemple, `CartService` centralise toute la logique du panier (fusion des lignes, validation des poids, calculs de totaux) et expose des méthodes testables comme `addProduct()` avec gestion des contraintes métier.

**Modélisation orientée métier :** Les entités reflètent le domaine réel : `Product` avec contraintes de poids minimum/maximum, `Order` avec cycle de vie explicite (CREATED → CONFIRMED → PREPARING → READY), `CartItem` qui conserve le prix unitaire pour garantir la cohérence tarifaire.

**API REST documentée :** L'endpoint `/api/pickup-slots` expose un contrat JSON propre pour les créneaux, avec gestion d'erreurs explicite et codes HTTP appropriés. La documentation suit les standards OpenAPI et s'étendra en V2 au panier et aux commandes.

### 1.3 Sécurité Appliquée

**Protection CSRF systématique :** Tous les formulaires sont protégés par token CSRF, notamment `CheckoutFormType` qui sécurise la création de commandes. La validation double (client + serveur) empêche les soumissions malveillantes.

**Contrôle d'accès granulaire :** Le back-office utilise le système de rôles Symfony avec routes protégées. Les tests fonctionnels vérifient le refus d'accès aux utilisateurs non authentifiés.

**Validation serveur rigoureuse :** Aucune donnée utilisateur n'est acceptée sans validation. Le `CartService` vérifie les contraintes métier (poids minimum, quantités positives) et lève des exceptions explicites en cas de violation.

### 1.4 Gestion de Projet et Qualité

**Versioning et traçabilité :** Commits atomiques avec messages explicites (ex. "feat(cart): fusion des lignes identiques et recalcul des totaux"), branches par fonctionnalité, historique Git propre permettant de suivre l'évolution de chaque feature.

**Tests ciblés :** Couverture des règles métier critiques avec tests unitaires (fusion des lignes du panier) et fonctionnels (contrôle d'accès, validation des créneaux). Chaque test documente un comportement attendu du système.

**Documentation vivante :** Architecture documentée dans le code, guides de déploiement, configuration explicite dans les fichiers YAML (ex. `pickup_slots.yaml` pour les créneaux).

📎 **Annexe C :** matrice de traçabilité détaillée User Stories → Compétences → Preuves

---

## 2. Résumé du projet

Le projet **Boucherie Eysa** digitalise un service de boucherie artisanale en proposant un site de commande en ligne sobre, accessible et adapté aux usages mobiles.

**Fonctionnalités principales :**
- **Catalogue intelligent** avec respect des poids minimum (500g minimum pour les pièces de bœuf)
- **Panier cohérent** qui fusionne automatiquement les lignes identiques et calcule la TVA boucherie (5,5%)
- **Système de créneaux** configuré via YAML avec vérification temps réel des capacités
- **Confirmation sécurisée** avec e-mails transactionnels côté client et boutique
- **Back-office protégé** pour la gestion autonome des produits et commandes

**Architecture technique :**
L'application privilégie **Symfony 6.4 + Doctrine** pour le back-end avec des services métier testables, **Twig** pour le rendu serveur et **Vue.js 3** via CDN pour les interactions critiques. Cette stack garantit un premier affichage rapide et permet des évolutions maîtrisées.

**Sécurité :**
Protection CSRF sur tous les formulaires, validation double client/serveur, contrôle d'accès strict avec rôles Symfony, API sécurisée avec gestion d'erreurs appropriée.

**Technologies clés :** Symfony 6.4, Doctrine ORM, Vue.js 3, Twig, MySQL, YAML, API REST, composants ES6

La V2 prévoit l'intégration Stripe pour le paiement réel et l'extension de l'API REST pour une architecture découplée complète.

---

## 3. Cahier des charges et expression des besoins

### 3.1 Contexte et objectifs

Le besoin est né d'une observation simple. En période de forte affluence, la boutique est saturée et le client hésite à attendre. À l'inverse, en heures creuses, il aimerait pouvoir commander en amont et venir récupérer son colis sans s'éterniser. 

La boucherie souhaite donc offrir un canal de commande souple qui respecte ses contraintes de préparation et de capacité (maximum 10 commandes par créneau selon `pickup_slots.yaml`), sans se perdre dans un site marchand trop ambitieux. L'objectif est de livrer une première version fiable qui traite bien les cas courants et pose un cadre clair pour les évolutions.

**Contraintes métier identifiées :**
- Poids minimum variables selon les produits (ex. 500g pour une pièce de bœuf)
- Créneaux limités : matin (09:00-12:30) et après-midi (15:30-19:15), dimanche matin uniquement
- Fermeture le lundi
- Capacité maximale de 10 commandes par créneau

### 3.2 Personas et parcours

**Persona principal : client particulier mobile**
Il consulte la liste des produits depuis son téléphone, lit une fiche avec calcul automatique du poids, ajoute un article au panier (fusion automatique si déjà présent), puis passe à la sélection d'un créneau. Le composant Vue.js lui montre en temps réel les créneaux disponibles/limités/complets. Il valide la commande et reçoit immédiatement une confirmation par e-mail.

**Persona secondaire : administratrice boutique**
Elle se connecte au back-office protégé, consulte les commandes du jour avec filtrage par créneau, gère les produits (ajout, modification des poids minimum/maximum) et prépare l'accueil des clients selon leurs créneaux. L'interface est volontairement simple et sans jargon technique.

**Parcours mobile optimisé :** Une main suffit pour tout le processus. Cibles tactiles larges, navigation intuitive, retours visuels immédiats sur les créneaux, confirmation claire avec récapitulatif.

### 3.3 User stories et critères d'acceptation

**US01 :** "En tant que client, je peux ajouter un produit au panier"
- *Critères :* Bouton d'ajout visible, respect du poids minimum (validation côté client + serveur), fusion automatique des lignes identiques
- *Preuve :* `CartService::addProduct()` avec gestion des contraintes et tests unitaires

**US02 :** "En tant que client, je peux choisir un créneau de retrait"
- *Critères :* Liste des créneaux sans illusions (complets grisés), actualisation temps réel, validation serveur avant confirmation
- *Preuve :* Composant `PickupSlotSelector` + API `/api/pickup-slots` + service `PickupSlotService`

**US03 :** "En tant que client, je peux valider ma commande"
- *Critères :* Récapitulatif cohérent, confirmation par e-mail, protection CSRF
- *Preuve :* `CheckoutController` avec validation complète et e-mails transactionnels

**US04 :** "En tant qu'admin, je peux gérer les produits"
- *Critères :* Formulaires protégés, validation serveur, messages d'erreur compréhensibles
- *Preuve :* Back-office avec contrôle d'accès strict et tests fonctionnels

**US05 :** "En tant qu'admin, je peux consulter les commandes"
- *Critères :* Liste filtrable par date/créneau, détail utile à la préparation
- *Preuve :* Interface d'administration avec queries Doctrine optimisées

### 3.4 Backlog et périmètre MVP puis V2

**MVP livré (V1) :**
- ✅ Catalogue avec validation poids minimum
- ✅ Panier intelligent (fusion lignes, TVA 5,5%)
- ✅ Système créneaux configurable (YAML + API)
- ✅ Commande sécurisée (CSRF + validation double)
- ✅ E-mails transactionnels
- ✅ Back-office protégé

**V2 planifiée :**
- 🔄 Intégration paiement Stripe (Payment Intents + webhooks)
- 🔄 Extension API REST (panier, commandes)
- 🔄 PWA légère (service worker, manifest)
- 🔄 Tableaux de bord métier (statistiques commandes)

Cette progression évite l'effet tunnel et permet des démonstrations régulières, rassurant pour la commerçante comme pour l'équipe de développement.

### 3.5 Exigences non fonctionnelles

**Performance :** LCP mobile < 2,5s sur le catalogue grâce au rendu serveur, images optimisées, scripts non critiques différés. Vue.js chargé via CDN pour éviter la complexité de compilation.

**Accessibilité :** Libellés explicites, contrastes WCAG 2.1, navigation clavier, zones tactiles adaptées mobile (min 44px). Validation avec outils automatisés.

**Sécurité :** Protection CSRF systématique, validation serveur rigoureuse, contrôle d'accès granulaire, pas de données sensibles dans les logs, entêtes HTTP défensifs.

**RGPD :** Collecte limitée au nécessaire (nom, email, téléphone pour commande), finalités explicitées, durée de conservation définie, point de contact fourni.

📎 **Annexe G :** configuration entêtes de sécurité et extraits mentions légales

---

## 4. Spécifications techniques — architecture et mise en œuvre

### 4.1 Choix technologiques justifiés

**Symfony 6.4 :** Framework mature pour un socle fiable. Le composant Security offre un contrôle précis des rôles (`ROLE_ADMIN` pour le back-office), le Validator rend les règles métier explicites au plus près des entités, et l'EventDispatcher permet d'isoler les effets de bord comme l'envoi d'e-mails.

**Doctrine ORM :** Modélisation orientée objets avec contraintes DB, migrations versionnées documentées. Relations explicites entre `Order`, `OrderItem`, `Product` avec lazy loading maîtrisé.

**Vue.js 3 via CDN :** Approche hybride évitant la complexité d'un build. Composants autonomes (`PickupSlotSelector`) intégrables sans refactoring global. La Composition API offre une logique claire et réutilisable.

**MySQL :** Base relationnelle pour les agrégats cohérents (commande + lignes + créneau) avec contraintes d'intégrité, index sur les requêtes fréquentes (date/créneau).

**Architecture hybride :** Twig pour les pages "structurantes" (SEO, premier rendu), Vue pour les micro-interactions (créneaux, panier). Meilleur compromis performance/complexité pour un MVP.

### 4.2 Architecture logique

```
HTTP Request → Routeur Symfony → Controller → Service Métier → Repository → DB
                     ↓
               Twig Renderer → HTML + îlots Vue → Client
                     ↓
               API Endpoints → JSON pour composants Vue
```

**Flux d'ajout au panier :**
1. `ProductController::addToCart()` reçoit la requête
2. Délégation à `CartService::addProduct()` avec validation métier
3. Vérification contraintes (poids min/max, capacité panier)
4. Fusion automatique si produit déjà présent
5. Recalcul total avec TVA boucherie (5,5%)
6. Persistance Doctrine + retour JSON/redirection

**Flux de sélection créneau :**
1. Composant Vue charge `/api/pickup-slots` via fetch
2. `PickupSlotController` délègue à `PickupSlotService`
3. Service consulte configuration YAML + comptage DB
4. Retour JSON avec statuts (available/limited/full)
5. Sélection côté client + validation serveur avant confirmation

### 4.3 Modélisation des données

**Entités principales :**

```php
Product {
    name, description, price, image, stock
    minWeight, maxWeight  // Contraintes métier
    category              // Relation ManyToOne
}
// ... (voir modèle complet en annexe A.1)
```
*(Voir structure complète des entités Cart, Order et relations en annexe A.1)*

**Invariants métier :**
- Prix toujours positifs (validation entité)
- Quantités minimum respectées (validation service)
- Créneaux dans limites configurées (validation YAML)
- Total recalculé automatiquement (méthodes entité)

**Index stratégiques :**
- `(pickup_date, pickup_time_slot)` pour comptage créneaux
- `(product_name)` pour recherche catalogue
- `(session_id)` pour récupération panier

### 4.4 Front-end : accessibilité et performance

**Rendu serveur prioritaire :** HTML sémantique avec Twig, métadonnées Open Graph, structure heading logique (h1→h2→h3). JavaScript non bloquant pour le Above-The-Fold.

**Vue.js ciblé :** Composant `PickupSlotSelector` autonome avec Composition API :

```javascript
const { ref, onMounted, computed } = Vue;

const loadSlots = async () => {
    const response = await fetch('/api/pickup-slots');
    // Logique de sélection et validation...
};
```
*(Voir implémentation complète du composant Vue.js en annexe A.2)*

**Responsive mobile-first :** Grid CSS pour les créneaux, flexbox pour la navigation, touch targets > 44px, contrastes vérifiés automatiquement.

### 4.5 Back-end : services métier testables

**CartService - Logique centralisée :**

```php
public function addProduct(Product $product, int $quantity = 1): CartItem {
    // Validation poids minimum
    if ($product->getMinWeight() && $quantity < $product->getMinWeight()) {
        throw new \InvalidArgumentException(
            sprintf('Minimum requis : %dg', $product->getMinWeight())
        );
    }
    // Fusion automatique et calcul total...
}
```
*(Voir service complet avec gestion des erreurs et tests unitaires en annexe A.3)*

**PickupSlotService - Configuration externalisée :**

```php
public function getAvailableSlotsForDate(DateTime $date): array {
    // Chargement config YAML et comptage commandes
    $configPath = $this->projectDir . '/config/pickup_slots.yaml';
    $currentOrders = $this->orderRepository->countByDateAndTimeSlot($date, $timeSlot);
    // Logique de disponibilité...
}
```
*(Voir service complet avec configuration YAML et tests de capacité en annexe A.4)*

### 4.6 API et contrats

**Endpoint principal :** `GET /api/pickup-slots`

```json
{
    "success": true,
    "data": {
        "slots": [
            {
                "date": "2025-10-17",
                "day_name": "Jeudi",
                "slots": [
                    {
                        "key": "matin",
                        "time": "09:00-12:30",
                        "available": true
                        // ... détails capacité
                    }
                ]
            }
        ]
    }
}
```
*(Voir documentation API complète avec tous les endpoints en annexe B.1)*

**Gestion d'erreurs standardisée :**
- 400 : Paramètres invalides avec message explicite
- 500 : Erreur serveur sans exposition de détails internes
- Headers CORS configurés selon environnement

### 4.7 Sécurité et RGPD

**Protection CSRF :** Tous les formulaires incluent `csrf_protection: true` avec token vérifié côté serveur. Exemple `CheckoutFormType` :

```php
public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
        'csrf_protection' => true,
        'csrf_field_name' => '_token',
        'csrf_token_id' => 'checkout_form',
    ]);
}
```

**Contrôle d'accès :** Routes admin protégées par `ROLE_ADMIN`, tests fonctionnels vérifiant le refus d'accès non authentifié.

**Validation double :** Client (UX) + serveur (sécurité). Aucune donnée acceptée sans validation métier côté service.

**RGPD appliqué :** Collecte minimale (email pour commande), finalités documentées, durée de conservation explicite, contact fourni pour exercice des droits.

---

## 5. Réalisations du projet — extraits de code commentés

### 5.1 Catalogue et validation des poids

**Contrôleur simple et efficace :**

```php
#[Route('/product/{id}', name: 'product_show')]
public function show(Product $product): Response {
    return $this->render('product/product_detail.html.twig', [
        'product' => $product
    ]);
}
```

**Template avec validation côté client :**

```twig
<div class="product-constraints">
    {% if product.minWeight %}
        <p class="weight-info">Poids minimum : {{ product.minWeight }}g</p>
    {% endif %}
</div>

<form action="{{ path('cart_add', {id: product.id}) }}" method="post">
    <input type="number" name="quantity" min="{{ product.minWeight ?? 1 }}">
    {{ csrf_token('cart_add') }}
    // ... validation et soumission
</form>
```
*(Voir templates Twig complets avec gestion d'erreurs en annexe C.1)*

📎 **Annexe F :** captures mobile/desktop des fiches produit avec contraintes

### 5.2 Panier intelligent avec fusion automatique

**Service CartService - Logique métier centralisée :**

Cette approche orientée service garantit la cohérence des règles métier. Le CartService centralise toute la logique de gestion du panier : validation des poids minimum, fusion automatique des lignes identiques et recalcul des totaux avec TVA.

```php
public function addProduct(Product $product, int $quantity = 1): CartItem {
    // Validation poids minimum métier
    if ($product->getMinWeight() !== null && $quantity < $product->getMinWeight()) {
        throw new \InvalidArgumentException(
            sprintf('Quantité insuffisante pour %s. Minimum requis : %dg',
                $product->getName(), $product->getMinWeight())
        );
    }
    // Logique fusion automatique et recalcul total...
}
```
*(Voir implémentation complète CartService avec tests unitaires en annexe A.5)*

**Test unitaire critique :**

```php
public function testFusionLignesIdentiques(): void {
    $product = $this->createProduct('Côte de bœuf', 29.90);
    
    // Premier ajout : 500g
    $this->cartService->addProduct($product, 500);
    // Second ajout du même produit : 300g
    $this->cartService->addProduct($product, 300);
    
    $items = $this->cartService->getCartItems();
    $this->assertEquals(1, count($items)); // Toujours 1 ligne
    $this->assertEquals(800, $items[0]->getQuantity()); // Fusion automatique
}
```
*(Voir suite complète des tests unitaires en annexe A.6)*

📎 **Annexe E :** sortie de test et captures panier fusionné

### 5.3 Sélection de créneaux avec Vue.js 3

**Composant Vue autonome :**

```javascript
const PickupSlotSelector = {
    setup(props, { emit }) {
        const { ref, onMounted } = Vue;
        
        const slots = ref([]);
        const loading = ref(true);
        const selectedSlot = ref(null);

        const loadSlots = async () => {
            try {
                const response = await fetch('/api/pickup-slots');
                const data = await response.json();
                
                if (data.success) {
                    slots.value = data.data.slots;
                } else {
                    throw new Error(data.error);
                }
            } catch (err) {
                console.error('Erreur créneaux:', err);
            } finally {
                loading.value = false;
            }
        };

        const selectSlot = (date, slot) => {
            if (!slot.available) return; // Créneaux complets non sélectionnables
            
            selectedSlot.value = {
                date: date,
                timeSlot: slot.key,
                time: slot.time
            };
            
            // Notification parent + mise à jour formulaire
            emit('slot-selected', selectedSlot.value);
        };

        onMounted(loadSlots);
        
        return { slots, loading, selectedSlot, selectSlot };
    }
};
```

**API PickupSlotController :**

```php
#[Route('/api/pickup-slots', name: 'api_pickup_slots_list', methods: ['GET'])]
public function getAvailableSlots(Request $request): JsonResponse {
    $days = (int) $request->query->get('days', 7);
    
    try {
        $startDate = new \DateTime();
        $endDate = (clone $startDate)->modify("+{$days} days");
        
        $availableSlots = $this->pickupSlotService->getAvailableSlotsForPeriod(
            $startDate, $endDate
        );

        return $this->json([
            'success' => true,
            'data' => ['slots' => $availableSlots]
        ]);
    } catch (\Exception $e) {
        return $this->json([
            'success' => false,
            'error' => 'Erreur lors de la récupération : ' . $e->getMessage()
        ], 400);
    }
}
```

📎 **Annexe F :** captures créneaux disponibles/limités/complets

### 5.4 Configuration externe des créneaux

**Fichier config/pickup_slots.yaml :**

```yaml
pickup_slots:
    max_orders_per_slot: 10
    
    time_slots:
        matin: "09:00-12:30"
        apres-midi: "15:30-19:15"
    
    closed_days: [1]  # Lundi fermé
    days_ahead: 7
    
    special_hours:
        0: "10:00-13:00"  # Dimanche matin uniquement
```

**Service avec logique métier :**

```php
public function getAvailableSlotsForDate(DateTime $date): array {
    // Vérification jour fermé
    if (in_array((int)$date->format('w'), $this->config['closed_days'])) {
        return [];
    }
    
    $slots = [];
    $dayOfWeek = (int)$date->format('w');
    
    foreach ($this->config['time_slots'] as $slotKey => $slotTime) {
        // Règle métier : pas d'après-midi le dimanche
        if ($dayOfWeek === 0 && $slotKey === 'apres-midi') {
            continue;
        }
        
        $currentOrders = $this->orderRepository->countByDateAndTimeSlot($date, $slotKey);
        $isAvailable = $currentOrders < $this->config['max_orders_per_slot'];
        
        $slots[] = [
            'key' => $slotKey,
            'time' => ($slotKey === 'matin' && $dayOfWeek === 0) 
                ? $this->config['special_hours'][0] 
                : $slotTime,
            'available' => $isAvailable,
            'current_orders' => $currentOrders,
            'status' => $this->getSlotStatus($currentOrders)
        ];
    }
    
    return $slots;
}
```

### 5.5 Sécurité : protection CSRF et validation

**Formulaire CheckoutFormType :**

```php
class CheckoutFormType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('customer_email', EmailType::class, [
                'label' => 'Votre email',
                'constraints' => [
                    new NotBlank(message: 'Email requis'),
                    new Email(message: 'Email invalide')
                ]
            ])
            ->add('pickup_date', HiddenType::class, [
                'constraints' => [new NotBlank(message: 'Date de retrait requise')]
            ])
            ->add('pickup_time_slot', HiddenType::class, [
                'constraints' => [new NotBlank(message: 'Créneau requis')]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'checkout_form',
        ]);
    }
}
```

**Contrôleur avec validation double :**

```php
#[Route('/panier/checkout', name: 'checkout_process', methods: ['POST'])]
public function processCheckout(Request $request): Response {
    $order = new Order();
    $form = $this->createForm(CheckoutFormType::class, $order);
    $form->handleRequest($request);

    if ($form->isValid()) {
        // Revalidation métier côté serveur
        $isSlotAvailable = $this->pickupSlotService->isSlotAvailable(
            new \DateTime($order->getPickupDate()),
            $order->getPickupTimeSlot()
        );
        
        if (!$isSlotAvailable) {
            $this->addFlash('error', 'Créneau plus disponible. Veuillez en choisir un autre.');
            return $this->redirectToRoute('checkout_form');
        }
        
        // Création commande sécurisée
        $this->orderService->createFromCart($order, $this->cartService->getCurrentCart());
        
        return $this->redirectToRoute('order_confirmation', ['id' => $order->getId()]);
    }
    
    return $this->render('checkout/form.html.twig', ['form' => $form]);
}
```

### 5.6 E-mails transactionnels

**Service NotificationService :**

```php
public function sendOrderConfirmation(Order $order): void {
    // E-mail client
    $customerEmail = (new TemplatedEmail())
        ->from(new Address('contact@boucherie-eysa.fr', 'Boucherie Eysa'))
        ->to($order->getCustomerEmail())
        ->subject('Confirmation de votre commande #' . $order->getId())
        ->htmlTemplate('emails/order_confirmation.html.twig')
        ->context([
            'order' => $order,
            'pickup_date' => $order->getPickupDate(),
            'pickup_slot' => $order->getPickupTimeSlot()
        ]);
    
    // E-mail boutique  
    $shopEmail = (new TemplatedEmail())
        ->from(new Address('noreply@boucherie-eysa.fr'))
        ->to('commandes@boucherie-eysa.fr')
        ->subject('Nouvelle commande #' . $order->getId())
        ->htmlTemplate('emails/new_order_shop.html.twig')
        ->context(['order' => $order]);
    
    $this->mailer->send($customerEmail);
    $this->mailer->send($shopEmail);
}
```

📎 **Annexe F :** captures anonymisées des e-mails

---

## 6. Jeu d'essai — scénario représentatif

### Scénario : Commande complète avec fusion de lignes et créneau tendu

**Étape 1 : Ajout au panier avec fusion**

| Entrée | Attendu | Obtenu | Status |
|--------|---------|--------|--------|
| Ajouter "Côte de bœuf" 500g | 1 ligne panier, 14,95€ | 1 ligne, 14,95€ | ✅ OK |
| Ajouter "Côte de bœuf" 300g | 1 ligne, 800g, 23,92€ | 1 ligne, 800g, 23,92€ | ✅ OK |
| Vérifier TVA (5,5%) | 23,92€ TTC calculé | 23,92€ TTC affiché | ✅ OK |

**Étape 2 : Sélection créneau avec disponibilité limitée**

| Entrée | Attendu | Obtenu | Status |
|--------|---------|--------|--------|
| Charger créneaux jeudi | API retourne créneaux | 2 créneaux affichés | ✅ OK |
| Créneau matin (8/10 places) | Statut "limited" | Badge orange "2 places restantes" | ✅ OK |
| Créneau après-midi (10/10) | Statut "full", non sélectionnable | Bouton grisé "Complet" | ✅ OK |
| Sélectionner matin | Slot sélectionné, formulaire mis à jour | Confirmation visuelle + hidden inputs | ✅ OK |

**Étape 3 : Confirmation avec validation double**

| Entrée | Attendu | Obtenu | Status |
|--------|---------|--------|--------|
| Valider commande | Revalidation créneau côté serveur | Créneau vérifié avant création | ✅ OK |
| Confirmation réussie | Ordre créé, e-mails envoyés | Commande #127, 2 e-mails | ✅ OK |
| Log order.created | Trace avec contexte minimal | "[ORDER_CREATED] #127 pickup:2025-10-17_matin" | ✅ OK |

**Étape 4 : Simulation indisponibilité tardive**

| Entrée | Attendu | Obtenu | Status |
|--------|---------|--------|--------|
| Créneau plein entre sélection/validation | Message clair + alternative | "Créneau plus disponible. Essayez vendredi matin." | ✅ OK |
| Proposition alternative | Créneaux alternatifs suggérés | 3 créneaux suivants affichés | ✅ OK |

📎 **Annexe E :** captures complètes du parcours + logs anonymisés

---

## 📎 Annexes

**Annexe A :** Code backend complet (A.1 à A.6)
- A.1 : Structure complète des entités (Product, Cart, Order, relations)
- A.2 : Implémentation complète composant Vue.js PickupSlotSelector  
- A.3 : Service CartService avec gestion erreurs et tests unitaires
- A.4 : Service PickupSlotService avec configuration YAML
- A.5 : Implémentation complète CartService avec tests unitaires
- A.6 : Suite complète des tests unitaires

**Annexe B :** Documentation API et contrats
- B.1 : Documentation API complète avec tous les endpoints
- B.2 : Schémas JSON des réponses et codes d'erreur
- B.3 : Collection Postman/Bruno pour tests API

**Annexe C :** Templates et frontend  
- C.1 : Templates Twig complets avec gestion d'erreurs
- C.2 : Composant Vue.js complet avec logique de validation
- C.3 : Matrice traçabilité User Stories → Compétences → Preuves

**Annexe D :** Tests et qualité
- D.1 : Suite complète des tests unitaires avec rapports

**Annexe E :** Tests et métriques performance
- E.1 : Plan d'essai détaillé + rapports tests 
- E.2 : Métriques performance et captures DevTools
- E.3 : Logs incidents + commits de correction

**Annexe F :** Interfaces et communications
- F.1 : Captures mobile/desktop des fiches produit avec contraintes
- F.2 : Interfaces back-office + exemples e-mails transactionnels
- F.3 : Captures créneaux disponibles/limités/complets

**Annexe G :** Sécurité et déploiement
- G.1 : Configuration sécurité + entêtes HTTP sécurisés  
- G.2 : Mentions légales et politique RGPD
- G.3 : Runbook complet + script de supervision
- G.4 : Bibliographie commentée et sources de veille
---

## 📎 Annexes

**Annexe A :** Captures écran parcours utilisateur complet
**Annexe C :** Matrice traçabilité User Stories → Compétences → Preuves
**Annexe E :** Plan d'essai détaillé + rapports tests + metrics performance
**Annexe F :** Interfaces back-office + exemples e-mails transactionnels
**Annexe G :** Configuration sécurité + mentions légales + bibliographie commentée

---

*Dossier réalisé dans le cadre de la certification DWWM - CNAM*
*Candidat : [Votre nom] - Date : Octobre 2025*