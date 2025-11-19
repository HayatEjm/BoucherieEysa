# 📧 Workflow d'inscription avec vérification email

## 🎯 Vue d'ensemble du parcours complet

```
┌─────────────────────────────────────────────────────────────────────┐
│                    PARCOURS UTILISATEUR COMPLET                     │
└─────────────────────────────────────────────────────────────────────┘

1️⃣  Utilisateur remplit le formulaire d'inscription
    └─> Route : GET /inscription
    └─> Template : templates/security/register.html.twig
    └─> Contrôleur : SignupController::index()

2️⃣  Soumission du formulaire
    └─> Route : POST /inscription
    └─> Validation des données (email unique, mot de passe)
    └─> Création du compte (isVerified = false)
    └─> Génération du token de vérification unique

3️⃣  Envoi de l'email de vérification
    └─> Template email : templates/emails/verify_email.html.twig
    └─> Contient le lien : https://boucherie-eysa.fr/verify-email/{token}

4️⃣  NOUVELLE ÉTAPE : Page intermédiaire "Vérifiez votre email"
    └─> Template : templates/security/signup_success.html.twig
    └─> Message : "Un email a été envoyé à {email}"
    └─> Instructions : "Cliquez sur le lien pour activer votre compte"
    └─> Aide : "Vérifiez vos spams, attendez quelques minutes..."

5️⃣  Utilisateur clique sur le lien dans l'email
    └─> Route : GET /verify-email/{token}
    └─> Contrôleur : VerifyEmailController::verifyEmail()
    └─> Vérification du token
    └─> Mise à jour : isVerified = true, verificationToken = null

6️⃣  Page de confirmation "Email vérifié ✓"
    └─> Template : templates/security/email_verified.html.twig
    └─> Message : "Votre compte a été activé !"
    └─> CTA : Bouton "Se connecter maintenant"

7️⃣  Connexion possible
    └─> Route : GET /login
    └─> L'utilisateur peut maintenant se connecter
```

---

## 📁 Fichiers créés/modifiés

### ✅ Nouveaux fichiers créés

1. **templates/security/signup_success.html.twig**
   - Page intermédiaire après inscription
   - Affiche l'email de l'utilisateur
   - Donne les instructions de vérification
   - 3 étapes visuelles à suivre
   - Aide pour retrouver l'email (spam, délai...)

2. **templates/security/email_verified.html.twig**
   - Page de confirmation après clic sur le lien email
   - Message de succès avec animation
   - Liste des avantages du compte
   - Bouton "Se connecter maintenant"

3. **src/Controller/VerifyEmailController.php**
   - Gère la vérification du token
   - Marque le compte comme vérifié
   - Gère les cas d'erreur (token invalide, déjà vérifié...)
   - Logs pour traçabilité

### 🔄 Fichiers modifiés

4. **src/Controller/SignupController.php**
   - Changement de la redirection finale
   - Avant : `redirectToRoute('app_login')` avec flash message
   - Après : `render('security/signup_success.html.twig')` avec email en paramètre

---

## 🎨 Design & Cohérence visuelle

### Styles appliqués

✅ **Respect du design system existant**
- Palette bordeaux/beige (`--primary-color`, `--beige-light`, `--success-color`)
- Typographies fluides (clamp)
- Boutons `.btn-eysa` cohérents
- Icônes Font Awesome
- Alerts `.alert-eysa` avec couleurs sémantiques

✅ **Animations subtiles**
- Icône succès avec bounce
- Icône vérifiée avec pulse
- Hover sur les listes de bénéfices

✅ **Responsive**
- Conteneur `.auth-container` adaptatif
- Layout flex avec passage en colonne sur mobile
- Tailles de police fluides

---

## 🔒 Sécurité implémentée

### Protections en place

1. **Token unique**
   - Généré avec `bin2hex(random_bytes(32))` (64 caractères)
   - Stocké en base de données
   - Supprimé après vérification (usage unique)

