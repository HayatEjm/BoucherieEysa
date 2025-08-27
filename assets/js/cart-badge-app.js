// Script pour monter le badge du panier dans le header
import { createApp } from 'vue'
import { pinia } from '../stores/pinia'
import CartBadge from '../components/CartBadge.vue'

// Monter le badge du panier seulement s'il y a un conteneur
const cartBadgeContainer = document.getElementById('cart-badge-container')
if (cartBadgeContainer) {
    const app = createApp(CartBadge)
    app.use(pinia)
    app.mount('#cart-badge-container')
    
    console.log('🛒 Badge du panier monté avec Pinia partagée')
}
