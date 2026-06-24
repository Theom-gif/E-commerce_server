<template>
  <AdminLayout>
    <template #header>
      <h1>New Product</h1>
      <p>Add product to catalog.</p>
    </template>

    <template #toolbar>
      <router-link to="/admin/products" class="btn btn-secondary">
        <i class="fas fa-xmark"></i> Back
      </router-link>
      <div />
    </template>

    <template #content>
      <form @submit.prevent="submit" enctype="multipart/form-data" class="np">
        <div>
          <div v-if="serverErrors" class="np-alert" style="background:#fef2f2;border-color:#fecaca;border-left-color:#ef4444;margin-bottom:20px;">
            <i class="fas fa-circle-exclamation" style="color:#ef4444;margin-top:2px;font-size:14px;"></i>
            <ul>
              <li v-for="(error, index) in serverErrors" :key="index">{{ error }}</li>
            </ul>
          </div>

          <div v-if="successMessage" class="np-alert" style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;margin-bottom:20px;">
            <i class="fas fa-circle-check" style="color:#16a34a;margin-top:2px;font-size:14px;"></i>
            <span style="font-size:13px;color:#14532d;line-height:1.6;">{{ successMessage }}</span>
          </div>

          <div class="np-panel">
            <div class="np-panel-head">
              <div class="np-panel-icon" style="background:#EEF2FF;color:#6366F1;">
                <i class="fas fa-tag"></i>
              </div>
              <div>
                <p class="np-panel-title">Basic Information</p>
                <p class="np-panel-sub">Name, category and description</p>
              </div>
            </div>
            <div class="np-panel-body">
              <div class="np-row">
                <div class="np-field">
                  <label class="np-label">Product Name <span style="color:#ef4444;margin-left:2px;">*</span></label>
                  <input v-model="form.name" type="text" placeholder="Enter product name" required maxlength="255" />
                </div>
                <div class="np-field">
                  <label class="np-label">Category <span style="color:#ef4444;margin-left:2px;">*</span></label>
                  <select v-model="form.category_id" required>
                    <option value="">Choose category</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                  </select>
                </div>
              </div>
              <div class="np-row full">
                <div class="np-field">
                  <label class="np-label">Description</label>
                  <textarea v-model="form.description" rows="4" placeholder="Optional product description" />
                  <span class="np-hint">Shown on the product page. Optional.</span>
                </div>
              </div>
            </div>
          </div>

          <div class="np-panel">
            <div class="np-panel-head">
              <div class="np-panel-icon" style="background:#ECFDF5;color:#10B981;">
                <i class="fas fa-dollar-sign"></i>
              </div>
              <div>
                <p class="np-panel-title">Pricing & Inventory</p>
                <p class="np-panel-sub">Price and available stock</p>
              </div>
            </div>
            <div class="np-panel-body">
              <div class="np-row">
                <div class="np-field">
                  <label class="np-label">Price <span style="color:#ef4444;margin-left:2px;">*</span></label>
                  <input v-model="form.price" type="number" step="0.01" min="0" placeholder="0.00" required />
                </div>
                <div class="np-field">
                  <label class="np-label">Stock <span style="color:#ef4444;margin-left:2px;">*</span></label>
                  <input v-model="form.stock" type="number" min="0" placeholder="0" required />
                </div>
              </div>
              <div class="np-actions">
                <router-link to="/admin/products" class="np-btn np-btn-ghost">
                  <i class="fas fa-xmark"></i> Cancel
                </router-link>
                <button type="submit" class="np-btn np-btn-primary">
                  <i class="fas fa-plus"></i> Create Product
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="np-img-panel">
          <div class="np-panel">
            <div class="np-panel-head">
              <div class="np-panel-icon" style="background:#FFF7ED;color:#F59E0B;">
                <i class="fas fa-image"></i>
              </div>
              <div>
                <p class="np-panel-title">Product Image</p>
                <p class="np-panel-sub">JPG, PNG or WEBP</p>
              </div>
            </div>
            <div class="np-panel-body" style="display:flex;flex-direction:column;gap:12px;">
              <div class="np-img-preview" :class="{ 'has-image': previewUrl }">
                <div v-if="!previewUrl" class="np-img-placeholder">
                  <i class="fas fa-image"></i>
                  <span>Preview appears here</span>
                </div>
                <img v-if="previewUrl" :src="previewUrl" alt="Preview" />
              </div>

              <label class="np-upload-label">
                <i class="fas fa-arrow-up-from-bracket"></i>
                <span>{{ fileInput.files[0]?.name || 'Choose image…' }}</span>
                <input ref="fileInput" type="file" name="image" @change="onFileChange" accept="image/*" />
              </label>
              <p class="np-upload-hint">Optional. JPEG or PNG recommended.</p>
            </div>
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

const form = ref({
  name: '',
  category_id: '',
  description: '',
  price: 0,
  stock: 0,
});
const categories = ref([]);
const serverErrors = ref([]);
const successMessage = ref('');
const previewUrl = ref('');
const fileInput = ref(null);

onMounted(async () => {
  try {
    const { data } = await axios.get('/admin/products/create');
    const payload = data?.data ?? data;
    categories.value = Array.isArray(payload?.categories) ? payload.categories : [];
  } catch {
    categories.value = [];
  }
});

