<template>
  <AdminLayout>
    <template #header>
      <h1>New Category</h1>
      <p>Create a new category to organize products.</p>
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
            <button type="submit" class="btn btn-primary">Create Category</button>
          </div>
        </div>
      </form>
    </template>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AdminLayout from '../../layouts/AdminLayout.vue';

const form = ref({ name: '' });
const serverErrors = ref([]);

async function submit() {
  serverErrors.value = [];
  try {
    const { data } = await axios.post('/admin/categories', buildPayload());
    if (data.redirect) navigateToList(data.redirect);
  } catch (error) {
    serverErrors.value = normaliseErrors(error);
  }
}

function buildPayload() {
  const data = new FormData();
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

onMounted(() => {
  console.log('Category create ready');
});
</script>
