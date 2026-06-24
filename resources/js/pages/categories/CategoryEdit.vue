<template>
  <AdminLayout>
    <template #header>
      <h1>Edit Category</h1>
      <p>Update category details.</p>
    </template>

    <template #toolbar>
      <router-link to="/admin/categories" class="btn btn-secondary">
        <i class="fas fa-xmark"></i> Back
      </router-link>
      <div />
    </template>

    <template #content>
      <form @submit.prevent="submit" class="form-card">
        <div v-if="serverErrors" class="alert" style="background:#fef2f2;border-color:#fecaca;color:#991b1b;">
          <ul style="margin:0;padding-left:18px;">
            <li v-for="(error, index) in serverErrors" :key="index">{{ error }}</li>
          </ul>
        </div>

        <div class="form-grid" style="margin-top:14px;">
          <div class="field">
            <label>Category Name</label>
            <input v-model="form.name" type="text" placeholder="Enter category name" required maxlength="255" />
            <span class="hint">Required. Max 255 characters.</span>
          </div>

          <div class="actions" style="justify-content: flex-end;">
            <router-link to="/admin/categories" class="btn btn-secondary">Cancel</router-link>
            <button type="submit" class="btn btn-primary">Update Category</button>
          </div>
        </div>
      </form>
    </template>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import AdminLayout from '../../layouts/AdminLayout.vue';

const route = useRoute();
const form = ref({ id: null, name: '' });
const serverErrors = ref([]);

onMounted(async () => {
  const id = route.params.id;
  if (!id) return;
  try {
    const { data } = await axios.get(`/admin/categories/${id}/edit`);
    const payload = data?.data ?? data;
    form.value = {
      id,
      name: payload?.name ?? '',
    };
  } catch {
    form.value.name = '';
  }
});

async function submit() {
  serverErrors.value = [];
  try {
    const payload = buildPayload();
    const { data } = await axios.post(`/admin/categories/${form.value.id}`, payload, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    if (data.redirect) navigateToList(data.redirect);
  } catch (error) {
    serverErrors.value = normaliseErrors(error);
  }
}

function buildPayload() {
  const data = new FormData();
  data.append('_method', 'PUT');
  data.append('name', form.value.name || '');
  data.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
  return data;
}

function normaliseErrors(error) {
  if (error.response?.data?.errors) {
    return Object.values(error.response.data.errors).flat();
  }
  if (error.response?.data?.message) {
    return [error.response.data.message];
  }
  return [];
}

function navigateToList(redirect) {
  window.location.href = redirect || '/admin/categories';
}
</script>

<style>
.form-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.04);
  padding: 22px;
}
.form-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 18px;
}
.field {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.field label {
  font-size: 13px;
  font-weight: 600;
  color: #374151;
}
.field input {
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid #e5e7eb;
  background: #fff;
  font-size: 14px;
  color: #111827;
  outline: none;
}
.field input:focus {
  border-color: #10b981;
  box-shadow: 0 0 0 3px rgba(16,185,129,0.18);
}
.hint {
  font-size: 12px;
  color: #6b7280;
}
.actions {
  display: flex;
  align-items: center;
  gap: 6px;
  justify-content: flex-end;
}
.alert {
  border-radius: 10px;
  padding: 12px 14px;
  font-size: 13px;
  margin-bottom: 6px;
}
</style>
