// Gestion des sélecteurs de quantité avec validation minWeight/maxWeight (extraction du inline JS)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.quantity-input').forEach(input => {
        const productId = input.dataset.productId;
        const minWeight = parseInt(input.dataset.minGrams);
        const maxWeight = parseInt(input.dataset.maxGrams);
        // Ajoutez ici la logique de validation et d'incrémentation
    });
});
/* ========================================================================
   JS DÉDIÉ À LA PAGE CATÉGORIE PRODUITS (category_products.html.twig)
   ======================================================================== */

document.addEventListener('DOMContentLoaded', function() {
    // Gestion des sélecteurs de quantité avec validation du minWeight
    document.querySelectorAll('.quantity-input').forEach(input => {
        const productId = input.dataset.productId;
        const minWeight = parseInt(input.dataset.minGrams);
        const maxWeight = parseInt(input.dataset.maxGrams);
        var productCard = input.closest('.product-card');
        var price = productCard ? parseFloat(productCard.querySelector('.quick-add-btn')?.dataset.price || 0) : 0;
        function validateAndUpdatePrice() {
            const currentUnit = input.dataset.currentUnit;
            let currentValue = parseFloat(input.value);
            let valueInGrams;
            if (currentUnit === 'kg') {
                valueInGrams = currentValue * 1000;
            } else {
                valueInGrams = currentValue;
            }
            if (valueInGrams < minWeight) {
                input.classList.add('error');
                if (currentUnit === 'kg') {
                    input.value = (minWeight / 1000).toFixed(1);
                } else {
                    input.value = minWeight;
                }
                valueInGrams = minWeight;
            } else {
                input.classList.remove('error');
            }
            if (valueInGrams > maxWeight) {
                if (currentUnit === 'kg') {
                    input.value = (maxWeight / 1000).toFixed(1);
                } else {
                    input.value = maxWeight;
                }
                valueInGrams = maxWeight;
            }
            const totalPrice = (valueInGrams / 1000) * price;
            const pricePreview = document.getElementById('price-preview-' + productId);
            if (pricePreview) {
                pricePreview.querySelector('.preview-price').textContent =
                    totalPrice.toFixed(2).replace('.', ',') + '€';
            }
        }
        input.addEventListener('input', validateAndUpdatePrice);
        input.addEventListener('blur', validateAndUpdatePrice);
        validateAndUpdatePrice();
    });
    document.querySelectorAll('.quick-add-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const minWeight = parseInt(this.dataset.minWeight);
            const quantityInput = document.getElementById('quantity-' + productId);
            const currentUnit = quantityInput.dataset.currentUnit;
            let quantity = parseFloat(quantityInput.value);
            let quantityInGrams = currentUnit === 'kg' ? quantity * 1000 : quantity;
            if (quantityInGrams < minWeight) {
                const minDisplay = minWeight >= 1000 ?
                    (minWeight/1000).toFixed(1) + 'kg' :
                    minWeight + 'g';
                window.BoucherieCart?.showNotification(`Quantité insuffisante pour "${productName}".\n\nMinimum requis : ${minDisplay}\nCette contrainte est nécessaire pour la rentabilité et la praticité en boucherie.`, 'error');
                return;
            }
            this.disabled = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Ajout...';
            addToCartWithValidation(productId, Math.round(quantityInGrams), productName, this);
        });
    });
});

function addToCartWithValidation(productId, quantityInGrams, productName, buttonElement) {
    fetch(`/panier/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `quantity=${quantityInGrams}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.BoucherieCart?.showNotification(`${productName} ajouté au panier !`, 'success');
            window.BoucherieCart?.loadCartCount();
        } else {
            window.BoucherieCart?.showNotification(`${data.error || 'Erreur lors de l\'ajout au panier'}`, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        window.BoucherieCart?.showNotification('Erreur de connexion', 'error');
    })
    .finally(() => {
        buttonElement.disabled = false;
        buttonElement.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Ajouter';
    });
}

// ========================================================================
// Fin du JS dédié à la page catégorie produits
// ========================================================================
