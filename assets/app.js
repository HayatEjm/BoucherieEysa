
console.log(' app.js bien chargé');

// --- Import Vue.js & Composants


import { createApp } from 'vue'
import { createPinia } from 'pinia'
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
    console.log("🟢 Point de montage menu trouvé, catégories :", categories)

    const dropdownApp = createApp(DropdownMenu, { categories })
    dropdownApp.mount(mountPoint)
  } catch (error) {
    console.error("❌ Erreur lors de l'initialisation du menu déroulant :", error)
  }
}

// --- Composant Vue SearchBar


const searchEl = document.querySelector('.search-container')
if (searchEl) {
  const searchApp = createApp(SearchBar)
  searchApp.mount(searchEl)
  console.log('🟢 Composant SearchBar monté avec succès')
}

// --- Composant Vue CartBadge
const cartBadgeEl = document.getElementById('cart-badge')
if (cartBadgeEl) {
  try {
    const badgeApp = createApp(CartBadge)
    badgeApp.use(createPinia())
    badgeApp.mount(cartBadgeEl)
    console.log('🟢 Composant CartBadge monté avec succès')
  } catch (error) {
    console.error('❌ Erreur lors du montage du composant CartBadge :', error)
  }
}

// --- Composants Vue AddToCartButton (remplace les boutons legacy)
const addToCartButtons = document.querySelectorAll('.add-to-cart[data-product-id]')
if (addToCartButtons.length > 0) {
  console.log(`🟢 ${addToCartButtons.length} boutons "Ajouter au panier" trouvés`)
  
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
      
      buttonApp.use(createPinia())
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
    app.use(createPinia())
    app.mount(el)

    console.log('🟢 Composant ProductDetail monté avec succès (avec Pinia)')
  } catch (error) {
    console.error('❌ Erreur lors du montage du composant ProductDetail :', error)
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

// Fin
console.log('🎉 Fin de chargement app.js (Vue + Styles + JS)');
