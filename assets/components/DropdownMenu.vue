<!-- assets/components/DropdownMenu.vue -->
<script setup>
import { ref } from 'vue'

// Props attendues
const props = defineProps({
  categories: {
    type: Array,
    required: true,
    default: () => []
  }
})

const isOpen = ref(false)

function toggle() {
  isOpen.value = !isOpen.value
}
function close() {
  isOpen.value = false
}
</script>
<template>
  <li class="dropdown" @mouseleave="close">
    <a href="#" class="dropbtn" @click.prevent="toggle">
      NOS PRODUITS
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-content" :class="{ show: isOpen }">
      <li v-for="category in categories" :key="category.id">
        <a :href="`/categories/${category.id}`">{{ category.name }}</a>
      </li>
    </ul>
  </li>
</template>


<style>
/* j'ai déjà définie le style dans header.css */
</style>
