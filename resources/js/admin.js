import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import AdminLoginLayout from './layouts/AdminLoginLayout.vue';
import AdminLayout from './layouts/AdminLayout.vue';
import AdminDashboard from './pages/AdminDashboard.vue';
import CategoriesIndex from './pages/categories/CategoriesIndex.vue';
import CategoryCreate from './pages/categories/CategoryCreate.vue';
import CategoryEdit from './pages/categories/CategoryEdit.vue';
import ProductsIndex from './pages/products/ProductsIndex.vue';
import ProductCreate from './pages/products/ProductCreate.vue';
import ProductEdit from './pages/products/ProductEdit.vue';
import OrdersIndex from './pages/orders/OrdersIndex.vue';

import './bootstrap';

const router = createRouter({
  history: createWebHistory('/'),
  routes: [
    { path: '/admin/login', component: AdminLoginLayout, meta: { guest: true } },
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
    { path: '/', redirect: '/admin/dashboard' },
    { path: '/:pathMatch(.*)*', redirect: '/admin/dashboard' }
  ]
});

createApp({}).use(router).mount('#app');
