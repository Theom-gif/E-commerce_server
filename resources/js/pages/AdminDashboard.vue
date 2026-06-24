<template>
  <AdminLayout>
    <template #header>
      <h1>Dashboard</h1>
      <p>Welcome back! Here is your business overview.</p>
    </template>

    <template #toolbar>
      <h2 class="toolbar-title">Overview</h2>
      <div />
    </template>

    <template #content>
      <div class="dashboard">
        <div class="kpi-grid">
          <div class="kpi-card blue">
            <p class="kpi-label">Total Users</p>
            <div class="kpi-row">
              <p class="kpi-value">{{ numberFormat(totalUsers) }}</p>
              <div class="kpi-icon"><i class="fas fa-users"></i></div>
            </div>
            <div class="kpi-foot">All registered accounts</div>
          </div>

          <div class="kpi-card green">
            <p class="kpi-label">Total Orders</p>
            <div class="kpi-row">
              <p class="kpi-value">{{ numberFormat(totalOrders) }}</p>
              <div class="kpi-icon"><i class="fas fa-shopping-bag"></i></div>
            </div>
            <div class="kpi-foot">Lifetime orders placed</div>
          </div>

          <div class="kpi-card purple">
            <p class="kpi-label">Total Revenue</p>
            <div class="kpi-row">
              <p class="kpi-value">${{ numberFormat(totalRevenue, 2) }}</p>
              <div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div>
            </div>
            <div class="kpi-foot">Cumulative earnings</div>
          </div>

          <div class="kpi-card amber">
            <p class="kpi-label">Top Products</p>
            <div class="kpi-row">
              <p class="kpi-value">{{ numberFormat(totalProducts) }}</p>
              <div class="kpi-icon"><i class="fas fa-box"></i></div>
            </div>
            <div class="kpi-foot">Active product listings</div>
          </div>
        </div>

        <div class="bottom-grid">
          <div class="panel">
            <div class="panel-head"><p class="panel-title">Order Status</p></div>
            <div class="panel-body">
              <div v-for="(label, key) in statuses" :key="key" class="status-item">
                <div class="status-row">
                  <span class="status-name">{{ label }}</span>
                  <span class="status-count">{{ getCount(key) }}</span>
                </div>
                <div class="bar-track">
                  <div
                    class="bar-fill"
                    :class="key"
                    :style="{ width: pct(getCount(key)) + '%' }"
                  />
                </div>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-head">
              <p class="panel-title">Monthly Revenue</p>
              <span class="panel-badge">This Year</span>
            </div>
            <div class="panel-body">
              <div class="rev-grid">
                <div v-for="month in months" :key="month.value" class="rev-col">
                  <div class="rev-tip">${{ numberFormat(month.amount, 0) }}</div>
                  <div class="rev-bar-wrap">
                    <div class="rev-bar" :style="{ height: Math.max(month.pct, 0) + '%' }" />
                  </div>
                  <span class="rev-month">{{ month.label }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </AdminLayout>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';
import AdminLayout from '../layouts/AdminLayout.vue';

const totalUsers = ref(0);
const totalOrders = ref(0);
const totalProducts = ref(0);
const totalRevenue = ref(0);
const orderStatus = ref({});

const statuses = {
  pending: 'Pending',
  processing: 'Processing',
  completed: 'Completed',
  cancelled: 'Cancelled',
  shipped: 'Shipped',
  delivered: 'Delivered',
};

const monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

onMounted(async () => {
  try {
    const { data } = await axios.get('/admin/dashboard');
    const payload = data?.data ?? data ?? {};
    totalUsers.value = payload.totalUsers ?? 0;
    totalOrders.value = payload.totalOrders ?? 0;
    totalProducts.value = payload.totalProducts ?? 0;
    totalRevenue.value = payload.totalRevenue ?? 0;
    orderStatus.value = payload.orderStatus ?? {};
  } catch {
    totalUsers.value = 0;
    totalOrders.value = 0;
    totalProducts.value = 0;
    totalRevenue.value = 0;
    orderStatus.value = {};
  }
});

function getCount(key) {
  return Number(orderStatus.value[key] ?? 0);
}

function pct(count) {
  const max = Math.max(1, ...Object.values(orderStatus.value).map(Number));
  return Math.round((count / max) * 100);
}

const months = computed(() => {
  const monthly = {};
  try {
    const raw = orderStatus.value;
  } catch {}
  return monthNames.map((label, idx) => {
    const amount = 0;
    return { label, value: idx + 1, amount, pct: 0 };
  });
});

function numberFormat(value, decimals = 0) {
  if (value === null || value === undefined) return '0';
  return Number(value).toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
}
</script>

<style scoped>
.dashboard {
  background: #f3f4f6;
  border-radius: 18px;
  padding: 22px;
  border: 1px solid #e5e7eb;
}
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 24px;
}
.kpi-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 16px;
  padding: 18px 18px 14px;
  position: relative;
  overflow: hidden;
}
.kpi-card::before {
  content: '';
  position: absolute;
  inset: 0 0 auto 0;
  height: 3px;
}
.kpi-card.blue::before { background: #3b82f6; }
.kpi-card.green::before { background: #10b981; }
.kpi-card.purple::before { background: #8b5cf6; }
.kpi-card.amber::before { background: #f59e0b; }

.kpi-label {
  margin: 6px 0 10px;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: #6b7280;
}
.kpi-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
}
.kpi-value {
  margin: 0;
  font-size: 26px;
  font-weight: 800;
  color: #111827;
  line-height: 1.1;
}
.kpi-icon {
  width: 38px;
  height: 38px;
  border-radius: 12px;
  display: grid;
  place-items: center;
  font-size: 14px;
  flex-shrink: 0;
}
.kpi-card.blue .kpi-icon { background: #eff6ff; color: #3b82f6; }
.kpi-card.green .kpi-icon { background: #ecfdf5; color: #10b981; }
.kpi-card.purple .kpi-icon { background: #f5f3ff; color: #8b5cf6; }
.kpi-card.amber .kpi-icon { background: #fffbeb; color: #f59e0b; }

.kpi-foot {
  margin: 10px 0 0;
  padding-top: 10px;
  border-top: 1px solid #e5e7eb;
  font-size: 12px;
  color: #6b7280;
}

.bottom-grid {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 16px;
}
.panel {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 16px;
  overflow: hidden;
}
.panel-head {
  padding: 14px 16px;
  border-bottom: 1px solid #e5e7eb;
  background: #f9fafb;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
}
.panel-title {
  margin: 0;
  font-size: 12px;
  font-weight: 700;
  color: #111827;
  letter-spacing: 0.04em;
  text-transform: uppercase;
}
.panel-badge {
  font-size: 11px;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 999px;
  background: #eef2ff;
  color: #6366f1;
}
.panel-body {
  padding: 16px;
}

.status-item + .status-item {
  margin-top: 12px;
}
.status-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 5px;
}
.status-name {
  font-size: 13px;
  font-weight: 600;
  color: #111827;
}
.status-count {
  font-size: 12px;
  color: #6b7280;
}
.bar-track {
  height: 6px;
  background: #eef2ff;
  border-radius: 999px;
  overflow: hidden;
}
.bar-fill {
  height: 100%;
  background: #6366f1;
  border-radius: 999px;
  transition: width 0.4s ease;
}
.bar-fill.pending { background: #f59e0b; }
.bar-fill.processing { background: #3b82f6; }
.bar-fill.completed { background: #10b981; }
.bar-fill.cancelled { background: #ef4444; }
.bar-fill.shipped { background: #8b5cf6; }
.bar-fill.delivered { background: #06b6d4; }

.rev-grid {
  display: grid;
  grid-template-columns: repeat(12, 1fr);
  gap: 6px;
  align-items: flex-end;
  height: 120px;
}
.rev-col {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-end;
  height: 100%;
  gap: 5px;
  position: relative;
}
.rev-bar-wrap {
  width: 100%;
  flex: 1;
  display: flex;
  align-items: flex-end;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  overflow: hidden;
}
.rev-bar {
  width: 100%;
  background: #6366f1;
  opacity: 0.9;
  border-radius: 4px 4px 0 0;
  min-height: 3px;
}
.rev-tip {
  display: none;
  position: absolute;
  bottom: calc(100% + 6px);
  left: 50%;
  transform: translateX(-50%);
  background: #111827;
  color: #ffffff;
  font-size: 10px;
  font-weight: 700;
  padding: 3px 7px;
  border-radius: 5px;
  white-space: nowrap;
  pointer-events: none;
  z-index: 10;
}
.rev-col:hover .rev-tip {
  display: block;
}
.rev-month {
  font-size: 10px;
  font-weight: 700;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  white-space: nowrap;
}
@media (max-width: 1100px) {
  .kpi-grid { grid-template-columns: repeat(2, 1fr); }
  .bottom-grid { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
  .kpi-grid { grid-template-columns: 1fr; }
}
</style>