2. **Validité temporelle**
   - Mention "valide 24h" dans l'email
   - ⚠️ **À implémenter** : ajout d'un champ `verificationTokenCreatedAt` pour vérifier l'expiration côté serveur

3. **Gestion des cas d'erreur**
   - Token invalide → redirection login avec message d'erreur
   - Compte déjà vérifié → redirection login avec message info
   - Erreur serveur → log + message générique utilisateur

4. **Logs traçabilité**
   - Envoi email : succès/échec
   - Vérification : succès/échec/token invalide
   - Email déjà dans la BDD

---

## 📧 Template email détaillé

### Structure de l'email (verify_email.html.twig)

```
┌─────────────────────────────────────┐
│  Header bordeaux avec logo          │
│  "Boucherie Eysa"                   │
├─────────────────────────────────────┤
│                                     │
│  Bienvenue chez Boucherie Eysa !   │
│                                     │
│  Message explicatif                 │
│                                     │
│  ┌───────────────────────────────┐ │
│  │  Boîte de vérification beige  │ │
│  │                               │ │
│  │  [Vérifier mon adresse email] │ │ <- Bouton bordeaux
│  │                               │ │
│  │  Lien valide 24h              │ │
│  └───────────────────────────────┘ │
│                                     │
│  Lien alternatif (si bouton KO)    │
│                                     │
│  ⚠️ Avertissement sécurité          │
│  "Pas vous ? Ignorez cet email"    │
│                                     │
│  Signature équipe                  │
│                                     │
├─────────────────────────────────────┤
│  Footer gris avec lien site        │
└─────────────────────────────────────┘
```

### Points clés email

✅ **Compatibilité email clients**
- Styles inline (pas de CSS externe)
- Tables pour layout (Gmail, Outlook...)
- Balises `<center>` pour compatibilité
- Largeur max 600px

✅ **Accessibilité**
- Texte alternatif si bouton ne s'affiche pas
- Lien brut copie/collable
- Contrastes de couleurs suffisants

✅ **Clarté**
- Titre explicite
- Instructions étape par étape
- Mention durée de validité
- Aide si problème

---

## ✅ Tests à effectuer

### Parcours complet à tester

1. **Inscription classique**
   ```
   1. Aller sur /inscription
   2. Remplir le formulaire (email valide)
   3. Soumettre
   → Vérifier : page "Vérifiez votre email" s'affiche
   → Vérifier : email correct affiché
   ```

2. **Réception email**
   ```
   1. Vérifier boîte mail
   → Vérifier : email reçu (vérifier spam si besoin)
   → Vérifier : expéditeur correct (contact@boucherie-eysa.fr)
   → Vérifier : bouton cliquable
   ```

3. **Vérification email**
   ```
   1. Cliquer sur le bouton dans l'email
   → Vérifier : redirection vers /verify-email/{token}
   → Vérifier : page "Email vérifié ✓" s'affiche
   → Vérifier : animations icône
   ```

4. **Connexion après vérification**
   ```
   1. Cliquer "Se connecter maintenant"
   2. Entrer email + mot de passe
   → Vérifier : connexion réussie
   → Vérifier : redirection vers /account
   ```

### Tests cas d'erreur

5. **Token invalide**
   ```
   1. Aller sur /verify-email/FAUX_TOKEN
   → Vérifier : redirection /login
   → Vérifier : message d'erreur "lien invalide"
   ```

6. **Double vérification**
   ```
   1. Cliquer 2 fois sur le même lien email
   → Vérifier : 2e fois = message "compte déjà vérifié"
   → Vérifier : pas d'erreur serveur
   ```

7. **Email déjà utilisé**
   ```
   1. S'inscrire avec email existant
   → Vérifier : message d'erreur formulaire
   → Vérifier : pas d'email envoyé
   ```

---

## 🔧 Améliorations possibles (V2)

### Fonctionnalités avancées

