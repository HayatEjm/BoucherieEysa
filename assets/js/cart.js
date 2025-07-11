// GESTION DU PANIER - SCRIPT GLOBAL

/**
 * Je crée ce fichier pour gérer toutes les interactions liées au panier
 * sur l'ensemble du site : badge dynamique, ajout au panier, notifications, etc.
 * 
 * Ce script sera chargé sur toutes les pages pour maintenir le badge à jour
 * et permettre l'ajout d'articles depuis n'importe quelle page.
 */

// Je commence par définir les éléments et variables globales
let cartBadge = null;
let cartCount = 0;

// URLs des endpoints de l'API panier (définis par Symfony)
const CART_ENDPOINTS = {
    add: '/panier/add',         // POST /panier/add/{id}
    remove: '/panier/remove',   // POST /panier/remove/{id}
    update: '/panier/update',   // POST /panier/update/{id}
    clear: '/panier/clear',     // POST /panier/clear
    count: '/panier/count',     // GET /panier/count
    summary: '/panier/summary'  // GET /panier/summary
};

/* ========================================================================
   INITIALISATION AU CHARGEMENT DE LA PAGE
   ======================================================================== */

/**
 * Je lance l'initialisation quand le DOM est prêt
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('🛒 Initialisation du système panier...');
    
    // Je récupère les éléments du DOM
    initializeElements();
    
    // Je charge le compteur initial du panier
    loadCartCount();
    
    // J'initialise les gestionnaires d'événements
    initializeEventListeners();
    
    console.log(' Système panier initialisé avec succès');
});

/**
 * Je récupère et stocke les références aux éléments du DOM
 */
function initializeElements() {
    cartBadge = document.getElementById('cart-badge');
    
    if (!cartBadge) {
        console.warn('Badge panier non trouvé dans le DOM');
        return;
    }
    
    console.log('Éléments du panier trouvés et initialisés');
}

/* ========================================================================
   GESTION DU BADGE PANIER
   ======================================================================== */

/**
 * Je charge le nombre d'articles dans le panier depuis le serveur
 */
