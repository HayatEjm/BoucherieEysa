/* ========================================================================
   JS DÉDIÉ À LA FICHE PRODUIT (product_detail.html.twig)
   ======================================================================== */

/**
 * Ce fichier regroupe toutes les interactions JavaScript spécifiques à la page
de détail produit. Il est importé uniquement sur cette page pour garantir une
séparation claire des responsabilités (MVC) et éviter tout JS inline dans le template.
 *
 * Placez ici les scripts liés à la gestion du poids minimum, de l’ajout au panier,
 * ou toute interaction propre à la fiche produit.
 *
 * Exemple d’utilisation pédagogique :
 * - Gestion du bouton "Ajouter au panier" avec minWeight
 * - Affichage dynamique d’informations produit
 */

// Gestion complète des boutons +, -, suggestions, et ajout au panier avec quantité
document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.getElementById('quantity-input');
    const btnPlus = document.querySelector('.qty-plus');
    const btnMinus = document.querySelector('.qty-minus');
    const suggestionBtns = document.querySelectorAll('.suggestion-btn');
    const addToCartBtn = document.querySelector('.add-to-cart, [data-add-to-cart]');

    if (!qtyInput || !addToCartBtn) return;

    const min = parseInt(qtyInput.getAttribute('min'), 10) || 1;
    const max = parseInt(qtyInput.getAttribute('max'), 10) || 5000;
    const step = parseInt(qtyInput.getAttribute('step'), 10) || 100;

    // Bouton +
    if (btnPlus) {
        btnPlus.addEventListener('click', function() {
            let val = parseInt(qtyInput.value, 10) || min;
            val = Math.min(val + step, max);
            qtyInput.value = val;
            qtyInput.dispatchEvent(new Event('input'));
        });
    }

    // Bouton -
    if (btnMinus) {
        btnMinus.addEventListener('click', function() {
            let val = parseInt(qtyInput.value, 10) || min;
            val = Math.max(val - step, min);
            qtyInput.value = val;
            qtyInput.dispatchEvent(new Event('input'));
        });
    }

    // Suggestions
    suggestionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const val = parseInt(this.getAttribute('data-qty'), 10);
            if (!isNaN(val)) {
                qtyInput.value = Math.max(min, Math.min(val, max));
                qtyInput.dispatchEvent(new Event('input'));
            }
        });
    });

    // Ajout au panier : lit la valeur de l'input
    addToCartBtn.addEventListener('click', function(e) {
        const quantity = parseInt(qtyInput.value, 10) || min;
        if (quantity < min) {
            window.BoucherieCart?.showNotification(`Quantité minimale requise : ${min}`, 'error');
            e.preventDefault();
            return;
        }
        if (window.BoucherieCart && typeof window.BoucherieCart.addToCart === 'function') {
            e.preventDefault();
            const productId = addToCartBtn.getAttribute('data-product-id');
            window.BoucherieCart.addToCart(productId, quantity);
        }
    });

    // Mise à jour dynamique du prix total
    qtyInput.addEventListener('input', function() {
        const price = parseFloat(qtyInput.getAttribute('data-price')) || 0;
        const val = parseInt(qtyInput.value, 10) || min;
        const total = (val / 1000) * price;
        const priceDisplay = document.getElementById('total-price');
        if (priceDisplay) {
            priceDisplay.textContent = total.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '€';
        }
    });
});

// ========================================================================
// Fin du JS dédié à la fiche produit
// ========================================================================