function onFileChange() {
  const file = fileInput.value?.files?.[0];
  if (!file) {
    previewUrl.value = '';
    return;
  }
  previewUrl.value = URL.createObjectURL(file);
}

async function submit() {
  serverErrors.value = [];
  successMessage.value = '';
  const data = new FormData();
  data.append('name', form.value.name || '');
  data.append('category_id', form.value.category_id || '');
  data.append('description', form.value.description || '');
  data.append('price', String(form.value.price ?? 0));
  data.append('stock', String(form.value.stock ?? 0));
  if (fileInput.value?.files?.[0]) {
    data.append('image', fileInput.value.files[0]);
  }
  data.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

  try {
    const { data: response } = await axios.post('/admin/products', data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    if (response.redirect) window.location.href = response.redirect;
  } catch (error) {
    serverErrors.value = normaliseErrors(error);
  }
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
</script>

<style scoped>
.np {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 20px;
  align-items: start;
}
@media (max-width: 860px) {
  .np { grid-template-columns: 1fr; }
}
.np-panel {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.05);
  overflow: hidden;
  margin-bottom: 16px;
}
.np-panel:last-child { margin-bottom: 0; }
.np-panel-head {
  padding: 14px 20px;
  border-bottom: 1px solid #e5e7eb;
  background: #f8fafc;
  display: flex;
  align-items: center;
  gap: 10px;
}
.np-panel-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: grid;
  place-items: center;
  font-size: 13px;
  flex-shrink: 0;
}
.np-panel-title { font-size: 13px; font-weight: 700; color: #111827; margin: 0; }
.np-panel-sub { font-size: 11px; color: #64748b; margin: 0; }
.np-panel-body { padding: 20px; }
.np-alert {
  background: #fef2f2;
  border: 1px solid #fecaca;
  border-left: 4px solid #ef4444;
  border-radius: 8px;
  padding: 12px 16px;
  margin-bottom: 20px;
  display: flex;
  gap: 10px;
  align-items: flex-start;
}
.np-alert ul { margin: 0; padding-left: 16px; }
.np-alert li { font-size: 13px; color: #b91c1c; line-height: 1.6; }
.np-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-bottom: 16px;
}
.np-row.full { grid-template-columns: 1fr; }
.np-row:last-child { margin-bottom: 0; }
.np-field { display: flex; flex-direction: column; gap: 5px; }
.np-label {
  font-size: 12px;
  font-weight: 600;
  color: #111827;
  letter-spacing: 0.02em;
}
.np-hint { font-size: 11px; color: #94a3b8; }
.np-field input[type="text"],
.np-field input[type="number"],
.np-field select,
.np-field textarea {
  width: 100%;
  padding: 10px 13px;
  border: 1.5px solid #e5e7eb;
  border-radius: 8px;
  font-size: 13.5px;
  color: #111827;
  background: #ffffff;
  outline: none;
  transition: border-color 180ms ease, box-shadow 180ms ease;
  box-sizing: border-box;
  font-family: inherit;
}
.np-field input:focus,
.np-field select:focus,
.np-field textarea:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}
.np-field textarea { resize: vertical; min-height: 96px; }
.np-img-panel { display: flex; flex-direction: column; gap: 0; }
.np-img-preview {
  background: #f8fafc;
  border: 2px dashed #e5e7eb;
  border-radius: 14px;
  min-height: 200px;
  display: flex; align-items: center; justify-content: center;
  overflow: hidden;
  transition: border-color 180ms ease;
  margin-bottom: 14px;
}
.np-img-preview.has-image {
  border-style: solid;
  border-color: #e5e7eb;
}
.np-img-preview:hover { border-color: #6366f1; }
.np-img-preview img {
  width: 100%;
  max-height: 240px;
  object-fit: contain;
  display: block;
  border-radius: calc(14px - 2px);
}
.np-img-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  color: #94a3b8;
  font-size: 12px;
  text-align: center;
  padding: 28px;
}
.np-img-placeholder i { font-size: 32px; opacity: .35; }
.np-upload-label {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #f8fafc;
  border: 1.5px solid #e5e7eb;
  border-radius: 8px;
  padding: 10px 14px;
  cursor: pointer;
  font-size: 13px;
  color: #64748b;
  transition: border-color 180ms ease, background 180ms ease, color 180ms ease;
}
.np-upload-label:hover {
  border-color: #6366f1;
  background: #eef2ff;
  color: #6366f1;
}
.np-upload-label input[type="file"] { display: none; }
.np-upload-hint { font-size: 11px; color: #94a3b8; text-align: center; margin-top: 8px; }
.np-actions {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 10px;
  padding: 14px 20px;
  border-top: 1px solid #e5e7eb;
  background: #f8fafc;
}
.np-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 9px 20px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  border: none;
  text-decoration: none;
  transition: background 180ms ease, transform 180ms ease;
  line-height: 1;
}
.np-btn:active { transform: scale(.97); }
.np-btn-ghost {
  background: transparent;
  color: #64748b;
  border: 1.5px solid #e5e7eb;
}
.np-btn-ghost:hover { background: #f8fafc; color: #111827; }
.np-btn-primary {
  background: #6366f1;
  color: #fff;
  box-shadow: 0 2px 8px rgba(99,102,241,.28);
}
.np-btn-primary:hover { background: #4f46e5; }
</style>
