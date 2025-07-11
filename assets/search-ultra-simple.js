// // Recherche ultra-simple - AUCUNE COMPLICATION
// console.log('🔍 Chargement du script de recherche simple...');

// document.addEventListener('DOMContentLoaded', function() {
//     console.log('🔍 DOM chargé, initialisation de la recherche...');
    
//     // Récupération sécurisée des éléments
//     const searchBtn = document.getElementById('searchToggle');
//     const dropdown = document.getElementById('searchDropdown');
//     const searchInput = document.getElementById('searchInput');
//     const resultsDiv = document.getElementById('searchResults');
    
//     // Vérification que TOUS les éléments existent
//     if (!searchBtn) {
//         console.error('❌ searchToggle non trouvé');
//         return;
//     }
//     if (!dropdown) {
//         console.error('❌ searchDropdown non trouvé');
//         return;
//     }
//     if (!searchInput) {
//         console.error('❌ searchInput non trouvé');
//         return;
//     }
//     if (!resultsDiv) {
//         console.error('❌ searchResults non trouvé');
//         return;
//     }
    
//     console.log('✅ Tous les éléments trouvés');
    
//     let searchTimeout;
    
//     // 1. CLIC SUR LA LOUPE
//     searchBtn.onclick = function(e) {
//         e.preventDefault();
//         console.log('👆 Clic sur la loupe');
        
//         if (dropdown.classList.contains('show')) {
//             dropdown.classList.remove('show');
//             console.log('🔒 Dropdown fermé');
//         } else {
//             dropdown.classList.add('show');
//             searchInput.focus();
//             console.log('🔓 Dropdown ouvert');
//         }
//     };
    
//     // 2. FERMER EN CLIQUANT AILLEURS
//     document.onclick = function(e) {
//         // Vérifier que les éléments existent toujours
//         if (!dropdown || !searchBtn) return;
        
//         // Si on clique en dehors du dropdown ET en dehors du bouton
//         if (!dropdown.contains(e.target) && !searchBtn.contains(e.target)) {
//             dropdown.classList.remove('show');
//             console.log('🔒 Dropdown fermé (clic extérieur)');
//         }
//     };
    
//     // 3. RECHERCHE
//     searchInput.oninput = function() {
//         const query = this.value.trim();
//         console.log('⌨️ Saisie:', query);
        
//         clearTimeout(searchTimeout);
        
//         if (query.length < 2) {
//             resultsDiv.innerHTML = '<p class="search-hint">Tapez au moins 2 caractères...</p>';
//             return;
//         }
        
//         searchTimeout = setTimeout(function() {
//             performSearch(query);
//         }, 300);
//     };
    
//     // 4. FONCTION DE RECHERCHE
//     function performSearch(query) {
//         console.log('🔎 Recherche pour:', query);
//         resultsDiv.innerHTML = '<div class="search-loading"><i class="fas fa-spinner"></i> Recherche...</div>';
        
//         fetch('/api/search?q=' + encodeURIComponent(query))
//             .then(function(response) {
//                 return response.json();
//             })
//             .then(function(data) {
//                 console.log('📊 Données reçues:', data);
//                 showResults(data);
//             })
//             .catch(function(error) {
//                 console.error('❌ Erreur:', error);
//                 resultsDiv.innerHTML = '<div class="search-error">Erreur de recherche</div>';
//             });
//     }
    
//     // 5. AFFICHAGE DES RÉSULTATS
//     function showResults(data) {
//         if (data.total_results === 0) {
//             resultsDiv.innerHTML = '<div class="search-no-results">Aucun résultat trouvé</div>';
//             return;
//         }
        
//         let html = '';
        
//         // Produits
//         if (data.products && data.products.length > 0) {
//             html += '<h4>Produits</h4>';
//             for (let i = 0; i < data.products.length; i++) {
//                 const product = data.products[i];
//                 html += '<div class="search-item" data-url="' + product.url + '" onclick="goTo(\'' + product.url + '\')" style="cursor: pointer;">';
//                 html += '<strong>' + product.name + '</strong>';
//                 html += '<span>' + product.price + '€ - ' + product.category_name + '</span>';
//                 html += '</div>';
//             }
//         }
        
//         // Catégories
//         if (data.categories && data.categories.length > 0) {
//             html += '<h4>Catégories</h4>';
//             for (let i = 0; i < data.categories.length; i++) {
//                 const category = data.categories[i];
//                 html += '<div class="search-item" data-url="' + category.url + '" onclick="goTo(\'' + category.url + '\')" style="cursor: pointer;">';
//                 html += '<strong>' + category.name + '</strong>';
//                 html += '<span>' + (category.product_count || 0) + ' produit(s)</span>';
//                 html += '</div>';
//             }
//         }
        
//         resultsDiv.innerHTML = html;
//         console.log('Résultats affichés avec onclick');
//     }
    
//     console.log('Recherche initialisée avec succès');
// });

// // 6. FONCTION DE NAVIGATION GLOBALE
// function goTo(url) {
//     console.log('Navigation vers:', url);
    
//     // Fermer le dropdown
//     const dropdown = document.getElementById('searchDropdown');
//     if (dropdown) {
//         dropdown.classList.remove('show');
//     }
    
//     // Naviguer
//     window.location.href = url;
// }

// // Rendre la fonction disponible globalement
// window.goTo = goTo;
