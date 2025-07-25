<template>
  <div>
    <!-- Composant principal d'affichage du panier -->
    <CartView />

    <!-- Composant pour les notifications (toast animé) -->
    <ToastVue />
  </div>
</template>

<script setup>
// J'importe le composant CartView depuis le dossier js/components
import CartView from './CartView.vue'

// J'importe le composant Toast pour les notifications utilisateur
import ToastVue from './ToastVue.vue'



</script>
