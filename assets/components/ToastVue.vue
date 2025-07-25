<template>
  <div class="toast-container">
    <transition-group name="toast" tag="div">
      <div
        v-for="toast in toasts"
        :key="toast.id"
        class="toast"
        :class="toast.type"
      >
        {{ toast.message }}
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { useToast } from '../composables/useToast'
const { visible, message } = useToast()
</script>

<style scoped>
.toast-container {
  position: fixed;
  bottom: 1.5rem;
  right: 1.5rem;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.toast {
  padding: 12px 16px;
  border-radius: 6px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
  color: white;
  font-weight: bold;
  min-width: 200px;
  max-width: 300px;
  opacity: 0.95;
  transition: all 0.3s;
}

.toast.success { background-color: #38a169; }
.toast.error   { background-color: #e53e3e; }

.toast-enter-from,
.toast-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}
</style>
