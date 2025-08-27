// assets/cart-app.js
import '../styles/design-system.css';  //  variables et tokens
import '../styles/cart/cart.css';      // styles spécifiques panier 

import { createApp } from 'vue'
import { pinia } from '../stores/pinia'

// J'importe le composant CartApp (qui inclut CartView et ToastVue)
import CartApp from '../components/CartApp.vue'

const app = createApp({
  components: { CartApp },

  // Voici le template inline auquel je faisais référence
  template: `
    <CartApp />
  `
})

app.use(pinia)
app.mount('#cart-app')
