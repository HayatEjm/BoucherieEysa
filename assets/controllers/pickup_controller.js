import { Controller } from '@hotwired/stimulus'

/**
 * Stimulus controller pour la sélection de la date et du créneau de retrait
 * - Grise/bloque les jours fermés (dimanche=0, lundi=1)
 * - Charge les créneaux disponibles pour la date choisie via l'API
 * - Vérifie la disponibilité du créneau choisi avant soumission
 */
export default class extends Controller {
  static targets = ['dateInput', 'timeSlotSelect', 'message']
  static values = {
    closedDays: Array, // ex: [0,1]
    apiBase: String,   // ex: '/api/pickup-slots'
    checkUrl: String   // ex: '/api/pickup-slots/check'
  }

  connect() {
    // Désactiver le sélecteur de créneau tant qu'aucune date valide n'est choisie
    this.setTimeSlotEnabled(false)
    this.clearMessage()
  }

  onDateChange() {
    const dateStr = this.dateInputTarget.value // format 'YYYY-MM-DD'

    if (!dateStr) {
      this.setTimeSlotEnabled(false)
      this.clearMessage()
      return
    }

    const day = this.getDayOfWeek(dateStr) // 0..6

    // Jour fermé ?
    if (this.closedDaysValue?.includes(day)) {
      this.showMessage('error', "Jour fermé : pas de retrait le dimanche ni le lundi.")
      // On vide et désactive le select des créneaux
      this.resetTimeSlots()
      this.setTimeSlotEnabled(false)
      // On empêche l'erreur au submit en nettoyant la valeur
      this.dateInputTarget.classList.add('is-invalid')
      return
    }

    this.dateInputTarget.classList.remove('is-invalid')
    this.loadSlotsForDate(dateStr)
  }

  async onTimeSlotChange() {
    const dateStr = this.dateInputTarget.value
    const slot = this.timeSlotSelectTarget.value

    if (!dateStr || !slot) {
      return
    }

    try {
      const res = await fetch(this.checkUrlValue, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ date: dateStr, time_slot: slot })
      })
      const data = await res.json()
      if (!data.success) {
        throw new Error(data.error || "Vérification indisponible")
      }
      if (!data.data?.available) {
        this.showMessage('error', "Ce créneau vient d'être complet. Merci d'en choisir un autre.")
        this.timeSlotSelectTarget.classList.add('is-invalid')
      } else {
        this.clearMessage()
        this.timeSlotSelectTarget.classList.remove('is-invalid')
      }
    } catch (e) {
      this.showMessage('error', e.message)
    }
  }

  // --- Helpers ---
  getDayOfWeek(dateStr) {
    // Forcer en UTC pour éviter les décalages (safeguard)
    const d = new Date(`${dateStr}T00:00:00Z`)
    return d.getUTCDay()
  }

  async loadSlotsForDate(dateStr) {
    try {
      this.showMessage('info', 'Chargement des créneaux disponibles…')
      const res = await fetch(`${this.apiBaseValue}/${dateStr}`)
      const data = await res.json()
      if (!data.success) {
        throw new Error(data.error || 'Erreur lors du chargement des créneaux')
      }
      const slots = data.data?.slots || []
      // Filtrer les créneaux non disponibles
      const available = slots.filter(s => s.available !== false && s.status !== 'full')
      this.populateTimeSlots(available)
      if (available.length === 0) {
        this.showMessage('warning', 'Aucun créneau disponible pour cette date.')
      } else {
        this.showMessage('success', `${available.length} créneau(x) disponible(s) pour cette date.`)
      }
      this.setTimeSlotEnabled(available.length > 0)
    } catch (e) {
      this.showMessage('error', e.message)
      this.resetTimeSlots()
      this.setTimeSlotEnabled(false)
    }
  }

  populateTimeSlots(slots) {
    const select = this.timeSlotSelectTarget
    // Sauvegarder le placeholder (première option) s'il existe
    const placeholderOption = select.querySelector('option[value=""]')
    select.innerHTML = ''
    if (placeholderOption) {
      select.appendChild(placeholderOption)
    } else {
      const opt = document.createElement('option')
      opt.value = ''
      opt.textContent = 'Choisissez un créneau'
      select.appendChild(opt)
    }

    slots.forEach(slot => {
      const opt = document.createElement('option')
      // L'API renvoie key/time. On préfèrera le format "09:00-10:00" pour value
      opt.value = slot.time
      opt.textContent = slot.time
      select.appendChild(opt)
    })

    // Réinitialiser la valeur au placeholder
    select.value = ''
    this.timeSlotSelectTarget.classList.remove('is-invalid')
  }

  resetTimeSlots() {
    const select = this.timeSlotSelectTarget
    const placeholder = select.querySelector('option[value=""]')
    select.innerHTML = ''
    if (placeholder) {
      select.appendChild(placeholder)
      select.value = ''
    }
  }

  setTimeSlotEnabled(enabled) {
    this.timeSlotSelectTarget.disabled = !enabled
    const submit = document.getElementById('submit-order')
    if (submit) submit.disabled = !enabled
  }

  showMessage(type, text) {
    if (!this.hasMessageTarget) return
    const el = this.messageTarget
    const colors = {
      success: { bg: '#e8f5e9', br: '#2e7d32', icon: 'check-circle' },
      error: { bg: '#ffebee', br: '#c62828', icon: 'exclamation-circle' },
      warning: { bg: '#fff3e0', br: '#ef6c00', icon: 'exclamation-triangle' },
      info: { bg: '#e3f2fd', br: '#1976d2', icon: 'info-circle' }
    }
    const c = colors[type] || colors.info
    el.innerHTML = `
      <div style="display:flex;align-items:center;gap:.5rem;border:1px solid #ddd;border-left:4px solid ${c.br};border-radius:8px;padding:8px 10px;background:${c.bg}">
        <i class="fas fa-${c.icon}" aria-hidden="true"></i>
        <span>${this.escape(text)}</span>
      </div>
    `
  }

  clearMessage() {
    if (this.hasMessageTarget) this.messageTarget.innerHTML = ''
  }

  escape(s) { return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c])) }
}
