// Composant Vue.js pour la recherche - Simple et efficace
import { createApp } from 'vue';

document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si l'élément de recherche existe
    const searchContainer = document.querySelector('.search-container');
    if (!searchContainer) return;

    console.log('🔍 Initialisation du composant Vue de recherche...');

    createApp({
        data() {
            return {
                isOpen: false,
                query: '',
                results: {
                    products: [],
                    categories: [],
                    total_results: 0
                },
                isLoading: false,
                searchTimeout: null
            };
        },
        computed: {
            hasResults() {
                return this.results.total_results > 0;
            },
            showHint() {
                return this.query.length < 2 && !this.isLoading;
            }
        },
        methods: {
            toggleDropdown() {
                this.isOpen = !this.isOpen;
                
                const dropdown = this.$el.querySelector('.search-dropdown');
                if (dropdown) {
                    if (this.isOpen) {
                        dropdown.classList.add('show');
                    } else {
                        dropdown.classList.remove('show');
                    }
                }
                
                if (this.isOpen) {
                    this.$nextTick(() => {
                        const input = this.$el.querySelector('input[type="text"]');
                        if (input) input.focus();
                    });
                }
            },
            
            closeDropdown() {
                this.isOpen = false;
                this.query = '';
                this.results = { products: [], categories: [], total_results: 0 };
                
                const dropdown = this.$el.querySelector('.search-dropdown');
                if (dropdown) {
                    dropdown.classList.remove('show');
                }
                
                const input = this.$el.querySelector('input[type="text"]');
                if (input) {
                    input.value = '';
                }
                
                this.updateResultsHTML();
            },
            
            onInput(event) {
                this.query = event.target.value;
                clearTimeout(this.searchTimeout);
                
                if (this.query.length < 2) {
                    this.results = { products: [], categories: [], total_results: 0 };
                    this.updateResultsHTML();
                    return;
                }
                
                this.searchTimeout = setTimeout(() => {
                    this.performSearch();
                }, 300);
            },
            
            async performSearch() {
                if (this.query.length < 2) return;
                
                this.isLoading = true;
                console.log('🔎 Recherche Vue.js pour:', this.query);
                
                try {
                    const response = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`);
                    this.results = await response.json();
                    console.log('📊 Résultats reçus:', this.results);
                    this.updateResultsHTML();
                } catch (error) {
                    console.error('❌ Erreur de recherche:', error);
                    this.results = { products: [], categories: [], total_results: 0 };
                    this.updateResultsHTML();
                } finally {
                    this.isLoading = false;
                }
            },
            
            updateResultsHTML() {
                const resultsContainer = this.$el.querySelector('.search-results');
                if (!resultsContainer) return;
                
                let html = '';
                
                if (this.isLoading) {
                    html = '<div class="search-loading"><i class="fas fa-spinner"></i> Recherche...</div>';
                } else if (this.query.length < 2) {
                    html = '<p class="search-hint">Tapez au moins 2 caractères...</p>';
                } else if (this.results.total_results === 0) {
                    html = '<div class="search-no-results">Aucun résultat trouvé</div>';
                } else {
                    // Afficher les produits
                    if (this.results.products && this.results.products.length > 0) {
                        html += '<h4>Produits</h4>';
                        this.results.products.forEach(product => {
                            html += `
                                <div class="search-item" data-url="${product.url}" style="cursor: pointer;">
                                    <strong>${product.name}</strong>
                                    <span>${product.price}€ - ${product.category_name}</span>
                                </div>
                            `;
                        });
                    }
                    
                    // Afficher les catégories
                    if (this.results.categories && this.results.categories.length > 0) {
                        html += '<h4>Catégories</h4>';
                        this.results.categories.forEach(category => {
                            html += `
                                <div class="search-item" data-url="${category.url}" style="cursor: pointer;">
                                    <strong>${category.name}</strong>
                                    <span>${category.product_count || 0} produit(s)</span>
                                </div>
                            `;
                        });
                    }
                }
                
                resultsContainer.innerHTML = html;
                
                // Ajouter les événements de clic
                const searchItems = resultsContainer.querySelectorAll('.search-item[data-url]');
                searchItems.forEach(item => {
                    item.addEventListener('click', () => {
                        const url = item.getAttribute('data-url');
                        if (url) {
                            this.goToUrl(url);
                        }
                    });
                });
            },
            
            goToUrl(url) {
                console.log('🔗 Navigation Vue.js vers:', url);
                this.closeDropdown();
                window.location.href = url;
            },
            
            handleClickOutside(event) {
                if (!this.$el.contains(event.target)) {
                    this.closeDropdown();
                }
            }
        },
        
        mounted() {
            document.addEventListener('click', this.handleClickOutside);
            
            // Attacher les événements aux éléments existants
            const searchBtn = this.$el.querySelector('.icon-link');
            const searchInput = this.$el.querySelector('input[type="text"]');
            
            if (searchBtn) {
                searchBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.toggleDropdown();
                });
            }
            
            if (searchInput) {
                searchInput.addEventListener('input', this.onInput);
            }
            
            console.log('✅ Composant Vue de recherche monté');
        },
        
        beforeUnmount() {
            document.removeEventListener('click', this.handleClickOutside);
            clearTimeout(this.searchTimeout);
        }
    }).mount('.search-container');
});
