// assets/composables/useToast.js
//ici pour centraliser la logique d'affichage des toasts sans les mélanger aux composants Vue

import { ref } from 'vue'

const visible = ref(false)
const message = ref('')
const timeout = ref(null)

export function useToast() {
  function showToast(msg, delay = 3000) {
    message.value = msg
    visible.value = true

    if (timeout.value) {
      clearTimeout(timeout.value)
    }

    timeout.value = setTimeout(() => {
      visible.value = false
    }, delay)
  }

  return {
    visible,
    message,
    showToast,
  }
}