async function loadCartCount() {
    try {
        console.log('Chargement du compteur panier...');
        
        const response = await fetch(CART_ENDPOINTS.count, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        
        // Je mets à jour le compteur local
        cartCount = data.count || 0;
        
        // Je mets à jour l'affichage du badge
        updateBadgeDisplay();
        
        console.log(`Compteur panier chargé: ${cartCount} articles`);
        
    } catch (error) {
        console.error('❌ Erreur lors du chargement du compteur panier:', error);
        // En cas d'erreur, je garde le badge caché
        cartCount = 0;
        updateBadgeDisplay();
    }
}

/**
 * Je mets à jour l'affichage du badge avec le bon nombre et les bonnes classes
 */
function updateBadgeDisplay() {
    if (!cartBadge) return;

    console.log(`🔄 Mise à jour du badge: ${cartCount} articles`);

    // Je détermine le texte à afficher selon le nombre d'articles
    let displayText = cartCount.toString();
    let badgeClass = 'normal';

    if (cartCount === 0) {
        // Panier vide : j'anime la disparition si nécessaire
        if (!cartBadge.classList.contains('hidden')) {
            if (typeof animateBadgeDisappear === 'function') animateBadgeDisappear();
        }
        cartBadge.setAttribute('data-count', '0');
        cartBadge.setAttribute('aria-label', 'Panier vide');
        updateTooltip(0);
        return;
    } else if (cartCount >= 100) {
        // 100+ articles : j'affiche 99+
        displayText = '99+';
        badgeClass = 'lots';
    } else if (cartCount >= 10) {
        // 10+ articles : classe spéciale
        badgeClass = 'many';
    }

    // Apparition du badge si on passe de 0 à 1+
    if (cartBadge.classList.contains('hidden')) {
        if (typeof animateBadgeAppear === 'function') animateBadgeAppear();
    }

    // Je mets à jour le contenu et les attributs
    cartBadge.textContent = displayText;
    cartBadge.setAttribute('data-count', displayText);
    cartBadge.setAttribute('aria-label', `${cartCount} articles dans le panier`);

    // Je mets à jour les classes CSS
    cartBadge.className = `cart-badge ${badgeClass}`;

    // Je mets à jour le tooltip
    updateTooltip(cartCount);

    console.log(`✅ Badge mis à jour: \"${displayText}\" (classe: ${badgeClass})`);
}

/**
 * Je mets à jour le tooltip du panier
 */
function updateTooltip(count) {
    const cartContainer = cartBadge?.closest('.cart-icon-container');
    if (!cartContainer) return;
    
    let tooltipText = 'Voir mon panier';
    if (count === 0) {
        tooltipText = 'Panier vide';
    } else if (count === 1) {
        tooltipText = '1 article dans le panier';
    } else {
        tooltipText = `${count} articles dans le panier`;
    }
    
    cartContainer.setAttribute('data-tooltip', tooltipText);
}

/* ========================================================================
   ANIMATIONS DU BADGE
   ======================================================================== */

/**
 * J'anime le badge quand un article est ajouté
 */
function animateBadgeAdd() {
    if (!cartBadge || cartBadge.classList.contains('hidden')) return;
    
    console.log('🎬 Animation ajout panier');
    
    // Je retire les anciennes classes d'animation
    cartBadge.classList.remove('pulse', 'shake', 'bounce');
    
    // Je lance l'animation de pulse
    cartBadge.classList.add('pulse');
    
    // Je retire la classe après l'animation
    setTimeout(() => {
        cartBadge.classList.remove('pulse');
    }, 600);
}

/**
 * J'anime le badge quand un article est retiré
 */
function animateBadgeRemove() {
    if (!cartBadge) return;
    
    console.log('🎬 Animation retrait panier');
    
    // Je retire les anciennes classes d'animation
    cartBadge.classList.remove('pulse', 'shake', 'bounce');
    
    // Je lance l'animation de shake
    cartBadge.classList.add('shake');
    
    // Je retire la classe après l'animation
    setTimeout(() => {
        cartBadge.classList.remove('shake');
    }, 500);
}

/**
 * J'anime l'apparition du badge (panier vide → premier article)
 */
function animateBadgeAppear() {
    if (!cartBadge) return;
    
    console.log('🎬 Animation apparition badge');
    
    cartBadge.classList.remove('hidden');
    cartBadge.classList.add('appear');
    
    setTimeout(() => {
        cartBadge.classList.remove('appear');
    }, 400);
}

/**
 * J'anime la disparition du badge (dernier article retiré)
 */
function animateBadgeDisappear() {
    if (!cartBadge) return;
    
    console.log('🎬 Animation disparition badge');
    
    cartBadge.classList.add('disappear');
    
    setTimeout(() => {
        cartBadge.classList.remove('disappear');
        cartBadge.classList.add('hidden');
    }, 300);
}

/* ========================================================================
   AJOUT D'ARTICLES AU PANIER
   ======================================================================== */

/**
 * J'ajoute un article au panier via AJAX
 */
async function addToCart(productId, quantity = 1) {
    try {
        console.log(`🛒 Ajout au panier: produit ${productId}, quantité ${quantity}`);
        
        // Je prépare les données à envoyer
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        
        // J'envoie la requête AJAX
        const response = await fetch(`${CART_ENDPOINTS.add}/${productId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        if (!response.ok) {
            throw new Error(`Erreur ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            // Je mets à jour le compteur local depuis le résumé du panier
            const oldCount = cartCount;
            cartCount = data.cartSummary?.totalQuantity || 0;
            
            // J'anime le badge selon le contexte
            if (oldCount === 0 && cartCount > 0) {
                // Premier article : j'anime l'apparition
                animateBadgeAppear();
            } else {
                // Articles supplémentaires : j'anime l'ajout
                animateBadgeAdd();
            }
            
            // Je mets à jour l'affichage
            updateBadgeDisplay();
            
            // Je montre une notification de succès
            showNotification(data.message || 'Article ajouté au panier', 'success');
            
            console.log(`Article ajouté avec succès. Nouveau total: ${cartCount}`);
            
            return true;
            
        } else {
            throw new Error(data.message || 'Erreur lors de l\'ajout au panier');
        }
        
    } catch (error) {
        console.error('Erreur lors de l\'ajout au panier:', error);
        showNotification('Erreur lors de l\'ajout au panier', 'error');
        return false;
    }
}

/* ========================================================================
   NOTIFICATIONS TOAST
   ======================================================================== */

/**
 * J'affiche une notification toast
 */
function showNotification(message, type = 'info') {
    console.log(`Notification: ${message} (${type})`);
    
    // Je crée l'élément toast
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    
    // J'ajoute le toast au body
    document.body.appendChild(toast);
    
    // J'anime l'apparition
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Je retire le toast après 3 secondes
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

/* ========================================================================
   GESTIONNAIRES D'ÉVÉNEMENTS
   ======================================================================== */

/**
 * J'initialise tous les gestionnaires d'événements
 */
function initializeEventListeners() {
    console.log('Initialisation des événements panier...');
    
    // Je gère les boutons "Ajouter au panier" sur toutes les pages
    initializeAddToCartButtons();
    
    // Je gère le rafraîchissement du badge lors de changements
    initializeBadgeRefresh();
    
    console.log('Événements panier initialisés');
}

/**
 * J'initialise les boutons "Ajouter au panier" sur toutes les pages
 */
function initializeAddToCartButtons() {
    // Je cherche tous les boutons avec la classe 'add-to-cart'
    const addToCartButtons = document.querySelectorAll('.add-to-cart, [data-add-to-cart]');
    
    console.log(` ${addToCartButtons.length} boutons "Ajouter au panier" trouvés`);
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', handleAddToCartClick);
    });
}

