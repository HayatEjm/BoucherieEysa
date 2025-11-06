// assets/composables/useToast.js
//ici pour centraliser la logique d'affichage des toasts sans les mélanger aux composants Vue
import { ref } from 'vue'

const toasts = ref([])

function showToast(msg, type = 'success', delay = 3000) {
  const id = Date.now() + Math.random()
  toasts.value.push({ id, message: msg, type })
  setTimeout(() => {
    toasts.value = toasts.value.filter(t => t.id !== id)
  }, delay)
}

export function useToast() {
  return {
    toasts,
    showToast,
  }
}
