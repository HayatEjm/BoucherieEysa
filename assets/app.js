
// --- Import Vue.js & Composants


import { createApp } from 'vue'
import { pinia } from './stores/pinia'
import DropdownMenu from './components/DropdownMenu.vue'
import ProductDetail from './components/ProductDetail.vue'
import SearchBar from './components/SearchBar.vue'
import CartBadge from './components/CartBadge.vue'
import AddToCartButton from './components/AddToCartButton.vue'

// --- Composant Menu Déroulant (Header)
const mountPoint = document.getElementById('vue-dropdown-menu')
if (mountPoint) {
  try {
    const categories = JSON.parse(mountPoint.dataset.categories)
    const dropdownApp = createApp(DropdownMenu, { categories })
    dropdownApp.mount(mountPoint)
  } catch (error) {
    console.error("Erreur menu :", error)
  }
}

// --- Composant Vue SearchBar
const searchEl = document.querySelector('.search-container')
if (searchEl) {
  const searchApp = createApp(SearchBar)
  searchApp.use(pinia) // ✅ Utilisation de Pinia partagée
  searchApp.mount(searchEl)
}

// --- Composant Vue CartBadge
const cartBadgeEl = document.getElementById('cart-badge')
if (cartBadgeEl) {
  try {
    const badgeApp = createApp(CartBadge)
    badgeApp.use(pinia)
    badgeApp.mount(cartBadgeEl)
  } catch (error) {
    console.error('Erreur CartBadge :', error)
  }
}

// --- Composants Vue AddToCartButton (version simple qui fonctionne)
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


// --- Composant Vue.js ProductDetail
const el = document.getElementById('product-detail')
if (el) {
  try {
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
    console.error('Erreur ProductDetail :', error)
  }
}



// --- Imports JS personnalisés
import './js/header.js'
import './js/category_products.js'
import './js/click_collect.js'
import './bootstrap.js'
import './js/pickupSlots.js'

// --- Fonts & Design System
import '@fortawesome/fontawesome-free/css/all.min.css'
import './styles/design-system.css'

// --- CSS Global & Pages
import './styles/app.css'
import './styles/category/category_list.css'
import './styles/category/quantity-selector.css'
import './styles/partials/header.css'
import './styles/partials/SearchBar.css'
import './styles/partials/footer.css'
import './styles/partials/click_collect.css'
import './styles/partials/pickup-slots.css'
import './styles/philosophy/philosophy.css'
import './styles/product/product_list_simple.css'
import './styles/product/product_detail.css'
import './styles/checkout/checkout.css'
import './styles/cart/cart_badge.css'
import './styles/auth/auth.css'
import './styles/account.css'

// Fin
