import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import AdminLogin from './pages/AdminLogin.vue';
import AdminLayout from './layouts/AdminLayout.vue';
import AdminDashboard from './pages/AdminDashboard.vue';
import CategoriesIndex from './pages/categories/CategoriesIndex.vue';
import CategoryCreate from './pages/categories/CategoryCreate.vue';
import CategoryEdit from './pages/categories/CategoryEdit.vue';
import ProductsIndex from './pages/products/ProductsIndex.vue';
import ProductCreate from './pages/products/ProductCreate.vue';
import ProductEdit from './pages/products/ProductEdit.vue';
import OrdersIndex from './pages/orders/OrdersIndex.vue';

import axios from 'axios';
window.axios = axios;

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/admin/login', component: AdminLogin, meta: { guest: true } },
    {
      path: '/admin',
      component: AdminLayout,
      meta: { requiresAuth: true },
      children: [
        { path: 'dashboard', component: AdminDashboard },
        { path: 'categories', component: CategoriesIndex },
        { path: 'categories/create', component: CategoryCreate },
        { path: 'categories/:id/edit', component: CategoryEdit },
        { path: 'products', component: ProductsIndex },
        { path: 'products/create', component: ProductCreate },
        { path: 'products/:id/edit', component: ProductEdit },
        { path: 'orders', component: OrdersIndex }
      ]
    },
    { path: '/', redirect: '/admin/dashboard' }
  ]
});

router.beforeEach((to, from) => {
  if (to.meta.requiresAuth && !window.__ADMIN_GUARD__) {
    return { path: '/admin/login' };
  }
  if (to.meta.guest && window.__ADMIN_GUARD__) {
    return { path: '/admin/dashboard' };
  }
});

createApp({}).use(router).mount('#app');
