<template>
  <span 
    :class="badgeClasses"
    :data-count="displayCount"
    :aria-label="ariaLabel"
    :title="tooltipText"
  >
    {{ displayCount }}
  </span>
</template>

<script>
import { useCartStore } from '../stores/cartStore'
import { computed, onMounted, ref } from 'vue'

export default {
  name: 'CartBadge',
  setup() {
    const cartStore = useCartStore()
    const hasLoadedOnce = ref(false)

    // Charger le panier au montage
    onMounted(async () => {
      try {
        await cartStore.fetchSummary()
        hasLoadedOnce.value = true
      } catch (error) {
        console.error('❌ CartBadge: Erreur lors du chargement:', error)
        hasLoadedOnce.value = true
      }
    })

    // Affichage du compteur (limité à 99+)
    const displayCount = computed(() => {
      return cartStore.totalQuantity > 99 ? '99+' : cartStore.totalQuantity.toString()
    })

    // Classes CSS dynamiques basées sur la quantité
    const badgeClasses = computed(() => {
      const baseClass = 'cart-badge'
      const count = cartStore.totalQuantity
      
      // Ne pas cacher pendant le premier chargement
      if (!hasLoadedOnce.value) return baseClass
      
      if (count === 0) return `${baseClass} hidden`
      if (count >= 100) return `${baseClass} lots`
      if (count >= 10) return `${baseClass} many`
      return baseClass
    })

    // Texte d'accessibilité
    const ariaLabel = computed(() => {
      const count = cartStore.totalQuantity
      if (count === 0) return 'Panier vide'
      return count === 1 ? '1 article dans le panier' : `${count} articles dans le panier`
    })

    // Texte du tooltip
    const tooltipText = computed(() => ariaLabel.value)

    return {
      displayCount,
      badgeClasses,
      ariaLabel,
      tooltipText,
      totalQuantity: computed(() => cartStore.totalQuantity)
    }
  }
}
</script>

<style scoped>
/* Les styles sont déjà définis dans cart_badge.css, on les garde */
</style>
