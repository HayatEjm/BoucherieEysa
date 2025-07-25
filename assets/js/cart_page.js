


// Gestion des boutons "Vider le panier" et modale de confirmation (extraction du inline JS)


document.addEventListener('DOMContentLoaded', function() {
    const btnClearCart = document.getElementById('btn-clear-cart');
    const modal = document.getElementById('confirm-clear-modal');
    const btnConfirm = document.getElementById('btn-confirm-clear');
    const btnCancel = document.getElementById('btn-cancel-clear');

    if (btnClearCart && modal && btnConfirm && btnCancel) {
        btnClearCart.addEventListener('click', function() {
            modal.style.display = 'block';
        });
        btnCancel.addEventListener('click', function() {
            modal.style.display = 'none';
        });
        btnConfirm.addEventListener('click', function() {
            // Logique de vidage du panier ici (AJAX ou redirection)
            modal.style.display = 'none';
            // Affichage d'un toast ou rechargement de la page si besoin
        });
    }
});
/* ========================================================================
   JS DÉDIÉ À LA PAGE PANIER (cart/index.html.twig)
   ======================================================================== */

/**
 * Ce fichier gère toutes les interactions spécifiques à la page panier :
 * - Suppression d’un article
 * - Vidage du panier (modale de confirmation)
 * - Affichage des notifications toast
 * - (Optionnel) Gestion des quantités si réactivé
 *
 * Toute logique JS spécifique à la page panier doit être placée ici, jamais inline.
 *
 * Cette approche respecte la séparation MVC et facilite la maintenance.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Gestion du bouton "Vider le panier" (optionnel, à activer si besoin)
    // ...


    // Suppression d’un article du panier (AJAX, MVC simple)
    document.querySelectorAll('.btn-remove[data-product-id]').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            fetch(`/panier/remove/${productId}`, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    window.BoucherieCart?.showNotification(data.message || data.error || 'Erreur lors de la suppression', 'error');
                }
            })
            .catch(() => {
                window.BoucherieCart?.showNotification('Erreur technique lors de la suppression', 'error');
            });
        });
    });

    // Les notifications toast sont gérées par window.BoucherieCart.showNotification(message, type)
});

// Fin du JS dédié à la page panier
