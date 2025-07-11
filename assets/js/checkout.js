// Injection de la configuration checkoutConfig (extraction du inline JS)
window.checkoutConfig = {
    apiUrl: checkoutConfigApiUrl || '', // À définir dynamiquement côté Twig si besoin
    daysToShow: 7
};

// Si Vue.js est requis côté client, importez-le via Webpack/Encore ou documentez l'import CDN ici
// import Vue from 'vue';
// Système de checkout avec sélection de créneaux
console.log('🛒 Initialisation du checkout...');

/**
 * Initialise le système de checkout avec sélection de créneaux
 */
class CheckoutManager {
    constructor(config = {}) {
        this.config = {
            apiUrl: config.apiUrl || '/api/pickup-slots',
            daysToShow: config.daysToShow || 7,
            onSlotSelected: config.onSlotSelected || this.defaultSlotHandler.bind(this),
            ...config
        };
        
        this.selectedSlot = null;
        this.init();
    }
    
    /**
     * Initialisation du checkout
     */
    init() {
        this.initPickupSlotSelector();
        this.initFormValidation();
        this.initSubmitButton();
    }
    
    /**
     * Initialise le sélecteur de créneaux
     */
    initPickupSlotSelector() {
        // Attendre que Vue et notre module soient chargés
        setTimeout(() => {
            if (typeof window.initPickupSlotSelector === 'function') {
                const pickupApp = window.initPickupSlotSelector('pickup-slot-app', {
                    daysToShow: this.config.daysToShow,
                    apiUrl: this.config.apiUrl,
                    onSlotSelected: this.config.onSlotSelected
                });
                console.log('✅ Sélecteur de créneaux initialisé');
            } else {
                console.error('❌ initPickupSlotSelector non disponible');
                this.showError('Erreur lors du chargement du sélecteur de créneaux');
            }
        }, 100);
    }
    
    /**
     * Gestionnaire par défaut de sélection de créneau
     */
    defaultSlotHandler(slot) {
        console.log('🎯 Créneau sélectionné:', slot);
        this.selectedSlot = slot;
        
        // Remplir les champs cachés
        const pickupDateField = document.getElementById('pickup_date');
        const pickupTimeSlotField = document.getElementById('pickup_time_slot');
        
        if (pickupDateField && pickupTimeSlotField) {
            pickupDateField.value = slot.date;
            pickupTimeSlotField.value = slot.timeSlot;
        }
        
        // Activer le bouton de soumission
        this.enableSubmitButton();
    }
    
    /**
     * Initialise la validation du formulaire
     */
    initFormValidation() {
        const form = document.getElementById('checkout-form');
        if (!form) return;
        
        form.addEventListener('submit', (e) => {
            if (!this.validateForm()) {
                e.preventDefault();
                return false;
            }
        });
    }
    
    /**
     * Valide le formulaire avant soumission
     */
    validateForm() {
        const pickupDate = document.getElementById('pickup_date')?.value;
        const pickupTimeSlot = document.getElementById('pickup_time_slot')?.value;
        const customerName = document.getElementById('customer_name')?.value;
        const customerPhone = document.getElementById('customer_phone')?.value;
        
        // Vérifier le créneau
        if (!pickupDate || !pickupTimeSlot) {
            this.showError('Veuillez sélectionner un créneau de retrait.');
            return false;
        }
        
        // Vérifier les informations client
        if (!customerName || !customerPhone) {
            this.showError('Veuillez remplir vos informations de contact.');
            return false;
        }
        
        // Validation du téléphone (simple)
        const phoneRegex = /^[\d\s\-\+\(\)]{8,}$/;
        if (!phoneRegex.test(customerPhone)) {
            this.showError('Veuillez entrer un numéro de téléphone valide.');
            return false;
        }
        
        return true;
    }
    
    /**
     * Initialise l'état du bouton de soumission
     */
    initSubmitButton() {
        const submitBtn = document.getElementById('submit-order');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-disabled');
        }
    }
    
    /**
     * Active le bouton de soumission
     */
    enableSubmitButton() {
        const submitBtn = document.getElementById('submit-order');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-disabled');
        }
    }
    
    /**
     * Désactive le bouton de soumission
     */
    disableSubmitButton() {
        const submitBtn = document.getElementById('submit-order');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-disabled');
        }
    }
    
    /**
     * Affiche une erreur à l'utilisateur
     */
    showError(message) {
        // Simple alert pour l'instant, peut être amélioré avec un toast
        alert(message);
        console.error('❌ Erreur checkout:', message);
    }
    
    /**
     * Obtient les données du formulaire
     */
    getFormData() {
        return {
            selectedSlot: this.selectedSlot,
            customerName: document.getElementById('customer_name')?.value,
            customerPhone: document.getElementById('customer_phone')?.value,
            customerEmail: document.getElementById('customer_email')?.value,
            pickupDate: document.getElementById('pickup_date')?.value,
            pickupTimeSlot: document.getElementById('pickup_time_slot')?.value
        };
    }
}

// Initialisation automatique au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    // Configuration passée depuis le template Twig
    const config = window.checkoutConfig || {};
    
    // Créer l'instance du checkout manager
    window.checkoutManager = new CheckoutManager(config);
    
    console.log('✅ Checkout initialisé');
});

// Export pour utilisation globale
window.CheckoutManager = CheckoutManager;