1. **Expiration temporelle du token**
   ```php
   // Ajouter dans User entity
   private ?\DateTime $verificationTokenCreatedAt = null;
   
   // Dans VerifyEmailController
   $tokenAge = (new \DateTime())->diff($user->getVerificationTokenCreatedAt());
   if ($tokenAge->days > 1) {
       // Token expiré
   }
   ```

2. **Bouton "Renvoyer l'email"**
   - Route : `POST /resend-verification`
   - Contrôleur : `ResendVerificationController`
   - Limite : max 3 envois / heure

3. **Modification de l'email avant vérification**
   - Lien "Email incorrect ?" sur page signup_success
   - Formulaire simple avec nouveau email
   - Régénération token + nouvel envoi

4. **Statistiques admin**
   - Nombre de comptes créés / jour
   - Taux de vérification email
   - Délai moyen entre inscription et vérification

5. **Rappel automatique**
   - Cron job quotidien
   - Email de relance après 48h si pas vérifié
   - Suppression auto après 7 jours sans vérification

---

## 📝 Checklist mise en production

### Avant le déploiement

- [ ] Tester parcours complet sur environnement local
- [ ] Vérifier réception emails (vrai serveur SMTP)
- [ ] Tester tous les cas d'erreur
- [ ] Vérifier logs Monolog (pas d'erreur)
- [ ] Valider design responsive (mobile/tablette/desktop)
- [ ] Vérifier compatibilité email (Gmail, Outlook, Apple Mail)
- [ ] Tester lien email depuis mobile

### Après le déploiement

- [ ] Créer un compte de test sur prod
- [ ] Vérifier email reçu
- [ ] Cliquer sur lien de vérification
- [ ] Confirmer connexion possible
- [ ] Vérifier logs serveur (pas d'erreur 500)

---

## 🎓 Explications pour l'oral du jury

### Pourquoi ce workflow ?

> "J'ai implémenté un **système de vérification email en 2 étapes** pour améliorer la sécurité et l'expérience utilisateur.
>
> **Étape 1 - Page intermédiaire "Vérifiez votre email"** :  
> Plutôt que de rediriger directement vers la page de connexion avec un simple message flash qui peut être manqué, j'affiche une **page dédiée** qui explique clairement à l'utilisateur ce qu'il doit faire ensuite. Cette page inclut des **instructions visuelles en 3 étapes** et une section d'aide pour les cas problématiques (spam, délai...).
>
> **Étape 2 - Page de confirmation "Email vérifié"** :  
> Après clic sur le lien, au lieu d'un simple message de succès, j'affiche une **page de félicitations** avec les **avantages du compte** et un **bouton CTA direct** vers la connexion. Cela guide l'utilisateur vers l'action suivante de manière fluide.
>
> **Sécurité** :  
> - Token unique généré avec `random_bytes(32)` (cryptographiquement sécurisé)  
> - Token supprimé après usage (pas de réutilisation possible)  
> - Vérifications côté serveur (token valide, compte pas déjà vérifié)  
> - Logs pour tracer les tentatives suspectes
>
> **UX améliorée** :  
> - Messages clairs à chaque étape  
> - Design cohérent avec le reste du site (palette bordeaux/beige)  
> - Animations subtiles pour feedback visuel  
> - Aide contextuelle (spam, délai...)  
> - Responsive mobile-first"

---

## 🚀 Résumé : Ce qui a été ajouté

| Fichier | Type | Rôle |
|---------|------|------|
| `templates/security/signup_success.html.twig` | Vue | Page intermédiaire "Vérifiez votre email" |
| `templates/security/email_verified.html.twig` | Vue | Page confirmation "Email vérifié ✓" |
| `src/Controller/VerifyEmailController.php` | Contrôleur | Logique de vérification du token |
| `src/Controller/SignupController.php` | Modifié | Redirection vers page intermédiaire |

**Impact** : Workflow d'inscription professionnel et rassurant pour l'utilisateur ! ✅
