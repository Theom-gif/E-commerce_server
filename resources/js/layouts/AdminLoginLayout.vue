<template>
  <div class="admin-login">
    <slot/>
  </div>
</template>

<script setup>
import AdminLoginPage from '../pages/AdminLogin.vue';
</script>

<style>
.admin-login {
  min-height: 100vh;
  display: grid;
  place-items: center;
  background:
    radial-gradient(circle at 15% 15%, #ede9fe 0%, transparent 45%),
    radial-gradient(circle at 85% 80%, #cffafe 0%, transparent 45%),
    linear-gradient(135deg, #ffffff 0%, #f5f3ff 100%);
  padding: 20px;
  font-family: -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}
</style>
