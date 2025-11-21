/**
 * GESTION DYNAMIQUE DES CRÉNEAUX DE RETRAIT
 * 
 * Permet de mettre à jour la liste des créneaux horaires
 * lorsque l'utilisateur change de date dans le formulaire checkout
 */

document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.querySelector('#checkout_pickupDate');
    const timeSlotInput = document.querySelector('#checkout_pickupTimeSlot');
    
    if (!dateInput || !timeSlotInput) {
        return; // Pas sur la page checkout
    }

    // Convertir le champ texte en select
    const timeSlotSelect = document.createElement('select');
    timeSlotSelect.id = timeSlotInput.id;
    timeSlotSelect.name = timeSlotInput.name;
    timeSlotSelect.className = timeSlotInput.className;
    timeSlotSelect.required = timeSlotInput.required;
    
    // Remplacer l'input par le select
    timeSlotInput.parentNode.replaceChild(timeSlotSelect, timeSlotInput);

    // Fonction pour charger les créneaux d'une date
    async function loadSlotsForDate(dateValue) {
        if (!dateValue) {
            return;
        }

        try {
            // Appeler l'API pour récupérer les créneaux
            const response = await fetch(`/checkout/api/slots?date=${dateValue}`);
            
            if (!response.ok) {
                console.error('Erreur lors du chargement des créneaux');
                return;
            }

            const data = await response.json();
            
            // Vider le select
            timeSlotSelect.innerHTML = '<option value="">Choisissez un créneau</option>';
            
            if (data.closed) {
                // Jour fermé
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Fermé ce jour';
                option.disabled = true;
                timeSlotSelect.appendChild(option);
                timeSlotSelect.disabled = true;
            } else if (data.slots.length === 0) {
                // Aucun créneau disponible
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Aucun créneau disponible';
                option.disabled = true;
                timeSlotSelect.appendChild(option);
                timeSlotSelect.disabled = true;
            } else {
                // Ajouter les créneaux disponibles
                data.slots.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.value;
                    option.textContent = slot.label;
                    if (!slot.available) {
                        option.disabled = true;
                        option.textContent += ' (complet)';
                    }
                    timeSlotSelect.appendChild(option);
                });
                timeSlotSelect.disabled = false;
            }
            
        } catch (error) {
            console.error('Erreur lors du chargement des créneaux:', error);
        }
    }

    // Écouter le changement de date
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;
        loadSlotsForDate(selectedDate);
    });

    // Charger les créneaux au chargement de la page si une date est présélectionnée
    if (dateInput.value) {
        loadSlotsForDate(dateInput.value);
    } else {
        // Aucune date sélectionnée, afficher un message
        timeSlotSelect.innerHTML = '<option value="">Choisissez d\'abord une date</option>';
        timeSlotSelect.disabled = true;
    }
});
