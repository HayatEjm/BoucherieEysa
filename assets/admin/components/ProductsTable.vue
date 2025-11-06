<template>
  <div class="card-eysa" style="padding:16px;">
    <h2 class="title-eysa-3" style="margin-bottom:12px;">Produits</h2>

    <div class="admin-table">
      <table>
        <thead>
          <tr>
            <th>Produit</th><th>Prix</th><th>Stock</th><th>Catégorie</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in items" :key="p.id">
            <td>{{ p.name }}</td>
            <td><strong>€ {{ (p.price ?? 0).toFixed(2) }}</strong></td>
            <td>
              <div style="display:flex;gap:6px;align-items:center;">
                <input type="number" min="0" v-model.number="p._stock" class="form-control" style="width:95px;" />
                <button class="btn-eysa btn-eysa-secondary" @click="saveStock(p)">Sauver</button>
              </div>
            </td>
            <td>{{ p.categoryName || 'Non classé' }}</td>
            <td>
              <a :href="`/admin/products/${p.id}/edit`" class="btn-eysa btn-eysa-outline">Modifier</a>
            </td>
          </tr>
          <tr v-if="!loading && items.length === 0">
            <td colspan="5" style="text-align:center;color:#666;padding:22px;">Aucun produit</td>
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

onMounted(fetchProducts);

async function fetchProducts() {
  loading.value = true;
  try {
    const res = await fetch(props.endpoints.list);
    const json = await res.json();
    items.value = (json.items || []).map(p => ({ ...p, _stock: p.stock ?? 0 }));
  } finally {
    loading.value = false;
  }
}

async function saveStock(p) {
  const url = props.endpoints.stock.replace('{id}', p.id);
  const res = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ stock: Number(p._stock) })
  });
  const json = await res.json();
  if (!json.success) alert('Erreur de mise à jour du stock');
}
</script>

<style scoped>
.admin-table { width:100%; background:#fff; border-radius:12px; overflow:hidden; border:1px solid #e5ddd4; }
.admin-table table { width:100%; border-collapse:collapse; }
.admin-table th { background:#FBF9F5; padding:12px 14px; text-align:left; }
.admin-table td { padding:12px 14px; border-top:1px solid #e5ddd4; }

.form-control { width:100%; padding:10px 12px; border:1px solid #e5ddd4; border-radius:8px; }
</style>
