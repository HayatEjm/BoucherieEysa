<template>
  <div class="card-eysa" style="padding:16px;">
    <h2 class="title-eysa-3" style="margin-bottom:12px;">Utilisateurs</h2>

    <div class="admin-table">
      <table>
        <thead>
          <tr>
            <th>Nom</th><th>Email</th><th>Rôles</th><th>Inscription</th><th>Statut</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in items" :key="u.id">
            <td>{{ u.displayName }}</td>
            <td>{{ u.email }}</td>
            <td>{{ u.roles?.join(', ') }}</td>
            <td>{{ formatDate(u.createdAt, 'dd/MM/yyyy') }}</td>
            <td>
              <span class="badge" :class="u.isActive ? 'status-completed':'status-cancelled'">
                {{ u.isActive ? 'Actif' : 'Inactif' }}
              </span>
            </td>
            <td style="display:flex;gap:6px;">
              <button class="btn-eysa btn-eysa-secondary" @click="toggle(u, !u.isActive)">
                {{ u.isActive ? 'Désactiver' : 'Activer' }}
              </button>
              <button class="btn-eysa btn-eysa-outline" @click="removeUser(u)">Supprimer</button>
            </td>
          </tr>
          <tr v-if="!loading && items.length === 0">
            <td colspan="6" style="text-align:center;color:#666;padding:22px;">Aucun utilisateur</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="loading" class="text-eysa-small" style="opacity:.7;margin-top:8px;">Chargement…</div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
  endpoints: { type: Object, required: true }
});

const items = ref([]);
const loading = ref(false);

onMounted(fetchUsers);

async function fetchUsers() {
  loading.value = true;
  try {
    const res = await fetch(props.endpoints.list);
    const json = await res.json();
    items.value = json.items || [];
  } finally {
    loading.value = false;
  }
}

async function toggle(u, newState) {
  const url = props.endpoints.toggle.replace('{id}', u.id);
  const res = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ active: newState })
  });
  const json = await res.json();
  if (json.success) u.isActive = newState;
  else alert('Erreur lors de la modification du statut');
}

async function removeUser(u) {
  if (!confirm('Supprimer cet utilisateur ?')) return;
  const url = props.endpoints.remove.replace('{id}', u.id);
  const res = await fetch(url, { method: 'DELETE', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
  const json = await res.json();
  if (json.success) items.value = items.value.filter(x => x.id !== u.id);
  else alert('Erreur lors de la suppression');
}

function formatDate(value, fmt) {
  if (!value) return '';
  const d = new Date(value);
  const pad = n => String(n).padStart(2, '0');
  const map = { dd: pad(d.getDate()), MM: pad(d.getMonth()+1), yyyy: d.getFullYear() };
  return fmt.replace(/dd|MM|yyyy/g, m => map[m]);
}
</script>

<style scoped>
.admin-table { width:100%; background:#fff; border-radius:12px; overflow:hidden; border:1px solid #e5ddd4; }
.admin-table table { width:100%; border-collapse:collapse; }
.admin-table th { background:#FBF9F5; padding:12px 14px; text-align:left; }
.admin-table td { padding:12px 14px; border-top:1px solid #e5ddd4; }
.badge{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:700;}
.status-completed{background:#d1eddc;color:#155724;}
.status-cancelled{background:#f8d7da;color:#721c24;}
.btn-eysa-outline{border:1px solid var(--primary-color);color:var(--primary-color);background:#fff;}
</style>
