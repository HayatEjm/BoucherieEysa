<script setup>
import { ref, computed, onMounted } from 'vue'

import { useCartStore } from '../stores/cartStore'
import { useToast } from '../composables/useToast'

const cart = useCartStore()
const toast = useToast()
console.log('Cart store:', cart)
console.log('Méthodes dispo:', Object.keys(cart))

// Props depuis Twig
const props = defineProps({
  product:     { type: Object, required: true },
  minWeight:   { type: Number, required: true },
  maxWeight:   { type: Number, default: 6000 },
  step:        { type: Number, default: 100 },
  suggestions: { type: Array,  default: () => [500, 1000, 1500] }
})

// État local
const quantity = ref(props.minWeight)
const isAdding = ref(false)

// Prix calculé automatiquement
const totalPrice = computed(() => {
  return ((quantity.value / 1000) * props.product.pricePerKg).toFixed(2)
})

// Suggestion rapide
function setSuggestion(val) {
  quantity.value = val
}

// Ajout au panier via store Pinia
async function addToCart() {
  if (quantity.value < props.minWeight) {
    alert(`Quantité minimale requise : ${props.minWeight}g`)
    return
  }

  isAdding.value = true

  try {
    const message = await cart.addToCart(props.product.id, quantity.value)
    toast.showToast('Produit ajouté au panier')
  } catch (err) {
    toast.showToast(err.message || 'Erreur lors de l’ajout au panier', 'error')
  } finally {
    isAdding.value = false
  }
}
import ToastVue from './ToastVue.vue'
</script>

<template>
  <div class="product-info-section">
    <!-- En-tête produit -->
    <div class="product-header">
      <h1 class="product-title">{{ product.name }}</h1>
      <div v-if="product.category" class="product-category">
        {{ product.category.name }}
      </div>
    </div>

    <!-- Description -->
    <div v-if="product.description" class="product-description">
      {{ product.description }}
    </div>

    <!-- Calculateur de prix -->
    <div class="price-calculator">
      <h3>Pesez votre viande</h3>

      <div class="price-display">
        <div class="current-price">{{ totalPrice }} €</div>
        <div class="price-per-kg">{{ product.pricePerKg.toFixed(2) }} € / kg</div>
      </div>

      <!-- Sélecteur de quantité -->
      <div class="quantity-controls">
        <label class="quantity-label">Quantité (g) :</label>
        <div class="quantity-input-group">
          <button
            class="qty-btn qty-minus"
            @click="quantity = Math.max(quantity - step, minWeight)"
          >−</button>
          <input
            class="quantity-input"
            type="number"
            v-model.number="quantity"
            :min="minWeight"
            :max="maxWeight"
            :step="step"
          />
          <button
            class="qty-btn qty-plus"
            @click="quantity = Math.min(quantity + step, maxWeight)"
          >+</button>
          <span class="quantity-unit">g</span>
        </div>
      </div>

      <!-- Suggestions -->
      <div class="quantity-suggestions">
        <span class="suggestions-label">Suggestions :</span>
        <div class="suggestion-buttons">
          <button
            v-for="s in suggestions"
            :key="s"
            class="suggestion-btn"
            @click="setSuggestion(s)"
          >
            {{ s }}g
            <span class="suggestion-desc" v-if="s === 500">pour 1-2 personnes</span>
            <span class="suggestion-desc" v-else-if="s === 1000">pour 2-4 personnes</span>
            <span class="suggestion-desc" v-else-if="s === 1500">pour 4-6 personnes</span>
          </button>
        </div>
      </div>

      <!-- Notice poids minimum -->
      <div class="min-weight-notice">
        <span class="notice-text">Quantité minimale requise : {{ minWeight }}g</span>
        <span class="notice-explanation">
          Pour garantir la qualité et la praticité de la découpe.
        </span>
      </div>

      <!-- Bouton panier -->
      <button
        class="add-to-cart-btn btn-eysa btn-eysa-primary"
        @click="addToCart"
        :disabled="isAdding"
      >
        <span v-if="isAdding">Ajout en cours…</span>
        <span v-else>Ajouter au panier</span>
      </button>
       <ToastVue />
    </div>
  </div>
</template>

<style scoped>
/* Styles personnalisés ici si nécessaire */
</style>
