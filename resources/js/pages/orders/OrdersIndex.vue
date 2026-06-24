<template>
  <AdminLayout>
    <template #header>
      <h1>Orders</h1>
      <p>Track and manage customer orders.</p>
    </template>

    <template #toolbar>
      <h2 class="toolbar-title">Orders</h2>
      <form @submit.prevent="search" class="orders-filter">
        <select v-model="status" style="border:none;outline:none;background:transparent;font-size:14px;color:#111827;min-width:140px;">
          <option value="">All statuses</option>
          <option v-for="s in statuses" :key="s" :value="s">{{ formatStatus(s) }}</option>
        </select>
        <button type="submit">Filter</button>
      </form>
    </template>

    <template #content>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th />
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in orders" :key="order.id">
            <td>#{{ order.id }}</td>
            <td>{{ order.user_name ?? 'Unknown' }}</td>
            <td>${{ priceFormat(order.total) }}</td>
            <td>
              <span class="badge" :style="badgeStyle(order.status)">
                {{ formatStatus(order.status) }}
              </span>
            </td>
            <td>{{ formatDate(order.created_at) }}</td>
            <td>
              <div class="actions">
                <a href="#" class="btn btn-secondary">View</a>
              </div>
            </td>
          </tr>
          <tr v-if="!loading && orders.length === 0">
            <td colspan="6" class="empty">No orders yet.</td>
          </tr>
        </tbody>
      </table>
      <div class="orders-foot">
        <button :disabled="loading || !hasMore" @click="loadMore" class="btn-secondary" style="padding:6px 12px;font-size:12px;border-radius:8px;border:1px solid #e5e7eb;background:#fff;cursor:pointer;">
          {{ loading ? 'Loading...' : (hasMore ? 'Load more' : 'No more orders') }}
        </button>
      </div>
    </template>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AdminLayout from '../../layouts/AdminLayout.vue';

const orders = ref([]);
const loading = ref(false);
const hasMore = ref(false);
const statuses = ['pending','processing','shipped','completed','cancelled','canceled'];
const status = ref('');

onMounted(async () => {
  await load();
});

async function load() {
  loading.value = true;
  try {
    const { data } = await axios.get('/admin/orders', { params: { status: status.value || undefined } });
    const payload = data?.data ?? data;
    orders.value = (Array.isArray(payload?.data) ? payload.data : Array.isArray(payload) ? payload : []).map((item) => ({
      id: item.id,
      user_name: item.user?.name ?? item.userName,
      total: item.total ?? 0,
      status: item.status,
      created_at: item.created_at ?? item.createdAt,
    }));
    const meta = payload?.meta;
    hasMore.value = Boolean(meta && orders.value.length >= (meta.total ?? orders.value.length));
  } finally {
    loading.value = false;
  }
}

function search() {
  orders.value = [];
  load();
}

async function loadMore() {
  if (loading.value) return;
  await load();
}

function formatStatus(value) {
  if (!value) return 'Pending';
  return String(value).charAt(0).toUpperCase() + String(value).slice(1);
}

function badgeStyle(status) {
  const map = {
    completed: 'background:#ecfdf5;color:#065f46;border-color:#a7f3d0;',
    pending: 'background:#fffbeb;color:#92400e;border-color:#fde68a;',
    shipped: 'background:#eff6ff;color:#1e40af;border-color:#bfdbfe;',
    cancelled: 'background:#fef2f2;color:#991b1b;border-color:#fecaca;',
    canceled: 'background:#fef2f2;color:#991b1b;border-color:#fecaca;',
  };
  return map[status] || 'background:#fff7ed;color:#9a3412;border-color:#fed7aa;';
}

function formatDate(value) {
  if (!value) return '—';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return '—';
  return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
}

function priceFormat(value) {
  if (value === null || value === undefined) return '0.00';
  return Number(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>

<style scoped>
.orders-filter {
  display: inline-flex;
  align-items: center;
  background: #fff;
  border: 1px solid #d1d5db;
  border-radius: 10px;
  padding: 3px;
  gap: 6px;
}
.orders-filter button {
  padding: 9px 14px;
  border-radius: 8px;
  background: #111827;
  color: #fff;
  border: none;
  font-size: 13px;
  cursor: pointer;
}
.orders-foot {
  padding: 14px 16px;
  background: #f9fafb;
  border-top: 1px solid #f3f4f6;
  font-size: 12px;
  color: #6b7280;
  display: flex;
  justify-content: flex-end;
}
.badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
  border: 1px solid;
}
.actions {
  display: flex;
  align-items: center;
  gap: 6px;
  justify-content: flex-end;
}
.btn-secondary {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 9px 14px;
  border-radius: 10px;
  background: #fff;
  color: #111827;
  border: 1px solid #e5e7eb;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
}
.empty {
  padding: 14px;
  text-align: center;
  color: #6b7280;
  font-size: 14px;
}
</style>
