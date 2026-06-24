<template>
  <div class="admin-shell">
    <aside class="sidebar">
      <div class="sidebar-brand">
        <div class="icon">A</div>
        <span>Admin</span>
      </div>
      <nav class="sidebar-nav">
        <router-link to="/admin/dashboard" active-class="active" class="nav-item">
          <i class="fas fa-th-large"></i> Dashboard
        </router-link>
        <router-link to="/admin/categories" active-class="active" class="nav-item" exact-active-class="active">
          <i class="fas fa-list"></i> Categories
        </router-link>
        <router-link to="/admin/products" active-class="active" class="nav-item" exact-active-class="active">
          <i class="fas fa-box"></i> Products
        </router-link>
        <router-link to="/admin/orders" active-class="active" class="nav-item" exact-active-class="active">
          <i class="fas fa-shopping-bag"></i> Orders
        </router-link>
      </nav>
      <div class="logout">
        <a href="#" @click.prevent="logout" class="nav-item">
          <i class="fas fa-arrow-right-from-bracket"></i> Logout
        </a>
      </div>
    </aside>

    <main class="main">
      <div v-if="flash" class="alert" role="status">{{ flash }}</div>

      <header v-if="$slots.header" class="page-header">
        <slot name="header" />
      </header>

      <div v-if="$slots.toolbar" class="toolbar">
        <slot name="toolbar" />
      </div>

      <section v-if="$slots.content" class="card">
        <slot name="content" />
      </section>

      <slot />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const flash = ref('');
let flashTimer = null;

onMounted(() => {
  const status = sessionStorage.getItem('admin_flash_status');
  const time = sessionStorage.getItem('admin_flash_time');
  if (status && time && Date.now() - Number(time) < 3000) {
    flash.value = status;
    flashTimer = window.setTimeout(() => {
      flash.value = '';
    }, 2500);
    sessionStorage.removeItem('admin_flash_status');
    sessionStorage.removeItem('admin_flash_time');
  }
});

async function logout() {
  try {
    await axios.post('/admin/logout', {}, { headers: { 'X-CSRF-TOKEN': window.csrfToken || '' } });
    window.__ADMIN_GUARD__ = false;
  } catch {
    window.__ADMIN_GUARD__ = false;
  } finally {
    window.location.href = '/admin/login';
  }
}
</script>

<style scoped>
.admin-shell {
  font-family: -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  background: #e5e7eb;
  color: #111827;
  min-height: 100vh;
}
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  width: 260px;
  background: #ffffff;
  border-right: 1px solid #e5e7eb;
  padding: 22px 16px;
  display: flex;
  flex-direction: column;
}
.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 18px;
  padding: 2px 6px;
}
.sidebar-brand .icon {
  width: 32px;
  height: 32px;
  border-radius: 10px;
  background: #e5e7eb;
  color: #111827;
  display: grid;
  place-items: center;
  font-weight: 700;
  font-size: 14px;
}
.sidebar-brand span {
  font-size: 20px;
  font-weight: 600;
  letter-spacing: -0.3px;
}
.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 10px;
  color: #374151;
  font-size: 14px;
  font-weight: 600;
}
.nav-item:hover {
  background: #f3f4f6;
}
.nav-item.active {
  background: #e5e7eb;
  color: #000000;
  font-weight: 700;
}
.nav-item i {
  width: 18px;
  text-align: center;
  color: #6b7280;
}
.nav-item.active i {
  color: #111827;
}
.logout {
  margin-top: auto;
}
.main {
  margin-left: 260px;
  min-height: 100vh;
  padding: 28px 28px 40px;
  background: #e5e7eb;
}
.page-header {
  margin-bottom: 18px;
}
.page-header :deep(h1) {
  font-size: 26px;
  font-weight: 700;
  margin: 0 0 4px;
}
.page-header :deep(p) {
  margin: 0;
  color: #6b7280;
  font-size: 14px;
}
.toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
  margin-bottom: 18px;
}
.toolbar-title {
  font-size: 20px;
  font-weight: 700;
  margin: 0;
}
.card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}
.alert {
  border-radius: 10px;
  padding: 12px 14px;
  font-size: 13px;
  background: #f0fdf4;
  color: #14532d;
  border: 1px solid #bbf7d0;
  margin-bottom: 16px;
}
</style>
