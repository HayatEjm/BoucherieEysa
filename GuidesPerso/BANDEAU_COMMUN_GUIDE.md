# 🎠 SYSTÈME DE BANDEAU COMMUN

## 📋 **Principe**

Un **bandeau visuel adaptatif** qui change selon la page visitée, mais qui garde la même structure CSS/HTML.

## 🎯 **Utilisation dans vos contrôleurs**

### Pour ajouter le bandeau à une nouvelle page :

```php
use App\Service\BannerService;

#[Route('/ma-page', name: 'ma_page')]
public function maPage(BannerService $bannerService): Response
{
    // Récupérer les données du bandeau pour cette page
    $bannerData = $bannerService->getBannerData('nom_de_ma_page');
    
    return $this->render('ma_page/index.html.twig', [
        'bannerData' => $bannerData,
        // ... autres variables
    ]);
}
```

### Dans le template :

```twig
{% block body %}
    {# Inclure le bandeau #}
    {{ include('partials/page_banner.html.twig', {bannerData: bannerData}) }}
    
    {# Le reste de votre contenu #}
    <section>
        <!-- Votre contenu -->
    </section>
{% endblock %}
```

## ⚙️ **Ajouter un nouveau bandeau**

Dans `src/Service/BannerService.php`, ajoutez une entrée :

```php
'nom_de_votre_page' => [
    'title' => 'Titre du bandeau',
    'subtitle' => 'Description du bandeau',
    'image' => 'images/votre-image.jpg',
    'button_text' => 'TEXTE BOUTON',
    'button_link' => '/lien-du-bouton',
    'background_color' => '#couleur'
],
```

## 🎨 **Personnaliser le style**

Le CSS est dans `assets/styles/partials/page_banner.css` :

- **Hauteur** : `.page-banner { height: 400px; }`
- **Couleurs** : `.banner-button { background: #8B0000; }`
- **Tailles** : `.banner-title { font-size: 3.5rem; }`

## 📱 **Images recommandées**

- **Format** : 1920x400px minimum
- **Qualité** : Haute résolution
- **Emplacement** : `public/images/`

## 🎯 **Bandeaux actuellement configurés**

1. **Homepage** - Page d'accueil générale
2. **Click & Collect** - Page du service
3. **Category Beef** - Page catégorie bœuf
4. **Cart** - Page panier
5. **Order** - Page commande

## ✨ **Avantages**

- ✅ **Réutilisable** sur toutes les pages
- ✅ **Facile à maintenir** (un seul template)
- ✅ **Cohérent** visuellement
- ✅ **Responsive** automatique
- ✅ **Personnalisable** par page

---

**Le bandeau est maintenant prêt ! Ajoutez `bannerData` à vos contrôleurs et incluez le template ! 🎯**
