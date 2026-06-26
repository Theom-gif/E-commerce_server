@extends('admin.layout')
@section('title', 'Orders')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  .orders-page {
    position: relative;
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    color: #0f172a;
  }

  .orders-page * {
    box-sizing: border-box;
  }

  .orders-page::before,
  .orders-page::after {
    content: '';
    position: fixed;
    pointer-events: none;
    z-index: 0;
    border-radius: 999px;
    filter: blur(18px);
    opacity: 0.42;
  }

  .orders-page::before {
    top: 80px;
    right: -120px;
    width: 260px;
    height: 260px;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.22) 0%, rgba(59, 130, 246, 0) 70%);
  }

  .orders-page::after {
    bottom: 20px;
    left: -100px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.18) 0%, rgba(16, 185, 129, 0) 70%);
  }

  .orders-shell {
    position: relative;
    z-index: 1;
  }

  .orders-hero {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 18px;
    padding: 24px 26px;
    border-radius: 26px;
    color: #fff;
    background:
      radial-gradient(circle at top left, rgba(255, 255, 255, 0.16), transparent 36%),
      linear-gradient(135deg, #0f172a 0%, #111827 48%, #1d4ed8 100%);
    box-shadow: 0 22px 40px rgba(15, 23, 42, 0.18);
    overflow: hidden;
  }

  .orders-hero::after {
    content: '';
    position: absolute;
    inset: auto -40px -60px auto;
    width: 190px;
    height: 190px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
  }

  .orders-hero > * {
    position: relative;
    z-index: 1;
  }

  .orders-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.9);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
  }

  .orders-title {
    margin: 14px 0 0;
    font-size: clamp(26px, 3vw, 38px);
    line-height: 1.05;
    letter-spacing: -0.04em;
    font-weight: 800;
  }

  .orders-subtitle {
    margin: 10px 0 0;
    max-width: 680px;
    font-size: 14px;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.8);
  }

  .orders-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: flex-end;
  }

  .orders-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 16px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    white-space: nowrap;
  }

  .orders-action:hover {
    transform: translateY(-1px);
  }

  .orders-action.primary {
    background: #ffffff;
    color: #0f172a;
    box-shadow: 0 10px 22px rgba(15, 23, 42, 0.16);
  }

  .orders-action.secondary {
    color: #fff;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.16);
  }

  .orders-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin: 18px 0 16px;
  }

  @media (max-width: 1040px) {
    .orders-hero {
      flex-direction: column;
    }

    .orders-actions {
      justify-content: flex-start;
    }
  }

  @media (max-width: 980px) {
    .orders-kpis {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 560px) {
    .orders-kpis {
      grid-template-columns: 1fr;
    }
  }

  .orders-kpi {
    background: #ffffff;
    border: 1px solid #e8edf2;
    border-radius: 18px;
    padding: 18px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
  }

  .orders-kpi-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
  }

  .orders-kpi-label {
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #64748b;
  }

  .orders-kpi-value {
    margin-top: 8px;
    font-size: 29px;
    line-height: 1;
    font-weight: 800;
    letter-spacing: -0.05em;
    color: #0f172a;
  }

  .orders-kpi-sub {
    margin-top: 7px;
    font-size: 12px;
    color: #64748b;
  }

  .orders-kpi-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
    font-size: 18px;
  }

  .orders-kpi.--blue .orders-kpi-icon {
    background: #dbeafe;
    color: #2563eb;
  }

  .orders-kpi.--amber .orders-kpi-icon {
    background: #fef3c7;
    color: #d97706;
  }

  .orders-kpi.--emerald .orders-kpi-icon {
    background: #d1fae5;
    color: #059669;
  }

  .orders-kpi.--violet .orders-kpi-icon {
    background: #ede9fe;
    color: #7c3aed;
  }

  .orders-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 16px;
  }

  .orders-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  .orders-filter {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 14px;
    border-radius: 999px;
    border: 1px solid #dbe3ec;
    background: #fff;
    color: #334155;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
  }

  .orders-filter:hover {
    transform: translateY(-1px);
    border-color: #cbd5e1;
    box-shadow: 0 8px 16px rgba(15, 23, 42, 0.05);
  }

  .orders-filter.active {
    background: #0f172a;
    border-color: #0f172a;
    color: #fff;
    box-shadow: 0 10px 18px rgba(15, 23, 42, 0.14);
  }

  .orders-meta {
    font-size: 12px;
    color: #64748b;
    line-height: 1.6;
  }

  .orders-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.7fr) minmax(280px, 0.85fr);
    gap: 16px;
  }

  @media (max-width: 1100px) {
    .orders-layout {
      grid-template-columns: 1fr;
    }
  }

  .orders-card {
    background: #fff;
    border: 1px solid #e8edf2;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
  }

  .orders-card-head {
    padding: 18px 20px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(180deg, #fbfdff 0%, #ffffff 100%);
  }

  .orders-card-head h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: #0f172a;
  }

  .orders-card-head p {
    margin: 4px 0 0;
    font-size: 12px;
    color: #64748b;
  }

  .orders-card-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 999px;
    background: #eff6ff;
    color: #1d4ed8;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
  }

  .orders-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  .orders-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 860px;
  }

  .orders-table th {
    padding: 14px 20px;
    text-align: left;
    background: #fcfdff;
    border-bottom: 1px solid #eef2f7;
    color: #64748b;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    white-space: nowrap;
  }

  .orders-table td {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #0f172a;
    font-size: 14px;
  }

  .orders-table tbody tr:hover td {
    background: #f8fafc;
  }

  .order-id {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 800;
  }

  .order-customer {
    display: flex;
    flex-direction: column;
    gap: 3px;
  }

  .order-customer strong {
    font-size: 14px;
    font-weight: 700;
  }

  .order-customer span {
    font-size: 12px;
    color: #64748b;
  }

  .order-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
    text-transform: capitalize;
    white-space: nowrap;
  }

  .status-pending {
    background: #fffbeb;
    color: #b45309;
  }

  .status-processing {
    background: #eff6ff;
    color: #1d4ed8;
  }

  .status-shipped {
    background: #eef2ff;
    color: #4f46e5;
  }

  .status-delivered {
    background: #ecfdf5;
    color: #047857;
  }

  .status-cancelled {
    background: #fef2f2;
    color: #b91c1c;
  }

  .status-completed {
    background: #ecfeff;
    color: #0f766e;
  }

  .status-default {
    background: #f1f5f9;
    color: #475569;
  }

  .order-total {
    font-size: 15px;
    font-weight: 800;
    white-space: nowrap;
  }

  .order-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
  }

  .order-icon-btn {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    border: 1px solid #dbe3ec;
    background: #fff;
    color: #334155;
    display: grid;
    place-items: center;
    text-decoration: none;
    transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease, color 0.2s ease;
  }

  .order-icon-btn:hover {
    transform: translateY(-1px);
    border-color: #cbd5e1;
    color: #0f172a;
  }

  .order-icon-btn.delete {
    background: #fef2f2;
    border-color: #fecaca;
    color: #dc2626;
  }

  .order-icon-btn.delete:hover {
    background: #dc2626;
    border-color: #dc2626;
    color: #fff;
  }

  .order-icon-btn.complete {
    background: #ecfdf5;
    border-color: #a7f3d0;
    color: #059669;
  }

  .order-icon-btn.complete:hover {
    background: #059669;
    border-color: #059669;
    color: #fff;
  }

  .orders-empty {
    padding: 56px 24px;
    text-align: center;
  }

  .orders-empty i {
    font-size: 44px;
    color: #cbd5e1;
    margin-bottom: 12px;
  }

  .orders-empty h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
  }

  .orders-empty p {
    margin: 8px 0 0;
    color: #64748b;
    font-size: 13px;
  }

  .orders-pager {
    padding: 16px 20px;
    border-top: 1px solid #f1f5f9;
    background: #fbfdff;
  }

  .orders-side {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  .orders-status-row,
  .orders-mini {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .orders-status-row {
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
  }

  .orders-status-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }

  .orders-status-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .orders-status-dot {
    width: 10px;
    height: 10px;
    border-radius: 999px;
    flex-shrink: 0;
  }

  .orders-status-name {
    font-size: 14px;
    font-weight: 600;
    text-transform: capitalize;
    color: #0f172a;
  }

  .orders-status-count {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
  }

  .orders-mini {
    padding: 14px 0;
    border-bottom: 1px solid #f1f5f9;
    align-items: flex-start;
  }

  .orders-mini:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }

  .orders-mini-id {
    font-weight: 800;
    color: #0f172a;
  }

  .orders-mini-sub {
    margin-top: 3px;
    font-size: 12px;
    color: #64748b;
  }

  .orders-mini-total {
    font-size: 14px;
    font-weight: 800;
    white-space: nowrap;
  }

  .orders-note {
    padding: 14px 16px;
    border-radius: 16px;
    background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
    color: #334155;
    font-size: 13px;
    line-height: 1.6;
  }

  @media (max-width: 640px) {
    .orders-hero {
      padding: 20px;
    }

    .orders-card-head {
      flex-direction: column;
    }

    .orders-table th,
    .orders-table td {
      padding-left: 14px;
      padding-right: 14px;
    }
  }
