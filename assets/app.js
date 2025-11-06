
// Import Vue.js et composants principaux
import { createApp } from 'vue'
import { pinia } from './stores/pinia'
import DropdownMenu from './components/DropdownMenu.vue'
import SearchBar from './components/SearchBar.vue'
import CartBadge from './components/CartBadge.vue'
import AddToCartButton from './components/AddToCartButton.vue'

// Initialisation du menu déroulant des catégories
const mountPoint = document.getElementById('vue-dropdown-menu')
if (mountPoint) {
  try {
    const categories = JSON.parse(mountPoint.dataset.categories)
    const dropdownApp = createApp(DropdownMenu, { categories })
    dropdownApp.mount(mountPoint)
  } catch (error) {
    console.error("Erreur initialisation menu :", error)
  }
}

// Composant de recherche avec store Pinia
const searchEl = document.querySelector('.search-container')
if (searchEl) {
  const searchApp = createApp(SearchBar)
  searchApp.use(pinia)
  searchApp.mount(searchEl)
}

// Badge compteur panier dans le header
const cartBadgeEl = document.getElementById('cart-badge')
if (cartBadgeEl) {
  try {
    const badgeApp = createApp(CartBadge)
    badgeApp.use(pinia)
    badgeApp.mount(cartBadgeEl)
  } catch (error) {
    console.error('Erreur initialisation badge panier :', error)
  }
}

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
import './bootstrap.js'
import './js/pickupSlots.js'

// Icônes et système de design
import '@fortawesome/fontawesome-free/css/all.min.css'
import './styles/design-system.css'

// Styles principaux
import './styles/app.css'

// Styles spécifiques par page/composant
import './styles/category/quantity-selector.css' 
import './styles/partials/SearchBar.css'
import './styles/philosophy/philosophy.css'
import './styles/checkout/checkout.css'
import './styles/auth/auth.css'
import './styles/account.css'
import './styles/components/cookie-banner.css'
