<template>
  <div class="card-eysa" style="padding:16px;">
    <h2 class="title-eysa-3" style="margin-bottom:12px;">Commandes</h2>

    <!-- Filtres -->
    <div class="grid-eysa grid-eysa-4" style="margin-bottom:12px;">
      <select v-model="filters.status" @change="fetchOrders" class="form-control">
        <option value="">Tous les statuts</option>
        <option value="pending">En attente</option>
        <option value="confirmed">Confirmée</option>
        <option value="ready">Prête</option>
        <option value="completed">Terminée</option>
        <option value="cancelled">Annulée</option>
      </select>
      <input v-model.trim="filters.search"
             @keyup.enter="fetchOrders"
             placeholder="Recherche client / email"
             class="form-control" />
      <button class="btn-eysa btn-eysa-primary" @click="fetchOrders">Appliquer</button>
      <button class="btn-eysa btn-eysa-secondary" @click="resetFilters">Réinitialiser</button>
    </div>

    <!-- Table -->
    <div class="admin-table">
      <table>
        <thead>
          <tr>
            <th>#</th><th>Client</th><th>Date</th><th>Créneau</th><th>Total</th><th>Statut</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="o in items" :key="o.id">
            <td><strong>#{{ shortId(o.id) }}</strong></td>
            <td>
              <div>{{ o.customerName || 'Client' }}</div>
              <small v-if="o.customerEmail" style="color:#666">{{ o.customerEmail }}</small>
            </td>
            <td>
              <div>{{ formatDate(o.createdAt, 'dd/MM/yyyy') }}</div>
              <small style="color:#666">{{ formatDate(o.createdAt, 'HH:mm') }}</small>
            </td>
            <td>
              <span v-if="o.pickupDate">{{ formatDate(o.pickupDate, 'dd/MM') }} • {{ o.pickupTime || '--:--' }}</span>
              <span v-else style="color:#999"><i>Non défini</i></span>
            </td>
            <td><strong>€ {{ (o.total ?? 0).toFixed(2) }}</strong></td>
            <td><span :class="['badge', badgeClass(o.status)]">{{ labelStatus(o.status) }}</span></td>
            <td>
              <select :value="o.status" @change="onChangeStatus(o, $event.target.value)" class="form-control" style="max-width:150px;">
                <option value="pending">En attente</option>
                <option value="confirmed">Confirmée</option>
                <option value="ready">Prête</option>
                <option value="completed">Terminée</option>
                <option value="cancelled">Annulée</option>
              </select>
            </td>
          </tr>
          <tr v-if="!loading && items.length === 0">
            <td colspan="7" style="text-align:center;color:#666;padding:22px;">Aucune commande trouvée</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="loading" class="text-eysa-small" style="opacity:.7;margin-top:8px;">Chargement…</div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';

// Je reçois les URLs depuis le data-attribute du div Twig
const props = defineProps({
  endpoints: { type: Object, required: true }
});

const items = ref([]);
const loading = ref(false);
const filters = reactive({ status: '', search: '' });

onMounted(fetchOrders);

async function fetchOrders() {
  loading.value = true;
  try {
    const qs = new URLSearchParams();
    if (filters.status) qs.set('status', filters.status);
    if (filters.search) qs.set('search', filters.search);

    const res = await fetch(`${props.endpoints.list}?${qs.toString()}`);
    const json = await res.json();
    items.value = json.items || [];
  } finally {
    loading.value = false;
  }
}

function resetFilters() {
  filters.status = '';
  filters.search = '';
  fetchOrders();
}

async function onChangeStatus(order, newStatus) {
  const url = props.endpoints.status.replace('{id}', order.id);
  const res = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ status: newStatus })
  });
  const json = await res.json();
  if (json.success) order.status = newStatus;
  else alert('Erreur lors de la mise à jour du statut');
}

// Helpers
function shortId(id) { return String(id).slice(-6).toUpperCase(); }
function labelStatus(s) {
  return ({ pending:'En attente', confirmed:'Confirmée', ready:'Prête', completed:'Terminée', cancelled:'Annulée' })[s] || s;
}
function badgeClass(s) {
  return ({ pending:'status-pending', confirmed:'status-confirmed', ready:'status-ready', completed:'status-completed', cancelled:'status-cancelled' })[s] || 'status-pending';
}
function formatDate(value, fmt) {
  if (!value) return '';
  const d = new Date(value);
  const pad = n => String(n).padStart(2, '0');
  const map = { dd: pad(d.getDate()), MM: pad(d.getMonth()+1), yyyy: d.getFullYear(), HH: pad(d.getHours()), mm: pad(d.getMinutes()) };
  return fmt.replace(/dd|MM|yyyy|HH|mm/g, m => map[m]);
}
</script>

<style scoped>
.admin-table { width:100%; background:#fff; border-radius:12px; overflow:hidden; border:1px solid #e5ddd4; }
.admin-table table { width:100%; border-collapse:collapse; }
.admin-table th { background:#FBF9F5; padding:12px 14px; text-align:left; }
.admin-table td { padding:12px 14px; border-top:1px solid #e5ddd4; }

.badge{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:700;}
.status-pending{background:#fff3cd;color:#856404;}
.status-confirmed{background:#cce7ff;color:#004085;}
.status-ready{background:#b3e5fc;color:#01579b;}
.status-completed{background:#d1eddc;color:#155724;}
.status-cancelled{background:#f8d7da;color:#721c24;}

.form-control { width:100%; padding:10px 12px; border:1px solid #e5ddd4; border-radius:8px; }
</style>
