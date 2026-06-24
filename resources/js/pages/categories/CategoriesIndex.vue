<template>
  <AdminLayout>
    <template #header>
      <h1>Categories</h1>
      <p>Manage your product categories.</p>
    </template>

    <template #toolbar>
      <h2 class="toolbar-title">Categories</h2>
      <div class="toolbar-actions">
        <form @submit.prevent="search" class="search">
          <input v-model="searchQuery" placeholder="Search categories…" />
          <button type="submit">Search</button>
        </form>
        <router-link to="/admin/categories/create" class="btn btn-secondary">
          <i class="fas fa-plus"></i> Create Category
        </router-link>
      </div>
    </template>

    <template #content>
      <div class="cl">
        <table class="cl-table">
          <thead>
            <tr>
              <th>Category</th>
              <th>Products</th>
              <th>Created</th>
              <th style="text-align:center;">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="category in categories" :key="category.id">
              <td>
                <div class="cl-name-wrap">
                  <div class="cl-icon"><i class="fas fa-folder"></i></div>
                  <span class="cl-name">{{ category.name }}</span>
                </div>
              </td>
              <td>
                <span class="cl-count">
                  <i class="fas fa-box"></i>
                  {{ numberFormat(category.products_count ?? 0) }}
                </span>
              </td>
              <td>
                <span class="cl-date">
                  <i class="fas fa-calendar" style="margin-right:5px;opacity:.4;font-size:11px;"></i>
                  {{ formatDate(category.created_at) }}
                </span>
              </td>
              <td>
                <div class="cl-acts">
                  <router-link :to="`/admin/categories/${category.id}/edit`" class="cl-btn cl-btn-edit">
                    <i class="fas fa-pen"></i> Edit
                  </router-link>
                  <button @click="destroy(category.id, category.name)" class="cl-btn cl-btn-del">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="!loading && categories.length === 0">
              <td colspan="4">
                <div class="cl-empty">
                  <div class="cl-empty-icon"><i class="fas fa-folder-open"></i></div>
                  <p class="cl-empty-title">No categories yet</p>
                  <p class="cl-empty-sub">Create your first category to start organising products.</p>
                  <router-link to="/admin/categories/create" class="cl-empty-cta">
                    <i class="fas fa-plus"></i> Create Category
                  </router-link>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="cl-foot">
          <button :disabled="loading || !hasMore" @click="loadMore" class="btn-secondary" style="padding:6px 12px;font-size:12px;border-radius:8px;border:1px solid #e5e7eb;background:#fff;cursor:pointer;">
            {{ loading ? 'Loading...' : (hasMore ? 'Load more' : 'No more categories') }}
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

const categories = ref([]);
const loading = ref(false);
const hasMore = ref(false);
const searchQuery = ref('');

onMounted(async () => {
  await load();
});

async function load() {
  loading.value = true;
  try {
    const { data } = await axios.get('/admin/categories', { params: { search: searchQuery.value || undefined } });
    const payload = data?.data ?? data;
    categories.value = (Array.isArray(payload?.data) ? payload.data : Array.isArray(payload) ? payload : []).map((item) => ({
      id: item.id ?? item.ID ?? item.category_id,
      name: item.name,
      products_count: item.products_count ?? item.productsCount ?? 0,
      created_at: item.created_at ?? item.createdAt,
      updated_at: item.updated_at ?? item.updatedAt,
      slug: item.slug,
    }));
    const meta = payload?.meta;
    hasMore.value = Boolean(meta && categories.value.length >= (meta.total ?? categories.value.length));
  } finally {
    loading.value = false;
  }
}

function search() {
  categories.value = [];
  load();
}

async function loadMore() {
  if (loading.value) return;
  await load();
}

async function destroy(id, name) {
  if (!confirm(`Delete '${name}'? This cannot be undone.`)) return;
  const form = new FormData();
  form.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
  form.append('_method', 'DELETE');
  try {
    await axios.post(`/admin/categories/${id}`, form, {
      headers: { 'Content-Type': 'multipart/form-data', 'X-HTTP-Method-Override': 'DELETE' },
    });
    categories.value = categories.value.filter((item) => item.id !== id);
  } catch (error) {
    alert('Failed to delete category.');
  }
}

function formatDate(value) {
  if (!value) return '—';
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return '—';
  return date.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
}

function numberFormat(value) {
  if (value === null || value === undefined) return '0';
  return Number(value).toLocaleString();
}
</script>

<style scoped>
.cl {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.05);
  overflow: hidden;
}
.cl-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13.5px;
}
.cl-table thead tr {
  background: #f8fafc;
  border-bottom: 1px solid #e5e7eb;
}
.cl-table thead th {
  padding: 11px 18px;
  font-size: 10.5px;
  font-weight: 700;
  letter-spacing: 0.09em;
  text-transform: uppercase;
  color: #94a3b8;
  text-align: left;
  white-space: nowrap;
}
.cl-table thead th:last-child { text-align: right; }
.cl-table tbody tr {
  border-bottom: 1px solid #e5e7eb;
  transition: background 180ms ease;
}
.cl-table tbody tr:last-child { border-bottom: none; }
.cl-table tbody tr:hover { background: #fafbff; }
.cl-table td {
  padding: 13px 18px;
  color: #111827;
  vertical-align: middle;
}
.cl-name-wrap {
  display: flex;
  align-items: center;
  gap: 11px;
}
.cl-icon {
  width: 36px;
  height: 36px;
  border-radius: 8px;
  background: #eef2ff;
  color: #6366f1;
  display: grid;
  place-items: center;
  font-size: 13px;
  flex-shrink: 0;
}
.cl-name {
  font-weight: 600;
  color: #111827;
  line-height: 1;
}
.cl-count {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
  background: #eef2ff;
  color: #6366f1;
  white-space: nowrap;
}
.cl-count i { font-size: 10px; }
.cl-date {
  font-size: 12.5px;
  color: #64748b;
  white-space: nowrap;
}
.cl-acts {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}
.cl-btn {
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
.cl-btn:active { transform: scale(.96); }
.cl-btn-edit {
  background: #eef2ff;
  color: #6366f1;
  border: 1px solid #c7d2fe;
}
.cl-btn-edit:hover { background: #e0e7ff; }
.cl-btn-del {
  background: #fef2f2;
  color: #ef4444;
  border: 1px solid #fecaca;
}
.cl-btn-del:hover { background: #ffe4e4; }
.cl-empty {
  padding: 60px 20px;
  text-align: center;
}
.cl-empty-icon { font-size: 38px; color: #e5e7eb; margin-bottom: 12px; }
.cl-empty-title { font-size: 15px; font-weight: 700; color: #111827; margin: 0 0 6px; }
.cl-empty-sub { font-size: 13px; color: #64748b; margin: 0 0 20px; }
.cl-empty-cta {
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
.cl-empty-cta:hover { background: #4f46e5; }
.cl-foot {
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