/**
 * Je gère le clic sur un bouton "Ajouter au panier"
 */
async function handleAddToCartClick(event) {
    event.preventDefault();
    
    const button = event.currentTarget;
    
    // Je récupère l'ID du produit depuis les attributs du bouton
    const productId = button.getAttribute('data-product-id') || 
                     button.getAttribute('data-add-to-cart') ||
                     button.dataset.productId;
    let quantity = parseInt(button.getAttribute('data-quantity'), 10);
    if (isNaN(quantity) || quantity < 1) quantity = 1;

    // Vérification minWeight côté client si présent
    let minWeight = button.getAttribute('data-min-weight');
    if (minWeight !== null) {
        minWeight = parseInt(minWeight, 10);
        if (!isNaN(minWeight) && quantity < minWeight) {
            showNotification(`Quantité minimale requise : ${minWeight}`, 'error');
            return;
        }
    }

    if (!productId) {
        console.error('❌ ID de produit manquant sur le bouton');
        showNotification('Erreur : produit non identifié', 'error');
        return;
    }

    // Je désactive temporairement le bouton pour éviter les clics multiples
    const originalText = button.textContent;
    button.disabled = true;
    button.textContent = 'Ajout en cours...';

    try {
        // J'ajoute l'article au panier
        const success = await addToCart(productId, quantity);

        if (success) {
            // Je change temporairement le texte du bouton
            button.textContent = '✓ Ajouté !';
            setTimeout(() => {
                button.textContent = originalText;
                button.disabled = false;
            }, 1500);
        } else {
            // En cas d'erreur, je restaure le bouton
            button.textContent = originalText;
            button.disabled = false;
        }

    } catch (error) {
        console.error('❌ Erreur lors de l\'ajout:', error);
        showNotification('Erreur technique lors de l\'ajout au panier', 'error');
        button.textContent = originalText;
        button.disabled = false;
    }
}

/**
 * J'initialise le rafraîchissement automatique du badge
 */
function initializeBadgeRefresh() {
    // Je rafraîchis le badge quand la page reprend le focus
    // (utile si l'utilisateur a modifié le panier dans un autre onglet)
    window.addEventListener('focus', () => {
        console.log('🔄 Page active: rafraîchissement du badge panier');
        loadCartCount();
    });
    
 
}

/* ========================================================================
   API PUBLIQUE POUR L'UTILISATION SUR D'AUTRES PAGES
   ======================================================================== */

/**
 * J'expose une API publique pour que d'autres scripts puissent
 * interagir avec le système panier
 */
window.BoucherieCart = {
    // Fonctions principales
    addToCart: addToCart,
    loadCartCount: loadCartCount,
    
    // Gestion du badge
    updateBadgeDisplay: updateBadgeDisplay,
    animateBadgeAdd: animateBadgeAdd,
    animateBadgeRemove: animateBadgeRemove,
    
    // Notifications
    showNotification: showNotification,
    
    // Propriétés en lecture seule
    get cartCount() { return cartCount; },
    get cartBadge() { return cartBadge; }
};

/* ========================================================================
   FONCTION D'AIDE POUR LE DEBUGGING
   ======================================================================== */

/**
 * Fonction de debug accessible depuis la console du navigateur
 */
window.debugCart = function() {
    console.log('🛒 Debug du système panier:');
    console.log('- Compteur actuel:', cartCount);
    console.log('- Badge DOM:', cartBadge);
    console.log('- Endpoints:', CART_ENDPOINTS);
    console.log('- API publique:', window.BoucherieCart);
};

console.log('🎯 Script panier global chargé. Utilisez debugCart() pour le debug.');
