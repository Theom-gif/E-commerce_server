<template>
  <AdminLayout>
    <template #header>
      <h1>Products</h1>
      <p>Manage your product catalog.</p>
    </template>

    <template #toolbar>
      <h2 class="toolbar-title">Products</h2>
      <div class="toolbar-actions">
        <form @submit.prevent="search" class="search">
          <input v-model="searchQuery" placeholder="Search products…" />
          <button type="submit">Search</button>
        </form>
        <router-link to="/admin/products/create" class="btn btn-secondary">
          <i class="fas fa-plus"></i> Create Product
        </router-link>
      </div>
    </template>

    <template #content>
      <div class="pl">
        <table class="pl-table">
          <thead>
            <tr>
              <th style="width:68px;">Image</th>
              <th>Product</th>
              <th>Price</th>
              <th>Stock</th>
              <th style="text-align:center;">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in products" :key="product.id">
              <td>
                <div class="pl-thumb">
                  <img
                    v-if="product.imageUrl"
                    :src="product.imageUrl"
                    :alt="product.name"
                    @error="onImageError($event)"
                  />
                  <i v-else class="fas fa-image pl-thumb-empty"></i>
                </div>
              </td>
              <td>
                <div class="pl-name">{{ product.name }}</div>
                <div class="pl-cat">{{ product.category_name ?? '—' }}</div>
              </td>
              <td>
                <span class="pl-price">${{ priceFormat(product.price) }}</span>
              </td>
              <td>
                <span class="pl-stock" :class="stockClass(product.stock)">
                  <i :class="stockIcon(product.stock)" style="font-size:10px;"></i>
                  {{ product.stock }}
                </span>
              </td>
              <td>
                <div class="pl-acts">
                  <router-link :to="`/admin/products/${product.id}/edit`" class="pl-btn pl-btn-edit">
                    <i class="fas fa-pen"></i> Edit
                  </router-link>
                  <button @click="destroy(product.id, product.name)" class="pl-btn pl-btn-del">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="!loading && products.length === 0">
              <td colspan="5">
                <div class="pl-empty">
                  <div class="pl-empty-icon"><i class="fas fa-box-open"></i></div>
                  <p class="pl-empty-title">No products yet</p>
                  <p class="pl-empty-sub">Add your first product to start building the catalog.</p>
                  <router-link to="/admin/products/create" class="pl-empty-cta">
                    <i class="fas fa-plus"></i> Create Product
                  </router-link>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="pl-foot">
          <button :disabled="loading || !hasMore" @click="loadMore" class="btn-secondary" style="padding:6px 12px;font-size:12px;border-radius:8px;border:1px solid #e5e7eb;background:#fff;cursor:pointer;">
            {{ loading ? 'Loading...' : (hasMore ? 'Load more' : 'No more products') }}
          </button>
        </div>
      </div>
    </template>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AdminLayout from '../../layouts/AdminLayout.vue';

const products = ref([]);
const loading = ref(false);
const hasMore = ref(false);
const searchQuery = ref('');

onMounted(async () => {
  await load();
});

async function load() {
  loading.value = true;
  try {
    const { data } = await axios.get('/admin/products', { params: { search: searchQuery.value || undefined } });
    const payload = data?.data ?? data;
    products.value = (Array.isArray(payload?.data) ? payload.data : Array.isArray(payload) ? payload : []).map((item) => ({
      id: item.id ?? item.ID ?? item.product_id,
      name: item.name,
      price: item.price,
      stock: item.stock,
      image: item.image,
      category: item.category,
      category_name: item.category?.name ?? item.categoryName ?? '—',
      imageUrl: imageUrlFor(item),
    }));
    const meta = payload?.meta;
    hasMore.value = Boolean(meta && products.value.length >= (meta.total ?? products.value.length));
  } finally {
    loading.value = false;
  }
}

function search() {
  products.value = [];
  load();
}

async function loadMore() {
  if (loading.value) return;
  await load();
}

async function destroy(id, name) {
  if (!confirm(`Delete ${name}? This cannot be undone.`)) return;
  try {
    const form = new FormData();
    form.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
    form.append('_method', 'DELETE');
    await axios.post(`/admin/products/${id}`, form, {
      headers: { 'Content-Type': 'multipart/form-data', 'X-HTTP-Method-Override': 'DELETE' },
    });
    products.value = products.value.filter((item) => item.id !== id);
  } catch {
    alert('Failed to delete product.');
  }
}

function imageUrlFor(item) {
  if (!item.image) return null;
  if (typeof item.image === 'string') {
    return item.image.startsWith('http') ? item.image : `/storage/${item.image}`;
  }
  return null;
}

