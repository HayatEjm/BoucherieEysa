/**
 * Composant Vue 3 autonome pour la sélection des créneaux de retrait
 * 
 * Ce composant :
 * - Récupère les créneaux disponibles depuis l'API Symfony
 * - Affiche un calendrier simple avec les créneaux
 * - Utilise les couleurs du design system pour les statuts
 * - Est facilement intégrable et évolutif
 * 
 * Ce fichier utilise Vue 3 depuis CDN pour simplicité
 */

// Configuration du composant
const PickupSlotSelector = {
    name: 'PickupSlotSelector',
    
    props: {
        // Nombre de jours à afficher (défaut: 7)
        daysToShow: {
            type: Number,
            default: 7
        },
        // URL de l'API (défaut: route Symfony)
        apiUrl: {
            type: String,
            default: '/api/pickup-slots'
        }
    },

    setup(props, { emit }) {
        // État réactif (Vue 3 sera chargé depuis CDN)
        const { ref, onMounted, computed } = Vue;
        
        const slots = ref([]);
        const loading = ref(true);
        const error = ref(null);
        const selectedSlot = ref(null);

        // Créneaux disponibles organisés
        const availableSlots = computed(() => {
            return slots.value || [];
        });

        // Charger les créneaux depuis l'API
        const loadSlots = async () => {
            try {
                loading.value = true;
                error.value = null;

                const response = await fetch(`${props.apiUrl}?days=${props.daysToShow}`);
                const data = await response.json();

                if (data.success) {
                    slots.value = data.data.slots;
                } else {
                    throw new Error(data.error || 'Erreur lors du chargement des créneaux');
                }
            } catch (err) {
                error.value = err.message;
                console.error('Erreur lors du chargement des créneaux:', err);
            } finally {
                loading.value = false;
            }
        };

        // Sélectionner un créneau
        const selectSlot = (date, slot) => {
            if (!slot.available) return;

            selectedSlot.value = {
                date: date,
                timeSlot: slot.key,
                time: slot.time
            };

            // Émettre l'événement pour le parent
            emit('slot-selected', selectedSlot.value);
        };

        // Obtenir la classe CSS selon le statut
        const getSlotClass = (slot) => {
            const baseClass = 'pickup-slot';
            
            if (!slot.available) {
                return `${baseClass} pickup-slot--full`;
            }
            
            switch (slot.status) {
                case 'limited':
                    return `${baseClass} pickup-slot--limited`;
                case 'available':
                    return `${baseClass} pickup-slot--available`;
                default:
                    return baseClass;
            }
        };

        // Vérifier si un créneau est sélectionné
        const isSelected = (date, slotKey) => {
            return selectedSlot.value?.date === date && selectedSlot.value?.timeSlot === slotKey;
        };

        // Formater la date pour l'affichage
        const formatDate = (dateStr) => {
            const date = new Date(dateStr);
            return date.toLocaleDateString('fr-FR', {
                weekday: 'long',
                day: 'numeric',
                month: 'long'
            });
        };

        // Charger les créneaux au montage
        onMounted(() => {
            loadSlots();
        });

        return {
            slots,
            loading,
            error,
            selectedSlot,
            availableSlots,
            loadSlots,
            selectSlot,
            getSlotClass,
            isSelected,
            formatDate
        };
    },

    template: `
        <div class="pickup-slot-selector">
            <div class="pickup-slot-selector__header">
                <h3 class="pickup-slot-selector__title">
                    Choisissez votre créneau de retrait
                </h3>
                <p class="pickup-slot-selector__subtitle">
                    Sélectionnez un créneau disponible pour récupérer votre commande
                </p>
            </div>

            <!-- État de chargement -->
            <div v-if="loading" class="pickup-slot-selector__loading">
                <div class="loading-spinner"></div>
                <p>Chargement des créneaux disponibles...</p>
            </div>

            <!-- Erreur -->
            <div v-else-if="error" class="pickup-slot-selector__error">
                <p>{{ error }}</p>
                <button @click="loadSlots" class="btn btn--secondary">
                    Réessayer
                </button>
            </div>

            <!-- Créneaux disponibles -->
            <div v-else class="pickup-slot-selector__content">
                <div v-if="availableSlots.length === 0" class="pickup-slot-selector__empty">
                    <p>Aucun créneau disponible pour le moment.</p>
                </div>

                <div v-else class="pickup-slot-grid">
                    <div 
                        v-for="day in availableSlots" 
                        :key="day.date"
                        class="pickup-day"
                    >
                        <div class="pickup-day__header">
                            <h4 class="pickup-day__date">
                                {{ formatDate(day.date) }}
                            </h4>
                        </div>

                        <div class="pickup-day__slots">
                            <button
                                v-for="slot in day.slots"
                                :key="slot.key"
                                :class="[
                                    getSlotClass(slot),
                                    { 'pickup-slot--selected': isSelected(day.date, slot.key) }
                                ]"
                                :disabled="!slot.available"
                                @click="selectSlot(day.date, slot)"
                            >
                                <span class="pickup-slot__time">{{ slot.time }}</span>
                                <span class="pickup-slot__status">
                                    <span v-if="slot.status === 'available'" class="status-text">
                                        {{ slot.max_orders - slot.current_orders }} places restantes
                                    </span>
                                    <span v-else-if="slot.status === 'limited'" class="status-text">
                                        Plus que {{ slot.max_orders - slot.current_orders }} places
                                    </span>
                                    <span v-else class="status-text">
                                        Complet
                                    </span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Créneau sélectionné -->
                <div v-if="selectedSlot" class="pickup-slot-selector__selected">
                    <div class="selected-slot">
                        <h4>Créneau sélectionné :</h4>
                        <p>
                            <strong>{{ formatDate(selectedSlot.date) }}</strong> 
                            à <strong>{{ selectedSlot.time }}</strong>
                        </p>
                    </div>
                </div>

                <!-- Légende -->
                <div class="pickup-slot-selector__legend">
                    <div class="legend-item">
                        <span class="legend-color legend-color--available"></span>
                        Disponible
                    </div>
                    <div class="legend-item">
                        <span class="legend-color legend-color--limited"></span>
                        Places limitées
                    </div>
                    <div class="legend-item">
                        <span class="legend-color legend-color--full"></span>
                        Complet
                    </div>
                </div>
            </div>
        </div>
    `
};

