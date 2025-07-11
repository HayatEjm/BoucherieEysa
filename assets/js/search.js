// ========================================================================
// MODAL DE RECHERCHE - JavaScript pur (sans Vue.js pour simplifier)
// ========================================================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Search: DOM loaded, initializing search...');
    
    const searchToggle = document.getElementById('searchToggle');
    const searchModal = document.getElementById('searchModal');
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const searchClose = document.querySelector('.search-close');
    
    console.log('🔍 Search elements found:');
    console.log('  - searchToggle:', searchToggle);
    console.log('  - searchModal:', searchModal);
    console.log('  - searchInput:', searchInput);
    console.log('  - searchResults:', searchResults);
    console.log('  - searchClose:', searchClose);
    
    let searchTimeout;

    // Vérifier que tous les éléments existent
    if (!searchToggle || !searchModal || !searchInput || !searchResults) {
        console.error('🔍 Search: Missing required elements, search functionality disabled');
        return;
    }

    console.log('🔍 Search: All elements found, setting up event listeners...');

    // Ouvrir la modal
    searchToggle.addEventListener('click', function(e) {
        console.log('🔍 Search: Toggle clicked!');
        e.preventDefault();
        openModal();
    });

    // Fermer la modal avec le bouton X
    if (searchClose) {
        searchClose.addEventListener('click', closeModal);
    }

    // Fermer la modal en cliquant sur l'overlay
    searchModal.addEventListener('click', function(e) {
        if (e.target === searchModal) {
            closeModal();
        }
    });

    // Fermer avec la touche Échap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isModalOpen()) {
            closeModal();
        }
    });

    // Recherche en temps réel
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            showEmptyState();
            return;
        }

        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    function openModal() {
        console.log('🔍 Search: Opening modal...');
        searchModal.style.display = 'flex';
        setTimeout(() => {
            searchInput.focus();
            console.log('🔍 Search: Modal opened and input focused');
        }, 100);
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        searchModal.style.display = 'none';
        searchInput.value = '';
        showEmptyState();
        document.body.style.overflow = '';
    }

    function isModalOpen() {
        return searchModal.style.display === 'flex';
    }

    function showEmptyState() {
        searchResults.innerHTML = `
            <div class="search-empty">
                <i class="fas fa-search"></i>
                <p>Commencez à taper pour rechercher...</p>
            </div>
        `;
    }

    function showLoading() {
        searchResults.innerHTML = `
            <div class="search-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Recherche en cours...</p>
            </div>
        `;
    }

    function showNoResults(query) {
        searchResults.innerHTML = `
            <div class="search-no-results">
                <i class="fas fa-search"></i>
                <p>Aucun résultat pour "${escapeHtml(query)}"</p>
            </div>
        `;
    }

    async function performSearch(query) {
        showLoading();

        try {
            const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
            
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            
            const data = await response.json();
            displayResults(data, query);
            
        } catch (error) {
            console.error('Erreur de recherche:', error);
            searchResults.innerHTML = `
                <div class="search-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Erreur lors de la recherche</p>
                </div>
            `;
        }
    }

    function displayResults(data, query) {
        if (data.total_results === 0) {
            showNoResults(query);
            return;
        }

        let html = '<div class="search-results-content">';

        // Affichage des catégories
        if (data.categories && data.categories.length > 0) {
            html += '<div class="search-section">';
            html += '<h4><i class="fas fa-tags"></i> Catégories</h4>';
            data.categories.forEach(category => {
                html += `
                    <div class="search-item category-item" onclick="navigateToCategory('${category.url}')">
                        <div class="search-item-content">
                            <h5>${highlightText(category.name, query)}</h5>
                            <p>${category.product_count} produit(s)</p>
                        </div>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                `;
            });
            html += '</div>';
        }

        // Affichage des produits
        if (data.products && data.products.length > 0) {
            html += '<div class="search-section">';
            html += '<h4><i class="fas fa-shopping-basket"></i> Produits</h4>';
            data.products.forEach(product => {
                const price = product.price ? `${product.price}€` : 'Prix sur demande';
                html += `
                    <div class="search-item product-item" onclick="navigateToProduct('${product.url}')">
                        <div class="search-item-content">
                            <h5>${highlightText(product.name, query)}</h5>
                            <p class="search-price">${price}</p>
                            ${product.category ? `<span class="search-category">${product.category}</span>` : ''}
                        </div>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                `;
            });
            html += '</div>';
        }

        html += '</div>';
        searchResults.innerHTML = html;
    }

    function highlightText(text, query) {
        if (!query) return escapeHtml(text);
        const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
        return escapeHtml(text).replace(regex, '<mark>$1</mark>');
    }

    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Fonctions globales pour la navigation (appelées depuis les clics)
    window.navigateToProduct = function(url) {
        window.location.href = url;
    };

    window.navigateToCategory = function(url) {
        window.location.href = url;
    };

    // Initialiser l'état vide
    showEmptyState();
});
