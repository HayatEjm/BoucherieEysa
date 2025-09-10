// assets/js/header.js
// Gestion du header et de la navigation

// Fonctions pour le header (menu mobile, etc)

// Gestion du menu mobile (si nécessaire)
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    
    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
        });
    }
    
    // Fermer le menu mobile quand on clique à l'extérieur
    document.addEventListener('click', function(event) {
        if (mobileMenu && 
            !mobileMenu.contains(event.target) && 
            !mobileMenuToggle.contains(event.target)) {
            mobileMenu.classList.remove('active');
        }
    });
    
    // Gestion du menu déroulant desktop si nécessaire
    const dropdownTriggers = document.querySelectorAll('.dropdown-trigger');
    dropdownTriggers.forEach(trigger => {
        const dropdown = trigger.nextElementSibling;
        
        if (dropdown && dropdown.classList.contains('dropdown-menu')) {
            trigger.addEventListener('mouseenter', function() {
                dropdown.style.display = 'block';
            });
            
            trigger.addEventListener('mouseleave', function() {
                setTimeout(() => {
                    if (!dropdown.matches(':hover')) {
                        dropdown.style.display = 'none';
                    }
                }, 100);
            });
            
            dropdown.addEventListener('mouseleave', function() {
                dropdown.style.display = 'none';
            });
        }
    });
});
