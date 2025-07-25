// // Système de recherche moderne avec dropdown
// console.log('🔍 Initialisation de la recherche...');

// document.addEventListener('DOMContentLoaded', function() {
    
//     // Récupération sécurisée des éléments
//     const searchBtn = document.getElementById('searchToggle');
//     const dropdown = document.getElementById('searchDropdown');
//     const searchInput = document.getElementById('searchInput');
//     const resultsDiv = document.getElementById('searchResults');
    
//     // Vérification que tous les éléments existent
//     if (!searchBtn || !dropdown || !searchInput || !resultsDiv) {
//         console.error('Éléments de recherche manquants');
//         return;
//     }
    
//     let searchTimeout;
    
//     // Ouverture/fermeture du dropdown
//     searchBtn.onclick = function(e) {
//         e.preventDefault();
        
//         if (dropdown.classList.contains('show')) {
//             dropdown.classList.remove('show');
//         } else {
//             dropdown.classList.add('show');
//             searchInput.focus();
//         }
//     };
    
//     // Fermer en cliquant ailleurs
//     document.onclick = function(e) {
//         if (!dropdown || !searchBtn) return;
        
//         if (!dropdown.contains(e.target) && !searchBtn.contains(e.target)) {
//             dropdown.classList.remove('show');
//         }
//     };
    
//     // Recherche en temps réel
//     searchInput.oninput = function() {
//         const query = this.value.trim();
        
//         clearTimeout(searchTimeout);
        
//         if (query.length < 2) {
//             resultsDiv.innerHTML = '<p class="search-hint">Tapez au moins 2 caractères...</p>';
//             return;
//         }
        
//         searchTimeout = setTimeout(function() {
//             performSearch(query);
//         }, 300);
//     };
    
//     // Fonction de recherche
//     function performSearch(query) {
//         resultsDiv.innerHTML = '<div class="search-loading"><i class="fas fa-spinner"></i> Recherche...</div>';
        
//         fetch('/api/search?q=' + encodeURIComponent(query))
//             .then(function(response) {
//                 return response.json();
//             })
//             .then(function(data) {
//                 showResults(data);
//             })
//             .catch(function(error) {
//                 console.error('Erreur de recherche:', error);
//                 resultsDiv.innerHTML = '<div class="search-error">Erreur de recherche</div>';
//             });
//     }
    
//     // Affichage des résultats
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
//     }
    
//     console.log(' Recherche initialisée');
// });

// // Fonction de navigation globale
// function goTo(url) {
//     const dropdown = document.getElementById('searchDropdown');
//     if (dropdown) {
//         dropdown.classList.remove('show');
//     }
//     window.location.href = url;
// }

// // Rendre la fonction disponible globalement
// window.goTo = goTo;
