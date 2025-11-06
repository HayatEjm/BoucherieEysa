// Point d'entrée pour la page panier
// Chargement optimisé du composant CartApp
import '../styles/design-system.css';
import '../styles/cart/cart.css';

import { createApp } from 'vue'
import { pinia } from '../stores/pinia'

async function initCartApp() {
  try {
    // Import dynamique pour réduire la taille du bundle principal
    const { default: CartApp } = await import('../components/CartApp.vue');
    
    const app = createApp({
      components: { CartApp },
      template: `<CartApp />`
    });
    
    app.use(pinia);
    app.mount('#cart-app');
    
  } catch (error) {
    console.error('Erreur chargement CartApp:', error);
    
    // Fallback en cas d'erreur de chargement
    document.getElementById('cart-app').innerHTML = `
      <div class="cart-error">
        <p>Erreur de chargement du panier</p>
        <button onclick="location.reload()">Réessayer</button>
      </div>
    `;
  }
}

initCartApp();
