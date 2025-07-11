/**
 * 🥩 MENU DÉROULANT PRODUITS - JavaScript simple et pédagogique
 * 
 * Ce fichier gère l'ouverture/fermeture du menu "Nos produits" au clic
 * Écrit de manière simple pour une développeuse débutante
 */

// ✅ Attendre que la page soit complètement chargée
document.addEventListener('DOMContentLoaded', function() {
    
    console.log('🔍 DÉBUT - Initialisation du menu déroulant...');
    
    // 📝 ÉTAPE 1 : Récupérer les éléments du DOM
    const dropdownToggle = document.querySelector('.dropdown-toggle[data-dropdown="products-menu"]');
    const dropdownMenu = document.getElementById('products-menu');
    const dropdownContainer = document.querySelector('.dropdown-container');
    
    console.log('🔍 dropdownToggle trouvé:', !!dropdownToggle);
    console.log('🔍 dropdownMenu trouvé:', !!dropdownMenu);
    console.log('🔍 dropdownContainer trouvé:', !!dropdownContainer);
    
    // 🔍 VÉRIFICATION : S'assurer que les éléments existent
    if (!dropdownToggle || !dropdownMenu) {
        console.log('❌ Menu déroulant non trouvé - éléments manquants');
        console.log('   - dropdownToggle:', dropdownToggle);
        console.log('   - dropdownMenu:', dropdownMenu);
        return;
    }
    
    console.log('✅ Tous les éléments trouvés, ajout des événements...');
    
    // 📝 ÉTAPE 2 : Gérer le clic sur "NOS PRODUITS"
    dropdownToggle.addEventListener('click', function(event) {
        console.log('🖱️ CLIC DÉTECTÉ sur NOS PRODUITS !');
        
        // Empêcher le comportement par défaut du lien
        event.preventDefault();
        
        // Toggle : ouvrir si fermé, fermer si ouvert
        const isOpen = dropdownMenu.classList.contains('show');
        console.log('📋 Menu actuellement ouvert:', isOpen);
        
        if (isOpen) {
            console.log('🔄 Fermeture du menu...');
            closeDropdown();
        } else {
            console.log('🔄 Ouverture du menu...');
            openDropdown();
        }
    });
    
    // 📝 ÉTAPE 3 : Fermer le menu si on clique ailleurs
    document.addEventListener('click', function(event) {
        // Vérifier si le clic est en dehors du menu
        const isClickInsideDropdown = dropdownContainer.contains(event.target);
        
        if (!isClickInsideDropdown && dropdownMenu.classList.contains('show')) {
            console.log('🖱️ Clic extérieur détecté, fermeture du menu...');
            closeDropdown();
        }
    });
    
    // 📝 FONCTIONS UTILITAIRES
    
    /**
     * Ouvre le menu déroulant avec animation
     */
    function openDropdown() {
        console.log('🟢 Fonction openDropdown() exécutée');
        dropdownMenu.classList.add('show');
        dropdownToggle.setAttribute('aria-expanded', 'true');
        console.log('🟢 Classe "show" ajoutée au menu');
    }
    
    /**
     * Ferme le menu déroulant
     */
    function closeDropdown() {
        console.log('🔴 Fonction closeDropdown() exécutée');
        dropdownMenu.classList.remove('show');
        dropdownToggle.setAttribute('aria-expanded', 'false');
        console.log('🔴 Classe "show" retirée du menu');
    }
    
    // 📝 BONUS : Gestion des touches clavier (accessibilité)
    dropdownToggle.addEventListener('keydown', function(event) {
        // Ouvrir/fermer avec Entrée ou Espace
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            dropdownToggle.click();
        }
        
        // Fermer avec Échap
        if (event.key === 'Escape') {
            closeDropdown();
        }
    });
    
    console.log('✅ Menu déroulant produits initialisé avec succès');
});
