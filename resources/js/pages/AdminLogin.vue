<template>
  <div class="login-card">
    <div class="brand">
      <div class="mark"><i class="fas fa-shield-alt"></i></div>
      <span>Admin</span>
    </div>
    <div class="body">
      <div class="hero">
        <h1>Login</h1>
        <p>Use your admin account to continue.</p>
      </div>

      <div v-if="error" class="alert" role="alert">{{ error }}</div>

      <form @submit.prevent="submit" class="login-form">
        <div class="form-group">
          <label for="email">Email</label>
          <div class="input-wrap">
            <i class="fas fa-envelope"></i>
            <input id="email" v-model="email" type="email" placeholder="admin@example.com" required/>
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrap">
            <i class="fas fa-lock"></i>
            <input id="password" v-model="password" :type="showPassword ? 'text' : 'password'" placeholder="Enter password" required/>
            <span class="toggle-password" @click="showPassword = !showPassword" aria-label="Toggle password visibility">
              <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
            </span>
          </div>
        </div>

        <div class="options">
          <label>
            <input v-model="remember" type="checkbox" />
            <span>Remember me</span>
          </label>
          <a href="#">Forgot password?</a>
        </div>

        <button class="btn-login" type="submit" :disabled="loading">
          <span v-if="!loading">Login</span>
          <span v-else>Signing in...</span>
        </button>
      </form>

      <div class="footer">Secure admin access only</div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const email = ref('');
const password = ref('');
const remember = ref(false);
const showPassword = ref(false);
const loading = ref(false);
const error = ref('');

async function submit() {
  error.value = '';
  loading.value = true;
  try {
    const { data } = await axios.post('/admin/login', {
      email: email.value,
      password: password.value,
      remember: remember.value ? true : undefined,
    });

    if (data.redirect) {
      window.location.href = data.redirect;
    }
  } catch (e) {
    if (e.response?.data?.message) {
      error.value = e.response.data.message;
    } else if (e.response?.data?.errors) {
      error.value = Object.values(e.response.data.errors).flat().join(' ');
    } else {
      error.value = 'Unable to sign in. Please try again.';
    }
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.login-card {
  width: 100%;
  max-width: 430px;
  margin: 20px;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 1px 1px rgba(15,23,42,0.05), 0 10px 30px rgba(124,58,237,0.12);
}
.brand {
  padding: 22px 24px 0;
  display: flex;
  align-items: center;
  gap: 12px;
}
.brand .mark {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  background: linear-gradient(135deg, #7c3aed, #06b6d4);
  display: grid;
  place-items: center;
  color: #fff;
  font-size: 14px;
  flex-shrink: 0;
}
.brand span {
  font-weight: 700;
  font-size: 16px;
  letter-spacing: 0.2px;
  color: #1e1b4b;
}
.body {
  padding: 22px 24px 28px;
}
.hero {
  margin-bottom: 18px;
}
.hero h1 {
  font-size: 28px;
  font-weight: 800;
  line-height: 1.15;
  color: #1e1b4b;
}
.hero p {
  margin-top: 8px;
  color: #64748b;
  font-size: 14px;
  line-height: 1.55;
}
.alert {
  background: #fff1f2;
  color: #9f1239;
  border: 1px solid #fecdd3;
  padding: 12px 14px;
  border-radius: 12px;
  margin-bottom: 16px;
  font-size: 13.5px;
}
.login-form .form-group {
  margin-bottom: 16px;
}
.login-form label {
  display: block;
  margin-bottom: 6px;
  font-weight: 600;
  font-size: 14px;
  color: #0f172a;
}
.input-wrap {
  position: relative;
}
.input-wrap .fa {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  left: 14px;
  color: #94a3b8;
  font-size: 16px;
  pointer-events: none;
}
.input-wrap input {
  width: 100%;
  padding: 14px 16px 14px 42px;
  border: 1.5px solid #e5e7eb;
  outline: none;
  border-radius: 12px;
  background: #f7fafc;
  color: #0f172a;
  font-size: 15px;
  transition: all 160ms ease-in-out;
}
.input-wrap input::placeholder {
  color: #94a3b8;
}
.input-wrap input:focus {
  border-color: #a78bfa;
  background: #ffffff;
  box-shadow: 0 0 0 4px rgba(124,58,237,0.12);
}
.toggle-password {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: #94a3b8;
  font-size: 16px;
}
.options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 18px 0 22px;
  font-size: 13px;
}
.options label {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  color: #0f172a;
}
.options input[type="checkbox"] {
  width: 16px;
  height: 16px;
  accent-color: #7c3aed;
}
.options a {
  color: #7c3aed;
  text-decoration: none;
  font-weight: 600;
}
.options a:hover {
  color: #5b21b6;
}
.btn-login {
  width: 100%;
  border: none;
  padding: 15px;
  border-radius: 12px;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  color: white;
  background: linear-gradient(135deg, #7c3aed, #06b6d4);
  transition: transform 80ms ease, box-shadow 160ms ease;
}
.btn-login:hover {
  transform: translateY(-1px);
  box-shadow: 0 10px 24px rgba(124,58,237,0.28);
}
.btn-login:active {
  transform: translateY(0);
}
.footer {
  margin-top: 18px;
  text-align: center;
  color: #64748b;
  font-size: 12.5px;
}
</style>
