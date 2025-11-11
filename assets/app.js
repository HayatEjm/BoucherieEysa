import './bootstrap.js';

// Import Vue.js et composants principaux
import { createApp } from 'vue'
import { pinia } from './stores/pinia'
import DropdownMenu from './components/DropdownMenu.vue'
import SearchBar from './components/SearchBar.vue'
import CartBadge from './components/CartBadge.vue'
import AddToCartButton from './components/AddToCartButton.vue'

// Attendre que le DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', function() {
  console.log('🚀 DOM chargé, initialisation des composants Vue...')

  // Initialisation du menu déroulant des catégories
  const mountPoint = document.getElementById('vue-dropdown-menu')
  if (mountPoint) {
    const categoriesData = mountPoint.dataset.categories
    console.log('🔍 DEBUG Menu - data-categories:', categoriesData)
    
    if (categoriesData) {
      try {
        const categories = JSON.parse(categoriesData)
        console.log('✅ Categories parsées:', categories)
        
        const dropdownApp = createApp(DropdownMenu, { categories })
        dropdownApp.mount(mountPoint)
        console.log('✅ Menu déroulant Vue monté avec succès')
      } catch (error) {
        console.error("❌ Erreur initialisation menu :", error)
        console.error("Stack trace:", error.stack)
      }
    } else {
      console.error('❌ ERREUR: data-categories est vide ou undefined')
    }
  } else {
    console.error('❌ ERREUR: Element #vue-dropdown-menu introuvable dans le DOM')
  }

  // Composant de recherche avec store Pinia
  const searchEl = document.querySelector('.search-container')
  if (searchEl) {
    const searchApp = createApp(SearchBar)
    searchApp.use(pinia)
    searchApp.mount(searchEl)
    console.log('✅ Barre de recherche montée')
  } else {
    console.log('ℹ️ Pas de barre de recherche sur cette page')
  }

  // Badge compteur panier dans le header
  const cartBadgeEl = document.getElementById('cart-badge')
  if (cartBadgeEl) {
    try {
      const badgeApp = createApp(CartBadge)
      badgeApp.use(pinia)
      badgeApp.mount(cartBadgeEl)
      console.log('✅ Badge panier monté')
    } catch (error) {
      console.error('❌ Erreur initialisation badge panier :', error)
    }
  } else {
    console.log('ℹ️ Pas de badge panier sur cette page')
  }
})

// Boutons d'ajout au panier sur toutes les pages
const addToCartButtons = document.querySelectorAll('.add-to-cart[data-product-id]')
if (addToCartButtons.length > 0) {
  addToCartButtons.forEach(button => {
    const productId = button.dataset.productId
    const quantity = parseInt(button.dataset.quantity) || 1
    
    if (productId) {
      const buttonApp = createApp(AddToCartButton, {
        productId,
        quantity,
        customClass: button.className,
        text: button.textContent.trim()
      })
      
      buttonApp.use(pinia)
      buttonApp.mount(button)
    }
  })
}


// Sélecteur de produit sur pages détail (chargement optimisé)
const el = document.getElementById('product-detail')
if (el) {
  async function initProductDetail() {
    try {
      // Import dynamique pour optimiser le chargement initial
      const { default: ProductDetail } = await import('./components/ProductDetail.vue')
      
      const product = JSON.parse(el.dataset.product)
      const minWeight = Number(el.dataset.minWeight || 200)
      const maxWeight = Number(el.dataset.maxWeight || 5000)
      const step = Number(el.dataset.step || 100)
      const suggestions = JSON.parse(el.dataset.suggestions || '[500,1000,1500]')

      const app = createApp(ProductDetail, {
        product,
        minWeight,
        maxWeight,
        step,
        suggestions
      })
      app.use(pinia)
      app.mount(el)
      
    } catch (error) {
      console.error('Erreur chargement ProductDetail :', error)
      el.innerHTML = '<p>Erreur de chargement du sélecteur de produit</p>'
    }
  }
  
  initProductDetail()
}



// Scripts JS spécifiques aux fonctionnalités
import './js/header.js'
import './js/category_products.js'
import './js/click_collect.js'
import './js/pickupSlots.js'

// Icônes et système de design
import '@fortawesome/fontawesome-free/css/all.min.css'
import './styles/design-system.css'

// Composants réutilisables (AVANT les pages qui les utilisent)
import './styles/components/forms.css'
import './styles/components/buttons.css'

// Partials réutilisables
import './styles/partials/page_banner.css'

// Styles spécifiques par page/composant (chargés AVANT app.css)
import './styles/home.css'
import './styles/category/category_list.css'
import './styles/category/category_products.css'
import './styles/category/quantity-selector.css' 
import './styles/partials/SearchBar.css'
import './styles/philosophy/philosophy.css'
import './styles/contact/contact.css'
import './styles/checkout/checkout.css'
import './styles/auth/auth.css'
import './styles/account.css'
import './styles/components/cookie-banner.css'

//  Styles principaux EN DERNIER pour qu'ils gagnent en spécificité
import './styles/app.css'

// Note: registerVueControllerComponents supprimé car causait des erreurs en production
// Les composants Vue sont montés manuellement ci-dessus