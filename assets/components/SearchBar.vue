<template>
  <!-- Conteneur principal du composant -->
  <div class="search-container" ref="container">
    
    <!-- Bouton d'ouverture/fermeture de la recherche -->
    <a href="#" class="icon-link" @click.prevent="toggleDropdown">
      <i class="fas fa-search"></i> <!-- Icône loupe -->
    </a>

    <!-- Dropdown de recherche -->
    <div class="search-dropdown" :class="{ show: isOpen }">
      <input
        type="text"
        placeholder="Rechercher un produit"
        v-model="query"
        @input="onInput"
      />

      <!-- Zone de résultats -->
      <div class="search-results">
        <!-- Affichage pendant le chargement -->
        <div v-if="isLoading" class="search-loading">
          <i class="fas fa-spinner"></i> Recherche...
        </div>

        <!-- Aide si trop peu de caractères -->
        <p v-else-if="showHint" class="search-hint">
          Tapez au moins 2 caractères...
        </p>

        <!-- Aucun résultat trouvé -->
        <div v-else-if="!hasResults" class="search-no-results">
          Aucun résultat trouvé
        </div>

        <!-- Résultats produits et catégories -->
        <template v-else>
          <!-- Produits -->
          <div v-if="results.products.length">
            <h4>Produits</h4>
            <div
              v-for="product in results.products"
              :key="product.id"
              class="search-item"
              @click="goToUrl(product.url)"
              style="cursor: pointer"
            >
              <strong>{{ product.name }}</strong>
              <span>{{ product.price }}€ - {{ product.category_name }}</span>
            </div>
          </div>

          <!-- Catégories -->
          <div v-if="results.categories.length">
            <h4>Catégories</h4>
            <div
              v-for="category in results.categories"
              :key="category.id"
              class="search-item"
              @click="goToUrl(category.url)"
              style="cursor: pointer"
            >
              <strong>{{ category.name }}</strong>
              <span>{{ category.product_count || 0 }} produit(s)</span>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
// Import des fonctions Vue pour gérer le cycle de vie et la réactivité
import { ref, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'

// isOpen → permet d'afficher ou cacher le menu déroulant
const isOpen = ref(false)

// query → contient le texte tapé par l'utilisateur
const query = ref('')

// isLoading → permet d'afficher un "spinner" pendant que la recherche se fait
const isLoading = ref(false)

// searchTimeout → utilisé pour retarder la recherche et ne pas spammer le serveur
const searchTimeout = ref(null)

// results → contient les produits, catégories et le total reçus de l'API
const results = ref({
  products: [],
  categories: [],
  total_results: 0
})

// ref vers le conteneur principal, utilisé pour détecter les clics en dehors
const container = ref(null)

// hasResults → true si des résultats sont présents
const hasResults = computed(() => results.value.total_results > 0)

// showHint → true si l'utilisateur tape moins de 2 caractères
const showHint = computed(() => query.value.length < 2 && !isLoading.value)

// Affiche ou masque la recherche
function toggleDropdown() {
  isOpen.value = !isOpen.value
  if (isOpen.value) {
    nextTick(() => {
      const input = container.value?.querySelector('input[type="text"]')
      input?.focus()
    })
  } else {
    closeDropdown()
  }
}

// Ferme la recherche et réinitialise les champs
function closeDropdown() {
  isOpen.value = false
  query.value = ''
  results.value = { products: [], categories: [], total_results: 0 }
  clearTimeout(searchTimeout.value)
}

// Redirige vers une URL (produit ou catégorie)
function goToUrl(url) {
  closeDropdown()
  window.location.href = url
}

// Déclenchée à chaque frappe → déclenche la recherche après un délai
function onInput() {
  clearTimeout(searchTimeout.value)

  if (query.value.length < 2) {
    results.value = { products: [], categories: [], total_results: 0 }
    return
  }

  searchTimeout.value = setTimeout(() => {
    performSearch()
  }, 300)
}

// Appelle l’API Symfony /api/search avec la requête
async function performSearch() {
  isLoading.value = true
  try {
    const response = await fetch(`/api/search?q=${encodeURIComponent(query.value)}`)
    const data = await response.json()
    results.value = data
  } catch (error) {
    console.error('Erreur recherche :', error)
    results.value = { products: [], categories: [], total_results: 0 }
  } finally {
    isLoading.value = false
  }
}

// Ferme le dropdown si on clique à l’extérieur du composant
function handleClickOutside(event) {
  if (container.value && !container.value.contains(event.target)) {
    closeDropdown()
  }
}

// Attache l'écoute du clic extérieur au montage du composant
onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

// Nettoie au démontage du composant
onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
  clearTimeout(searchTimeout.value)
})
</script>
