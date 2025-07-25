// GESTION DU PANIER - SCRIPT GLOBAL

/**
 * Ce fichier gère toutes les interactions liées au panier :
 * - Badge dynamique dans le header
 * - Chargement du compteur initial
 * - Ajout d’articles (AJAX)
 * - Notifications toast
 * - Réinitialisation et mise à jour du badge
 * 
 * Il est chargé sur toutes les pages via app.js.
 */

// Références globales
let cartBadge = null;
let cartCount = 0;

// Endpoints de l’API panier définis côté Symfony
const CART_ENDPOINTS = {
    add:     '/panier/add',       // POST /panier/add/{id}
    remove:  '/panier/remove',    // POST /panier/remove/{id}
    update:  '/panier/update',    // POST /panier/update/{id}
    clear:   '/panier/clear',     // POST /panier/clear
    count:   '/panier/count',     // GET  /panier/count
    summary: '/panier/summary'    // GET  /panier/summary
};


/* ========================================================================
   INITIALISATION DU PANIER
   ======================================================================== */

function initCart() {
    console.log('🛒 Initialisation du système panier...');
    initializeElements();
    loadCartCount();
    initializeEventListeners();
    console.log('✅ Système panier initialisé avec succès');
}

// Si le DOM est déjà prêt, on init tout de suite, sinon on attend DOMContentLoaded
if (document.readyState !== 'loading') {
    initCart();
} else {
    document.addEventListener('DOMContentLoaded', initCart);
}


/* ========================================================================
   RÉCUPÉRATION DES ÉLÉMENTS DU DOM
   ======================================================================== */

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

async function loadCartCount() {
    try {
        console.log('Chargement du compteur panier...');
        const response = await fetch(CART_ENDPOINTS.count, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!response.ok) throw new Error(`Erreur ${response.status}`);
        const data = await response.json();
        cartCount = data.count || 0;
        updateBadgeDisplay();
        console.log(`Compteur panier chargé: ${cartCount} articles`);
    } catch (err) {
        console.error('❌ Erreur chargement compteur panier:', err);
        cartCount = 0;
        updateBadgeDisplay();
    }
}

function updateBadgeDisplay() {
    if (!cartBadge) return;
    let text = cartCount > 99 ? '99+' : cartCount.toString();
    let cls  = cartCount >= 100 ? 'lots'
             : cartCount >= 10  ? 'many'
             : cartCount === 0  ? 'normal'
             : 'normal';
    cartBadge.textContent = text;
    cartBadge.setAttribute('data-count', text);
    cartBadge.setAttribute('aria-label', cartCount === 0
        ? 'Panier vide'
        : `${cartCount} article${cartCount>1?'s':''} dans le panier`
    );
    cartBadge.className = `cart-badge ${cls}`;
    updateTooltip(cartCount);
}

function updateTooltip(count) {
    const container = cartBadge?.closest('.cart-icon-container');
    if (!container) return;
    let tip = count === 0
        ? 'Panier vide'
        : count === 1
          ? '1 article dans le panier'
          : `${count} articles dans le panier`;
    container.setAttribute('data-tooltip', tip);
}


/* ========================================================================
   ANIMATIONS DU BADGE
   ======================================================================== */

function animateBadgeAppear() { /* ... inchangé ... */ }
function animateBadgeDisappear() { /* ... inchangé ... */ }
function animateBadgeAdd() { /* ... inchangé ... */ }
function animateBadgeRemove() { /* ... inchangé ... */ }


/* ========================================================================
   AJOUT D’ARTICLES AU PANIER
   ======================================================================== */

/**
 * Envoie une requête AJAX pour ajouter un produit.
 */
async function addToCart(productId, quantity = 1) {
    try {
        console.log(`🛒 Ajout au panier: produit ${productId}, quantité ${quantity}`);

        // --- MODIFICATION : utilisation de FormData pour que Symfony reçoive bien 'quantity'
        const form = new FormData();
        form.append('quantity', quantity);

        const resp = await fetch(`${CART_ENDPOINTS.add}/${productId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: form
        });

        // Si la réponse n'est pas OK, on tente de lire le JSON d'erreur
        if (!resp.ok) {
            let errMsg = `Erreur ${resp.status}`;
            try {
                const errData = await resp.json();
                if (errData.error) errMsg = errData.error;
            } catch (e) {
                // Pas de JSON valide, on conserve errMsg initial
            }
            throw new Error(errMsg);
        }

        // Réponse OK, on parse le JSON de succès
        const data = await resp.json();
        if (!data.success) throw new Error(data.message || 'Erreur ajout panier');

        // Mise à jour locale du badge
        const oldCount = cartCount;
        cartCount = data.cartSummary.totalQuantity || 0;
        oldCount === 0 ? animateBadgeAppear() : animateBadgeAdd();
        updateBadgeDisplay();
        showNotification(data.message || 'Article ajouté', 'success');
        return true;

    } catch (err) {
        console.error('❌ Erreur ajout panier:', err);
        showNotification(err.message || 'Erreur ajout panier', 'error');
        return false;
    }
}


/* ========================================================================
   NOTIFICATIONS TOAST
   ======================================================================== */

function showNotification(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}


/* ========================================================================
   GESTIONNAIRES D’ÉVÉNEMENTS
   ======================================================================== */

function initializeEventListeners() {
    console.log('Initialisation des événements panier...');
    initializeAddToCartButtons();
    initializeBadgeRefresh();
}

function initializeAddToCartButtons() {
    const btns = document.querySelectorAll('.add-to-cart');
    console.log(`🔍 ${btns.length} boutons "Ajouter au panier" trouvés`);
    btns.forEach(btn => btn.addEventListener('click', handleAddToCartClick));
}

async function handleAddToCartClick(event) {
    event.preventDefault();
    const btn = event.currentTarget;
    const pid = btn.dataset.productId;
    const qty = parseInt(btn.dataset.quantity) || 1;
    btn.disabled = true; 
    btn.textContent = 'Ajout…';
    await addToCart(pid, qty);
    setTimeout(() => {
        btn.disabled = false;
        btn.textContent = 'Ajouter au panier';
    }, 1500);
}

function initializeBadgeRefresh() {
    window.addEventListener('focus', loadCartCount);
}


/* ========================================================================
   API PUBLIQUE POUR AUTRES SCRIPTS
   ======================================================================== */
window.BoucherieCart = {
    addToCart,
    loadCartCount,
    updateBadgeDisplay,
    animateBadgeAdd,
    animateBadgeRemove,
    showNotification,
    get cartCount() { return cartCount; },
    get cartBadge() { return cartBadge; }
};

console.log('🎯 Script panier global chargé. debugCart() disponible.');
