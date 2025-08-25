// assets/cart-app.js
import '../styles/design-system.css';  //  variables et tokens
import '../styles/cart/cart.css';      // styles spécifiques panier 

import { createApp } from 'vue'
import { createPinia } from 'pinia'

// J'importe le composant CartApp (qui inclut CartView et ToastVue)
import CartApp from '../components/CartApp.vue'

const app = createApp({
  components: { CartApp },

  // Voici le template inline auquel je faisais référence
  template: `
    <CartApp />
  `
})

app.use(createPinia())
app.mount('#cart-app')