function onImageError(event) {
  event.target.style.display = 'none';
}

function stockClass(stock) {
  if (!stock && stock !== 0) return 'zero';
  if (stock === 0) return 'zero';
  if (stock <= 5) return 'low';
  return 'ok';
}

function stockIcon(stock) {
  if (stock === 0) return 'fas fa-circle-xmark';
  if (stock <= 5) return 'fas fa-triangle-exclamation';
  return 'fas fa-circle-check';
}

function priceFormat(value) {
  if (value === null || value === undefined) return '0.00';
  return Number(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>

<style scoped>
.pl {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.05);
  overflow: hidden;
}
.pl-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13.5px;
}
.pl-table thead tr {
  background: #f8fafc;
  border-bottom: 1px solid #e5e7eb;
}
.pl-table thead th {
  padding: 11px 16px;
  font-size: 10.5px;
  font-weight: 700;
  letter-spacing: 0.09em;
  text-transform: uppercase;
  color: #94a3b8;
  text-align: left;
  white-space: nowrap;
}
.pl-table thead th:last-child { text-align: right; }
.pl-table tbody tr {
  border-bottom: 1px solid #e5e7eb;
  transition: background 180ms ease;
}
.pl-table tbody tr:last-child { border-bottom: none; }
.pl-table tbody tr:hover { background: #fafbff; }
.pl-table td {
  padding: 12px 16px;
  color: #111827;
  vertical-align: middle;
}
.pl-thumb {
  width: 52px;
  height: 52px;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
  background: #f8fafc;
  overflow: hidden;
  display: grid;
  place-items: center;
  flex-shrink: 0;
}
.pl-thumb img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
}
.pl-thumb-empty {
  font-size: 18px;
  color: #e5e7eb;
}
.pl-name {
  font-weight: 600;
  color: #111827;
  line-height: 1.3;
}
.pl-cat {
  font-size: 11.5px;
  color: #94a3b8;
  margin-top: 2px;
}
.pl-price {
  font-weight: 700;
  color: #111827;
  letter-spacing: -0.2px;
}
.pl-stock {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
}
.pl-stock.ok { background: #ecfdf5; color: #059669; }
.pl-stock.low { background: #fffbeb; color: #d97706; }
.pl-stock.zero { background: #fef2f2; color: #ef4444; }
.pl-acts {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}
.pl-btn {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 6px 13px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  border: none;
  text-decoration: none;
  transition: background 180ms ease, transform 180ms ease;
  line-height: 1;
  white-space: nowrap;
}
.pl-btn:active { transform: scale(.96); }
.pl-btn-edit {
  background: #eef2ff;
  color: #6366f1;
  border: 1px solid #c7d2fe;
}
.pl-btn-edit:hover { background: #e0e7ff; }
.pl-btn-del {
  background: #fef2f2;
  color: #ef4444;
  border: 1px solid #fecaca;
}
.pl-btn-del:hover { background: #ffe4e4; }
.pl-empty {
  padding: 60px 20px;
  text-align: center;
}
.pl-empty-icon {
  font-size: 38px;
  color: #e5e7eb;
  margin-bottom: 12px;
}
.pl-empty-title {
  font-size: 15px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 6px;
}
.pl-empty-sub {
  font-size: 13px;
  color: #64748b;
  margin: 0 0 20px;
}
.pl-empty-cta {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 9px 20px;
  background: #6366f1;
  color: #fff;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  box-shadow: 0 2px 8px rgba(99,102,241,.28);
  transition: background 180ms ease;
}
.pl-empty-cta:hover { background: #4f46e5; }
.pl-foot {
  padding: 12px 18px;
  border-top: 1px solid #e5e7eb;
  background: #f8fafc;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  font-size: 12px;
  color: #64748b;
}
.toolbar-actions {
  display: inline-flex;
  align-items: center;
  gap: 8px;
}
.search {
  display: inline-flex;
  align-items: center;
  background: #fff;
  border: 1px solid #d1d5db;
  border-radius: 10px;
  padding: 3px;
}
.search input {
  border: none;
  outline: none;
  padding: 9px 10px;
  border-radius: 8px;
  min-width: 220px;
  font-size: 14px;
  color: #111827;
  background: transparent;
}
.search input::placeholder { color: #9ca3af; }
.search button {
  padding: 9px 14px;
  border-radius: 8px;
  background: #111827;
  color: #fff;
  border: none;
  font-size: 13px;
  cursor: pointer;
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
.btn-secondary:hover {
  background: #f9fafb;
}
</style>
