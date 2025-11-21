<template>
  <div class="cart-container">
    <!-- État de chargement -->
    <div v-if="cart.loading" class="cart-loading">
      <h2>Chargement en cours...</h2>
      <p>Récupération de votre panier...</p>
    </div>
    
    <!-- Panier vide -->
    <div v-else-if="cart.isEmpty" class="cart-empty">
      <h2>Votre panier est vide</h2>
      <p>Il semble que vous n'ayez pas encore ajouté de produit.</p>
      <a href="/" class="btn-continue-shopping">Voir les produits</a>
    </div>
    
    <!-- Contenu du panier -->
    <div v-else>
      <h2 class="cart-title">Contenu de votre panier</h2>
      <div class="cart-items">
        <div v-for="item in cart.items" :key="item.product.id" class="cart-item">
          <div class="cart-item-image">
            <img :src="item.product.image ? `/images/${item.product.image}` : 'https://via.placeholder.com/80'" alt="">
          </div>
          <div class="cart-item-info">
            <h3>{{ item.product.name }}</h3>
            <p>Quantité : {{ (item.quantity / 1000).toFixed(2) }}kg</p>
            <p>Prix unitaire : {{ item.unitPrice.toFixed(2) }} €/kg</p>
            <p>Total : {{ item.totalTTC.toFixed(2) }} €</p>
          </div>
          <div class="cart-item-actions">
            <button @click="modify(item.product.id)" class="btn-modify-item">
              <i class="fas fa-edit"></i> Modifier
            </button>
            <button @click="remove(item.product.id)" class="btn-remove-item">
              <i class="fas fa-trash-alt"></i> Supprimer
            </button>
          </div>
        </div>
      </div>
      <div class="cart-summary">
        <h3>Total TTC : {{ cart.totalPrice.toFixed(2) }} €</h3>
        <div class="cart-actions">
          <button @click="confirmClear" class="btn-clear-cart">
            <i class="fas fa-times-circle"></i> Vider le panier
          </button>
          <a href="/checkout" class="btn-checkout">
            <i class="fas fa-check-circle"></i> Valider le panier
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useCartStore } from '../stores/cartStore'
import { useToast } from '../composables/useToast'

// On importe le CSS global spécifique au panier
import '../styles/cart/cart.css'

const cart = useCartStore()
const toast = useToast()

onMounted(() => {
  cart.fetchSummary()
})

// Supprimer un produit
async function remove(productId) {
  await cart.removeProduct(productId)
  toast.showToast('Produit supprimé du panier', 'success')
}

// Modifier un produit (redirection vers la fiche produit)
function modify(productId) {
  // Rediriger vers la page produit pour permettre la modification
  window.location.href = `/product/${productId}`
}

// Vider le panier avec confirmation JS simple
function confirmClear() {
  if (confirm('Êtes-vous sûr de vouloir vider votre panier ?')) {
    cart.clearCart()
    toast.showToast('Panier vidé', 'success')
  }
}
</script>