// Fonction pour initialiser le composant (sera appelée depuis le template Twig)
window.initPickupSlotSelector = function(containerId = 'pickup-slot-app', options = {}) {
    const container = document.getElementById(containerId);
    
    if (!container) {
        console.error(`Container avec l'id "${containerId}" non trouvé`);
        return null;
    }

    // Attendre que Vue soit chargé
    if (typeof Vue === 'undefined') {
        console.error('Vue.js n\'est pas chargé');
        return null;
    }

    const { createApp, ref } = Vue;

    const app = createApp({
        components: {
            PickupSlotSelector
        },
        setup() {
            const selectedSlot = ref(null);

            const handleSlotSelected = (slot) => {
                selectedSlot.value = slot;
                
                // Mettre à jour les champs cachés du formulaire
                const dateInput = document.querySelector('input[name="pickup_date"]');
                const slotInput = document.querySelector('input[name="pickup_time_slot"]');
                
                if (dateInput) dateInput.value = slot.date;
                if (slotInput) slotInput.value = slot.timeSlot;

                // Déclencher les événements pour la validation du formulaire
                if (dateInput) dateInput.dispatchEvent(new Event('change'));
                if (slotInput) slotInput.dispatchEvent(new Event('change'));

                // Callback personnalisé si fourni
                if (options.onSlotSelected) {
                    options.onSlotSelected(slot);
                }
            };

            return {
                selectedSlot,
                handleSlotSelected,
                ...options
            };
        },
        template: `
            <PickupSlotSelector 
                :days-to-show="daysToShow || 7"
                :api-url="apiUrl || '/api/pickup-slots'"
                @slot-selected="handleSlotSelected"
            />
        `
    });

    return app.mount(container);
};

// Export pour utilisation dans d'autres modules
