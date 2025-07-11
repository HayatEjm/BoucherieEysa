// Test simple de navigation - Copiez-collez dans la console du navigateur

// Test 1: Vérifier si les éléments existent
console.log('=== Test des éléments DOM ===');
console.log('searchToggle:', document.getElementById('searchToggle'));
console.log('searchDropdown:', document.getElementById('searchDropdown'));
console.log('searchInput:', document.getElementById('searchInput'));
console.log('searchResults:', document.getElementById('searchResults'));

// Test 2: Simuler un clic sur la loupe
console.log('\n=== Test ouverture du dropdown ===');
const searchBtn = document.getElementById('searchToggle');
if (searchBtn) {
    searchBtn.click();
    console.log('Clic sur la loupe effectué');
} else {
    console.error('Bouton de recherche non trouvé');
}

// Test 3: Simuler une recherche
console.log('\n=== Test de recherche ===');
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.value = 'boeuf';
    searchInput.dispatchEvent(new Event('input'));
    console.log('Recherche "boeuf" déclenchée');
    
    // Attendre 1 seconde puis vérifier les résultats
    setTimeout(() => {
        const results = document.querySelectorAll('.search-item[data-url]');
        console.log('Nombre de résultats:', results.length);
        results.forEach((item, index) => {
            console.log(`Résultat ${index}:`, item.querySelector('strong').textContent, 'URL:', item.getAttribute('data-url'));
        });
        
        // Test de clic sur le premier résultat
        if (results.length > 0) {
            console.log('\n=== Test de clic sur le premier résultat ===');
            console.log('Avant clic - URL actuelle:', window.location.href);
            results[0].click();
        }
    }, 1000);
} else {
    console.error('Champ de recherche non trouvé');
}
