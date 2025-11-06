// assets/admin/main-admin.js
// Mon point d'entrée Vue pour l'admin (séparé du front public)

import '../styles/design-system.css'; // je réutilise ton DS global
import '../styles/admin.css';         // si tu préfères, garde ton admin.css public

import { createApp } from 'vue';

// J'importe mes composants Vue admin
import OrdersTable from './components/OrdersTable.vue';
import ProductsTable from './components/ProductsTable.vue';
import UsersTable from './components/UsersTable.vue';

// Je monte chaque composant seulement si le conteneur existe

const ordersRoot = document.getElementById('orders-table-root');
if (ordersRoot) {
  createApp(OrdersTable, {
    endpoints: {
      list: ordersRoot.dataset.endpointList,
      status: ordersRoot.dataset.endpointStatus, // pattern .../{id}/status
    }
  }).mount(ordersRoot);
}

const productsRoot = document.getElementById('products-table-root');
if (productsRoot) {
  createApp(ProductsTable, {
    endpoints: {
      list: productsRoot.dataset.endpointList,
      stock: productsRoot.dataset.endpointStock,  // pattern .../{id}/stock
    }
  }).mount(productsRoot);
}

const usersRoot = document.getElementById('users-table-root');
if (usersRoot) {
  createApp(UsersTable, {
    endpoints: {
      list: usersRoot.dataset.endpointList,
      toggle: usersRoot.dataset.endpointToggle,   // pattern .../{id}/toggle-status
      remove: usersRoot.dataset.endpointDelete,   // pattern .../{id}/delete
    }
  }).mount(usersRoot);
}
