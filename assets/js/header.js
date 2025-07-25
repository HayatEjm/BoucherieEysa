// document.addEventListener('DOMContentLoaded', function() {
    
//     console.log('🔍 DÉBUT - Initialisation du menu déroulant...');
    
//     // Récupérer les éléments du DOM
//     const dropdown = document.querySelector('.dropdown');
//     const dropdownToggle = dropdown.querySelector('.dropbtn');  // Ciblage du bouton
//     const dropdownMenu = dropdown.querySelector('.dropdown-content');  // Ciblage du menu
//     const dropdownContainer = dropdown;  // Utilisation de la classe parent (dropdown)

//     console.log('🔍 dropdownToggle trouvé:', !!dropdownToggle);
//     console.log('🔍 dropdownMenu trouvé:', !!dropdownMenu);
    
//     // Vérification que les éléments existent
//     if (!dropdownToggle || !dropdownMenu) {
//         console.log('❌ Menu déroulant non trouvé - éléments manquants');
//         return;
//     }
    
//     console.log('✅ Tous les éléments trouvés, ajout des événements...');
    
//     // Gérer le clic sur "NOS PRODUITS"
//     dropdownToggle.addEventListener('click', function(event) {
//         console.log('🖱️ CLIC DÉTECTÉ sur NOS PRODUITS !');
        
//         // Empêcher le comportement par défaut du lien
//         event.preventDefault();
        
//         // Toggle : ouvrir si fermé, fermer si ouvert
//         const isOpen = dropdownMenu.classList.contains('show');
//         console.log('📋 Menu actuellement ouvert:', isOpen);
        
//         if (isOpen) {
//             console.log('🔄 Fermeture du menu...');
//             closeDropdown();
//         } else {
//             console.log('🔄 Ouverture du menu...');
//             openDropdown();
//         }
//     });
    
//     // Fermer le menu si on clique ailleurs
//     document.addEventListener('click', function(event) {
//         const isClickInsideDropdown = dropdownContainer.contains(event.target);
        
//         if (!isClickInsideDropdown && dropdownMenu.classList.contains('show')) {
//             console.log('🖱️ Clic extérieur détecté, fermeture du menu...');
//             closeDropdown();
//         }
//     });
    
//     // Fonctions utilitaires
//     function openDropdown() {
//         console.log('🟢 Fonction openDropdown() exécutée');
//         dropdownMenu.classList.add('show');
//         dropdownToggle.setAttribute('aria-expanded', 'true');
//         console.log('🟢 Classe "show" ajoutée au menu');
//     }
    
//     function closeDropdown() {
//         console.log('🔴 Fonction closeDropdown() exécutée');
//         dropdownMenu.classList.remove('show');
//         dropdownToggle.setAttribute('aria-expanded', 'false');
//         console.log('🔴 Classe "show" retirée du menu');
//     }
    
//     // Bonus : Gestion des touches clavier (accessibilité)
//     dropdownToggle.addEventListener('keydown', function(event) {
//         // Ouvrir/fermer avec Entrée ou Espace
//         if (event.key === 'Enter' || event.key === ' ') {
//             event.preventDefault();
//             dropdownToggle.click();
//         }
        
//         // Fermer avec Échap
//         if (event.key === 'Escape') {
//             closeDropdown();
//         }
//     });
    
//     console.log('✅ Menu déroulant produits initialisé avec succès');
// });
