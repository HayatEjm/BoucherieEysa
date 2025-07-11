// Version simple et robuste de la recherche
// Remplace temporairement le contenu de search.js si nécessaire

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Initialisation de la recherche simple...');
    
    const searchBtn = document.getElementById('searchToggle');
    const dropdown = document.getElementById('searchDropdown');
    const searchInput = document.getElementById('searchInput');
    const resultsDiv = document.getElementById('searchResults');
    
    if (!searchBtn || !dropdown || !searchInput || !resultsDiv) {
        console.error('❌ Éléments manquants pour la recherche');
        return;
    }
    
    console.log('✅ Tous les éléments trouvés');
    
    // Ouvrir/fermer dropdown
    searchBtn.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('🔍 Ouverture du dropdown');
        dropdown.classList.toggle('show');
        if (dropdown.classList.contains('show')) {
            searchInput.focus();
        }
    });
    
    // Fermer en cliquant ailleurs
    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target) && !searchBtn.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
    
    // Recherche simple
    let timeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            resultsDiv.innerHTML = '<p class="search-hint">Tapez au moins 2 caractères...</p>';
            return;
        }
        
        timeout = setTimeout(function() {
            console.log('🔎 Recherche pour:', query);
            search(query);
        }, 300);
    });
    
    async function search(query) {
        try {
            const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            console.log('📊 Données reçues:', data);
            showResults(data);
        } catch (error) {
            console.error('❌ Erreur de recherche:', error);
            resultsDiv.innerHTML = '<div class="search-error">Erreur de recherche</div>';
        }
    }
    
    function showResults(data) {
        if (data.total_results === 0) {
            resultsDiv.innerHTML = '<div class="search-no-results">Aucun résultat</div>';
            return;
        }
        
        let html = '';
        
        // Produits
        if (data.products && data.products.length > 0) {
            html += '<h4>Produits</h4>';
            data.products.forEach(function(product) {
                html += `
                    <div class="search-item" data-url="${product.url}" style="cursor: pointer;">
                        <strong>${product.name}</strong>
                        <span>${product.price}€ - ${product.category_name}</span>
                    </div>
                `;
            });
        }
        
        // Catégories
        if (data.categories && data.categories.length > 0) {
            html += '<h4>Catégories</h4>';
            data.categories.forEach(function(category) {
                html += `
                    <div class="search-item" data-url="${category.url}" style="cursor: pointer;">
                        <strong>${category.name}</strong>
                        <span>${category.product_count || 0} produits</span>
                    </div>
                `;
            });
        }
        
        resultsDiv.innerHTML = html;
        
        // Ajouter les événements de clic - MÉTHODE SIMPLE
        const items = resultsDiv.querySelectorAll('.search-item[data-url]');
        console.log('🎯 Ajout des clics sur', items.length, 'éléments');
        
        items.forEach(function(item) {
            item.onclick = function() {
                const url = this.getAttribute('data-url');
                console.log('🔗 Clic détecté - Redirection vers:', url);
                window.location.href = url;
            };
        });
    }
});
