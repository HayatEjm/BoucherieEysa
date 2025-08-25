<template>
  <div class="toast-banner-wrapper">
    <transition-group name="toast-banner" tag="div">
      <div
        v-for="toast in toasts"
        :key="toast.id"
        class="toast-banner"
        :class="toast.type"
      >
        <!-- Icône coche pour succès -->
        <span v-if="toast.type === 'success'" class="toast-icon">✔️</span>
        <!-- Icône croix pour erreur -->
        <span v-if="toast.type === 'error'" class="toast-icon">❌</span>
        <span class="toast-message">{{ toast.message }}</span>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
// J'importe le composable qui gère la logique des toasts
import { useToast } from '../composables/useToast'

// On récupère le tableau réactif des toasts et la fonction d'ajout
const { toasts, showToast } = useToast()




</script>

<style scoped>
/* Bandeau horizontal sous le header */
.toast-banner-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  z-index: 9999;
  pointer-events: none;
}

.toast-banner {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 48px;
  width: 100vw;
  font-size: 1.1rem;
  font-weight: bold;
  color: #fff;
  background-color: #38a169;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  border-bottom: 2px solid #2f855a;
  opacity: 0.98;
  transition: all 0.3s;
  pointer-events: auto;
}
.toast-banner.error {
  background-color: #e53e3e;
  border-bottom: 2px solid #c53030;
}
.toast-banner.success {
  background-color: #38a169;
  border-bottom: 2px solid #2f855a;
}
.toast-icon {
  margin-right: 12px;
  font-size: 1.3em;
}
.toast-message {
  display: inline-block;
}
.toast-banner-enter-from,
.toast-banner-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}
.toast-banner-enter-active,
.toast-banner-leave-active {
  transition: all 0.3s ease;
}
</style>
