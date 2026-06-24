<template>
  <AdminLayout>
    <template #header>
      <h1>Edit Product</h1>
      <p>Update product details and image.</p>
    </template>

    <template #toolbar>
      <router-link to="/admin/products" class="btn btn-secondary">
        <i class="fas fa-xmark"></i> Back
      </router-link>
      <div />
    </template>

    <template #content>
      <form @submit.prevent="submit" enctype="multipart/form-data" class="ep">
        <div>
          <div v-if="serverErrors" class="ep-alert" style="background:#fef2f2;border-color:#fecaca;border-left-color:#ef4444;">
            <i class="fas fa-circle-exclamation" style="color:#ef4444;margin-top:2px;font-size:14px;"></i>
            <ul>
              <li v-for="(error, index) in serverErrors" :key="index">{{ error }}</li>
            </ul>
          </div>
          <div v-if="successMessage" class="ep-alert" style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;">
            <i class="fas fa-circle-check" style="color:#16a34a;margin-top:2px;font-size:14px;"></i>
            <span style="font-size:13px;color:#14532d;line-height:1.6;">{{ successMessage }}</span>
          </div>

          <div class="ep-panel" style="margin-bottom:16px;">
            <div class="ep-panel-head">
              <div class="ep-panel-head-icon"><i class="fas fa-tag"></i></div>
              <div>
                <p class="ep-panel-head-title">Basic Information</p>
                <p class="ep-panel-head-sub">Name, category and description</p>
              </div>
            </div>
            <div class="ep-panel-body">
              <div class="ep-row">
                <div class="ep-field">
                  <label class="ep-label">Product Name <span class="ep-req">*</span></label>
                  <input v-model="form.name" type="text" placeholder="Enter product name" required maxlength="255" />
                </div>
                <div class="ep-field">
                  <label class="ep-label">Category <span class="ep-req">*</span></label>
                  <select v-model="form.category_id" required>
                    <option value="">Select category</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id" :selected="form.category_id === category.id">
                      {{ category.name }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="ep-row full">
                <div class="ep-field">
                  <label class="ep-label">Description</label>
                  <textarea v-model="form.description" rows="4" placeholder="Optional product description" />
                </div>
              </div>
            </div>
          </div>

          <div class="ep-panel">
            <div class="ep-panel-head">
              <div class="ep-panel-head-icon" style="background:#ECFDF5;color:#10B981;"><i class="fas fa-dollar-sign"></i></div>
              <div>
                <p class="ep-panel-head-title">Pricing & Inventory</p>
                <p class="ep-panel-head-sub">Price and available stock</p>
              </div>
            </div>
            <div class="ep-panel-body">
              <div class="ep-row">
                <div class="ep-field">
                  <label class="ep-label">Price <span class="ep-req">*</span></label>
                  <input v-model="form.price" type="number" step="0.01" min="0" placeholder="0.00" required />
                </div>
                <div class="ep-field">
                  <label class="ep-label">Stock <span class="ep-req">*</span></label>
                  <input v-model="form.stock" type="number" min="0" placeholder="0" required />
                </div>
              </div>
              <div class="ep-actions">
                <router-link to="/admin/products" class="ep-btn ep-btn-ghost">
                  <i class="fas fa-xmark"></i> Cancel
                </router-link>
                <button type="submit" class="ep-btn ep-btn-primary">
                  <i class="fas fa-floppy-disk"></i> Save Changes
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="ep-img-panel">
          <div class="ep-panel">
            <div class="ep-panel-head">
              <div class="ep-panel-head-icon" style="background:#FFF7ED;color:#F59E0B;"><i class="fas fa-image"></i></div>
              <div>
                <p class="ep-panel-head-title">Product Image</p>
                <p class="ep-panel-head-sub">JPG, PNG or WEBP</p>
              </div>
            </div>
            <div class="ep-panel-body" style="display:flex;flex-direction:column;gap:14px;">
              <div class="ep-img-preview" :class="{ 'has-image': previewUrl || initialImageUrl }">
                <div v-if="!previewUrl && !initialImageUrl" class="ep-img-placeholder">
                  <i class="fas fa-image"></i>
                  <span>No image yet</span>
                </div>
                <img v-if="previewUrl || initialImageUrl" :src="previewUrl || initialImageUrl" alt="Product preview" @error="imageUrl = initialImageUrl || ''" />
              </div>

              <label class="ep-upload-label">
                <i class="fas fa-arrow-up-from-bracket"></i>
                <span>{{ fileInput.files[0]?.name || 'Choose new image…' }}</span>
                <input ref="fileInput" type="file" name="image" accept="image/*" @change="onFileChange" />
              </label>
            </div>
          </div>
        </div>
      </form>
    </template>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import AdminLayout from '../../layouts/AdminLayout.vue';

const route = useRoute();
const router = useRouter();
const form = ref({
  id: null,
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
const initialImageUrl = ref('');
const fileInput = ref(null);

onMounted(async () => {
  const id = route.params.id;
  if (id) {
    try {
      const { data } = await axios.get(`/admin/products/${id}/edit`);
      const payload = data?.data ?? data;
      form.value = {
        id,
        name: payload?.name ?? '',
        category_id: payload?.category_id ?? payload?.categoryId ?? '',
        description: payload?.description ?? '',
        price: payload?.price ?? 0,
        stock: payload?.stock ?? 0,
      };
      initialImageUrl.value = imageUrlFor(payload);
    } catch {
      form.value.id = id;
    }
  }
  try {
    const { data } = await axios.get('/admin/products/create');
    const payload = data?.data ?? data;
    categories.value = Array.isArray(payload?.categories) ? payload.categories : [];
  } catch {
    categories.value = [];
  }
});

onMounted(() => {});

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
  data.append('_method', 'PUT');
  data.append('name', form.value.name || '');
  data.append('category_id', String(form.value.category_id || ''));
  data.append('description', form.value.description || '');
  data.append('price', String(form.value.price ?? 0));
  data.append('stock', String(form.value.stock ?? 0));
  if (fileInput.value?.files?.[0]) {
    data.append('image', fileInput.value.files[0]);
  }
  data.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

  try {
    const { data: response } = await axios.post(`/admin/products/${form.value.id}`, data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    successMessage.value = 'Product updated successfully.';
    initialImageUrl.value = previewUrl.value || initialImageUrl.value;
    previewUrl.value = '';
  } catch (error) {
    serverErrors.value = normaliseErrors(error);
  }
}

function imageUrlFor(payload) {
  if (!payload) return '';
  const image = payload.image;
  if (!image) return '';
  if (typeof image === 'string') {
    return image.startsWith('http') ? image : `/storage/${image}`;
  }
  return '';
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
.ep {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 20px;
  align-items: start;
}
@media (max-width: 860px) {
  .ep { grid-template-columns: 1fr; }
}
.ep-panel {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.05);
  overflow: hidden;
}
.ep-panel-head {
  padding: 16px 22px;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  align-items: center;
  gap: 10px;
  background: #f8fafc;
}
.ep-panel-head-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background: #eef2ff;
  color: #6366f1;
  display: grid;
  place-items: center;
  font-size: 13px;
  flex-shrink: 0;
}
.ep-panel-head-title { font-size: 13px; font-weight: 700; color: #111827; margin: 0; }
.ep-panel-head-sub { font-size: 11px; color: #64748b; margin: 0; }
.ep-panel-body { padding: 22px; }
.ep-alert {
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
.ep-alert ul { margin: 0; padding-left: 16px; }
.ep-alert li { font-size: 13px; color: #b91c1c; line-height: 1.6; }
.ep-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-bottom: 16px;
}
.ep-row.full { grid-template-columns: 1fr; }
.ep-field { display: flex; flex-direction: column; gap: 6px; }
.ep-label {
  font-size: 12px;
  font-weight: 600;
  color: #111827;
  letter-spacing: 0.02em;
}
.ep-req { color: #ef4444; margin-left: 2px; font-size: 11px; }
.ep-field input[type="text"],
.ep-field input[type="number"],
.ep-field select,
.ep-field textarea {
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
.ep-field input:focus,
.ep-field select:focus,
.ep-field textarea:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}
.ep-field textarea { resize: vertical; min-height: 96px; }
.ep-img-panel { display: flex; flex-direction: column; gap: 16px; }
.ep-img-preview {
  background: #f8fafc;
  border: 2px dashed #e5e7eb;
  border-radius: 14px;
  min-height: 200px;
  display: flex; align-items: center; justify-content: center;
  overflow: hidden;
  transition: border-color 180ms ease;
}
.ep-img-preview:hover { border-color: #6366f1; }
.ep-img-preview img {
  width: 100%;
  max-height: 240px;
  object-fit: contain;
  border-radius: calc(14px - 2px);
}
.ep-img-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  color: #94a3b8;
  font-size: 12px;
  padding: 24px;
}
.ep-img-placeholder i { font-size: 32px; opacity: .4; }
.ep-upload-label {
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
  transition: border-color 180ms ease, background 180ms ease;
}
.ep-upload-label:hover {
  border-color: #6366f1;
  background: #eef2ff;
  color: #6366f1;
}
.ep-upload-label input[type="file"] { display: none; }
.ep-actions {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 10px;
  padding: 16px 22px;
  border-top: 1px solid #e5e7eb;
  background: #f8fafc;
}
.ep-btn {
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
.ep-btn:active { transform: scale(.97); }
.ep-btn-ghost {
  background: transparent;
  color: #64748b;
  border: 1.5px solid #e5e7eb;
}
.ep-btn-ghost:hover { background: #f8fafc; color: #111827; }
.ep-btn-primary {
  background: #6366f1;
  color: #fff;
  box-shadow: 0 2px 8px rgba(99,102,241,.30);
}
.ep-btn-primary:hover { background: #4f46e5; }
</style>