</style>
@endpush

@section('content')
@php
  $statusOptions = [
    '' => 'All',
    'pending' => 'Pending',
    'processing' => 'Processing',
    'shipped' => 'Shipped',
    'delivered' => 'Delivered',
    'cancelled' => 'Cancelled',
  ];

  $statusColors = [
    'pending' => '#f59e0b',
    'processing' => '#3b82f6',
    'shipped' => '#6366f1',
    'delivered' => '#10b981',
    'cancelled' => '#ef4444',
    'completed' => '#14b8a6',
  ];

  $statusLabels = [
    'pending' => 'Pending',
    'processing' => 'Processing',
    'shipped' => 'Shipped',
    'delivered' => 'Delivered',
    'cancelled' => 'Cancelled',
    'completed' => 'Completed',
  ];

  $selectedStatus = $selectedStatus ?? request('status');
  $pendingOrders = $statusCounts['pending'] ?? 0;
  $processingOrders = $statusCounts['processing'] ?? 0;
  $shippedOrders = $statusCounts['shipped'] ?? 0;
  $deliveredOrders = $statusCounts['delivered'] ?? 0;
  $cancelledOrders = $statusCounts['cancelled'] ?? 0;
@endphp

<div class="orders-page">
  <div class="orders-shell">
    <section class="orders-kpis">
      <div class="orders-kpi --blue">
        <div class="orders-kpi-head">
          <div>
            <span class="orders-kpi-label">Total Orders</span>
            <div class="orders-kpi-value">{{ number_format($totalOrders) }}</div>
            <div class="orders-kpi-sub">All orders in the store</div>
          </div>
          <div class="orders-kpi-icon">
            <i class="fas fa-boxes-stacked"></i>
          </div>
        </div>
      </div>

      <div class="orders-kpi --amber">
        <div class="orders-kpi-head">
          <div>
            <span class="orders-kpi-label">Revenue</span>
            <div class="orders-kpi-value">${{ number_format($totalRevenue, 2) }}</div>
            <div class="orders-kpi-sub">Lifetime gross sales</div>
          </div>
          <div class="orders-kpi-icon">
            <i class="fas fa-sack-dollar"></i>
          </div>
        </div>
      </div>

      <div class="orders-kpi --emerald">
        <div class="orders-kpi-head">
          <div>
            <span class="orders-kpi-label">Average Order</span>
            <div class="orders-kpi-value">${{ number_format($averageOrderValue, 2) }}</div>
            <div class="orders-kpi-sub">Across all orders</div>
          </div>
          <div class="orders-kpi-icon">
            <i class="fas fa-chart-line"></i>
          </div>
        </div>
      </div>

      <div class="orders-kpi --violet">
        <div class="orders-kpi-head">
          <div>
            <span class="orders-kpi-label">Pending</span>
            <div class="orders-kpi-value">{{ number_format($pendingOrders) }}</div>
            <div class="orders-kpi-sub">Orders needing attention</div>
          </div>
          <div class="orders-kpi-icon">
            <i class="fas fa-hourglass-half"></i>
          </div>
        </div>
      </div>
    </section>

    <div class="orders-toolbar">
      <div class="orders-filters">
        @foreach($statusOptions as $value => $label)
          <a
            href="{{ $value === '' ? route('admin.orders.index') : route('admin.orders.index', ['status' => $value]) }}"
            class="orders-filter {{ ($selectedStatus ?? '') === $value ? 'active' : '' }}"
          >
            {{ $label }}
          </a>
        @endforeach
      </div>

      <div class="orders-meta">
        Showing {{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
        @if($selectedStatus)
          <br>Filtered by {{ $statusLabels[$selectedStatus] ?? ucfirst($selectedStatus) }}
        @endif
      </div>
    </div>

    <div class="orders-layout">
      <section class="orders-card">
        <div class="orders-card-head">
          <div>
            <h3>Order List</h3>
            <p>Latest customer purchases with quick access to the detail view.</p>
          </div>
          <div class="orders-card-badge">
            <i class="fas fa-list"></i>
            {{ $orders->count() }} on this page
          </div>
        </div>

        <div class="orders-table-wrap">
          <table class="orders-table">
            <thead>
              <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Total</th>
                <th>Items</th>
                <th>Date</th>
                <th style="text-align: right;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $order)
                @php
                  $statusKey = strtolower($order->status ?? 'pending');
                  $statusClass = in_array($statusKey, ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'completed'], true)
                    ? 'status-' . $statusKey
                    : 'status-default';
                @endphp
                <tr>
                  <td>
                    <span class="order-id">
                      <i class="fas fa-receipt"></i>
                      #{{ $order->id }}
                    </span>
                  </td>
                  <td>
                    <div class="order-customer">
                      <strong>{{ $order->user->name ?? 'Guest' }}</strong>
                      <span>{{ $order->user->email ?? 'No email available' }}</span>
                    </div>
                  </td>
                  <td>
                    <span class="order-badge {{ $statusClass }}">
                      {{ $statusLabels[$statusKey] ?? ucfirst($statusKey) }}
                    </span>
                  </td>
                  <td class="order-total">${{ number_format((float) ($order->total ?? 0), 2) }}</td>
                  <td>{{ $order->items_count }}</td>
                  <td>
                    <div style="font-weight: 600;">{{ $order->created_at?->format('M d, Y') }}</div>
                    <div style="font-size: 12px; color: #64748b;">{{ $order->created_at?->format('h:i A') }}</div>
                  </td>
                  <td>
                    <div class="order-actions">
                      <a href="{{ route('admin.orders.show', $order) }}" class="order-icon-btn" title="View order">
                        <i class="fas fa-eye"></i>
                      </a>
                      @if(strtolower($order->status) !== 'completed' && strtolower($order->status) !== 'delivered')
                        <form
                          action="{{ route('admin.orders.complete', $order) }}"
                          method="POST"
                          onsubmit="return confirm('Mark this order as successful/completed?');"
                          style="margin: 0;"
                        >
                          @csrf
                          @method('PATCH')
                          <button type="submit" class="order-icon-btn complete" title="Mark as completed">
                            <i class="fas fa-check"></i>
                          </button>
                        </form>
                      @endif
                      <form
                        action="{{ route('admin.orders.destroy', $order) }}"
                        method="POST"
                        onsubmit="return confirm('Delete this order?');"
                        style="margin: 0;"
                      >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="order-icon-btn delete" title="Delete order">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7">
                    <div class="orders-empty">
                      <i class="fas fa-box-open"></i>
                      <h4>No Orders Found</h4>
                      <p>
                        @if($selectedStatus)
                          No orders matched the selected filter.
                        @else
                          New orders will appear here once customers start checking out.
                        @endif
                      </p>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if($orders->hasPages())
          <div class="orders-pager">
            {{ $orders->links() }}
          </div>
        @endif
      </section>

      <aside class="orders-side">
        <section class="orders-card">
          <div class="orders-card-head">
            <div>
              <h3>Status Breakdown</h3>
              <p>Current order mix across the store.</p>
            </div>
          </div>

          <div style="padding: 0 20px 20px;">
            @foreach($statusLabels as $statusKey => $statusLabel)
              <div class="orders-status-row">
                <div class="orders-status-left">
                  <span class="orders-status-dot" style="background: {{ $statusColors[$statusKey] ?? '#94a3b8' }};"></span>
                  <span class="orders-status-name">{{ $statusLabel }}</span>
                </div>
                <span class="orders-status-count">{{ number_format($statusCounts[$statusKey] ?? 0) }}</span>
              </div>
            @endforeach
          </div>
        </section>

        <section class="orders-card">
          <div class="orders-card-head">
            <div>
              <h3>Recent Orders</h3>
              <p>The latest five orders in the system.</p>
            </div>
          </div>

          <div style="padding: 0 20px 20px;">
            @forelse($recentOrders as $recentOrder)
              @php
                $recentStatus = strtolower($recentOrder->status ?? 'pending');
              @endphp
              <div class="orders-mini">
                <div>
                  <div class="orders-mini-id">#{{ $recentOrder->id }}</div>
                  <div class="orders-mini-sub">
                    {{ $recentOrder->user->name ?? 'Guest' }} - {{ $recentOrder->created_at?->format('M d, Y') }}
                  </div>
                  <div style="margin-top: 8px;">
                    <span class="order-badge {{ in_array($recentStatus, ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'completed'], true) ? 'status-' . $recentStatus : 'status-default' }}">
                      {{ $statusLabels[$recentStatus] ?? ucfirst($recentStatus) }}
                    </span>
                  </div>
                </div>
                <div class="orders-mini-total">
                  ${{ number_format((float) ($recentOrder->total ?? 0), 2) }}
                </div>
              </div>
            @empty
              <div class="orders-note">
                No recent orders are available yet.
              </div>
            @endforelse
          </div>
        </section>

        <section class="orders-card">
          <div class="orders-card-head">
            <div>
              <h3>Quick Note</h3>
              <p>Operational hint for the team.</p>
            </div>
          </div>
          <div style="padding: 0 20px 20px;">
            <div class="orders-note">
              Use the status filter chips above to jump straight to pending or in-progress orders. The detail view is a fast place to review customer information and remove test data when needed.
            </div>
          </div>
        </section>
      </aside>
    </div>
  </div>
</div>
@endsection
