import './js/category_products.js'; // JS dédié à la page catégorie produits
import './js/checkout.js'; // JS dédié à la page checkout
import './js/click_collect.js'; // JS dédié à la page Click & Collect
import './js/home.js'; // JS dédié à la page d'accueil
import './js/philosophy.js'; // JS dédié à la page philosophie
// import './styles/product/product_list.css'; // CSS dédié à la page liste produits (supprimé car page supprimée)
import './styles/product/category_products.css'; // CSS dédié à la page catégorie produits
import './js/cart_page.js'; // JS dédié à la page panier (MVC)
import './bootstrap.js';

// Font Awesome - Icônes locales (plus fiable que le CDN)
import '@fortawesome/fontawesome-free/css/all.min.css';

// Design System - Variables globales 
import './styles/design-system-new.css';

import './styles/app.css';
import './styles/category/category_list.css';
import './styles/category/quantity-selector.css';
import './styles/partials/header.css';
import './styles/partials/footer.css';  // Footer noir
import './styles/partials/click_collect.css';
// import './styles/partials/page_banner.css';  //  CSS du bandeau commun
import './styles/partials/pickup-slots.css'; // CSS des créneaux de retrait

import './styles/philosophy/philosophy.css'; // CSS de la page philosophie

import './styles/product/product_list_simple.css';
import './styles/product/product_detail.css';
import './js/product_detail.js'; // JS dédié à la fiche produit (MVC)

// CHECKOUT - CSS pour les pages de finalisation
import './styles/checkout/checkout.css';

// PANIER - CSS spécifique pour la gestion du panier
import './styles/cart/cart.css';          //  CSS de la page panier
import './styles/cart/cart_badge.css';    // CSS du badge panier

// PANIER - JavaScript global pour la gestion du panier
import './js/cart.js';                    //   JS du système panier

// CRÉNEAUX DE RETRAIT - Module Vue.js pour la sélection des créneaux
import './js/pickupSlots.js';             //   JS du composant créneaux

// RECHERCHE - Système de recherche moderne
import './search.js';                    //   JS de la recherche

//   JS du menu déroulant
import './js/productMenu.js';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
