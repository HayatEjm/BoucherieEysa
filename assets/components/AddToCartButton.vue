<template>
  <button 
    :class="buttonClasses"
    :disabled="loading"
    @click="handleAddToCart"
  >
    {{ buttonText }}
  </button>
</template>

<script>
import { useCartStore } from '../stores/cartStore'
import { ref, computed } from 'vue'

export default {
  name: 'AddToCartButton',
  props: {
    productId: {
      type: [String, Number],
      required: true
    },
    quantity: {
      type: Number,
      default: 1
    },
    customClass: {
      type: String,
      default: 'add-to-cart'
    },
    text: {
      type: String,
      default: 'Ajouter au panier'
    },
    loadingText: {
      type: String,
      default: 'Ajout...'
    }
  },
  
  setup(props, { emit }) {
    const cartStore = useCartStore()
    const loading = ref(false)
    
    const buttonText = computed(() => {
      return loading.value ? props.loadingText : props.text
    })
    
    const buttonClasses = computed(() => {
      return `${props.customClass} ${loading.value ? 'loading' : ''}`
    })
    
    const handleAddToCart = async () => {
      if (loading.value) return
      
      loading.value = true
      
      try {
        const message = await cartStore.addToCart(props.productId, props.quantity)
        emit('added', { productId: props.productId, quantity: props.quantity, message })
      } catch (error) {
        emit('error', error)
      } finally {
        loading.value = false
      }
    }
    
    return {
      loading,
      buttonText,
      buttonClasses,
      handleAddToCart
    }
  }
}
</script>
