@extends('admin.layout')

@section('title', 'Dashboard')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  .dashboard-page {
    position: relative;
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    color: #0f172a;
  }

  .dashboard-page * {
    box-sizing: border-box;
  }

  .dashboard-page::before,
  .dashboard-page::after {
    content: '';
    position: fixed;
    z-index: 0;
    pointer-events: none;
    border-radius: 999px;
    filter: blur(18px);
    opacity: 0.4;
  }

  .dashboard-page::before {
    top: 60px;
    right: -120px;
    width: 260px;
    height: 260px;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.18) 0%, rgba(59, 130, 246, 0) 70%);
  }

  .dashboard-page::after {
    bottom: 20px;
    left: -100px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.16) 0%, rgba(16, 185, 129, 0) 70%);
  }

  .dashboard-shell {
    position: relative;
    z-index: 1;
  }

  .dashboard-hero {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 18px;
    padding: 24px 26px;
    border-radius: 28px;
    color: #fff;
    background:
      radial-gradient(circle at top left, rgba(255, 255, 255, 0.14), transparent 35%),
      linear-gradient(135deg, #0f172a 0%, #111827 54%, #1d4ed8 100%);
    box-shadow: 0 22px 40px rgba(15, 23, 42, 0.18);
    overflow: hidden;
    margin-bottom: 16px;
  }

  .dashboard-hero > * {
    position: relative;
    z-index: 1;
  }

  .dashboard-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.88);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
  }

  .dashboard-title {
    margin: 14px 0 0;
    font-size: clamp(28px, 3vw, 42px);
    line-height: 1.04;
    letter-spacing: -0.05em;
    font-weight: 800;
  }

  .dashboard-subtitle {
    margin: 10px 0 0;
    max-width: 720px;
    font-size: 14px;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.8);
  }

  .dashboard-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 10px;
  }

  .dashboard-action {
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

  .dashboard-action:hover {
    transform: translateY(-1px);
  }

  .dashboard-action.primary {
    background: #ffffff;
    color: #0f172a;
    box-shadow: 0 10px 22px rgba(15, 23, 42, 0.16);
  }

  .dashboard-action.secondary {
    color: #fff;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.16);
  }

  .dashboard-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 16px;
  }

  @media (max-width: 1100px) {
    .dashboard-hero {
      flex-direction: column;
    }

    .dashboard-actions {
      justify-content: flex-start;
    }
  }

  @media (max-width: 980px) {
    .dashboard-kpis {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 560px) {
    .dashboard-kpis {
      grid-template-columns: 1fr;
    }
  }

  .dashboard-kpi {
    position: relative;
    background: #ffffff;
    border: 1px solid #e8edf2;
    border-radius: 20px;
    padding: 18px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .dashboard-kpi:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 30px rgba(15, 23, 42, 0.08);
  }

  .dashboard-kpi::before {
    content: '';
    position: absolute;
    inset: 0 auto 0 0;
    width: 4px;
  }

  .dashboard-kpi.--blue::before { background: linear-gradient(180deg, #3b82f6, #2563eb); }
  .dashboard-kpi.--green::before { background: linear-gradient(180deg, #10b981, #059669); }
  .dashboard-kpi.--amber::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
  .dashboard-kpi.--violet::before { background: linear-gradient(180deg, #8b5cf6, #7c3aed); }

  .dashboard-kpi-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
  }

  .dashboard-kpi-label {
    display: block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #64748b;
  }

  .dashboard-kpi-value {
    margin-top: 8px;
    font-size: 30px;
    line-height: 1;
    font-weight: 800;
    letter-spacing: -0.05em;
    color: #0f172a;
  }

  .dashboard-kpi-sub {
    margin-top: 7px;
    font-size: 12px;
    color: #64748b;
  }

  .dashboard-kpi-icon {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
    font-size: 18px;
    color: #fff;
  }

  .dashboard-kpi.--blue .dashboard-kpi-icon {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    box-shadow: 0 10px 18px rgba(59, 130, 246, 0.25);
  }

  .dashboard-kpi.--green .dashboard-kpi-icon {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 10px 18px rgba(16, 185, 129, 0.25);
  }

  .dashboard-kpi.--amber .dashboard-kpi-icon {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 10px 18px rgba(245, 158, 11, 0.25);
  }

  .dashboard-kpi.--violet .dashboard-kpi-icon {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    box-shadow: 0 10px 18px rgba(139, 92, 246, 0.25);
  }

  .dashboard-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.45fr) minmax(300px, 0.95fr);
    gap: 16px;
  }

  .dashboard-column {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  @media (max-width: 1180px) {
    .dashboard-grid {
      grid-template-columns: 1fr;
    }
  }

  .dashboard-card {
    background: #fff;
    border: 1px solid #e8edf2;
    border-radius: 22px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
    overflow: hidden;
  }

  .dashboard-card-head {
    padding: 18px 20px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(180deg, #fbfdff 0%, #ffffff 100%);
  }

  .dashboard-card-head h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: #0f172a;
  }

  .dashboard-card-head p {
    margin: 4px 0 0;
    font-size: 12px;
    color: #64748b;
  }

  .dashboard-card-tag {
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
    white-space: nowrap;
  }

  .dashboard-card-body {
    padding: 18px 20px 20px;
  }

  .chart-box {
    position: relative;
    width: 100%;
    height: 280px;
  }

  .chart-box canvas {
    width: 100% !important;
    height: 100% !important;
  }

  .mini-chart-box {
    position: relative;
    width: 100%;
    height: 220px;
  }

  .mini-chart-box canvas {
    width: 100% !important;
    height: 100% !important;
  }

  .status-list,
  .timeline-list,
  .review-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .status-row,
  .item-row,
  .review-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 14px;
    border: 1px solid #eef2f7;
    border-radius: 16px;
    background: #fbfdff;
    transition: transform 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
  }

  .status-row:hover,
  .item-row:hover,
  .review-row:hover {
    transform: translateY(-1px);
    border-color: #dbe3ec;
    box-shadow: 0 10px 20px rgba(15, 23, 42, 0.05);
  }

  .status-left,
  .item-left,
  .review-left {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
  }

  .status-dot,
  .item-dot {
    width: 10px;
    height: 10px;
    border-radius: 999px;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
  }

  .status-name,
  .item-name,
  .review-name {
    font-weight: 700;
    font-size: 13px;
    color: #0f172a;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .status-pill,
  .score-pill {
    font-size: 12px;
    font-weight: 800;
    color: #0f172a;
    background: #fff;
    padding: 4px 10px;
    border-radius: 999px;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    white-space: nowrap;
  }

  .muted-line {
    margin-top: 2px;
    font-size: 12px;
    color: #64748b;
  }

  .empty-state {
    text-align: center;
    padding: 38px 18px;
    color: #94a3b8;
    font-size: 13px;
  }

  .table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  .dashboard-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 760px;
  }

  .dashboard-table th {
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

  .dashboard-table td {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    color: #0f172a;
    font-size: 14px;
  }

  .dashboard-table tbody tr:hover td {
    background: #f8fafc;
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

  .status-pending { background: #fffbeb; color: #b45309; }
  .status-processing { background: #eff6ff; color: #1d4ed8; }
  .status-shipped { background: #eef2ff; color: #4f46e5; }
  .status-delivered { background: #ecfdf5; color: #047857; }
  .status-cancelled { background: #fef2f2; color: #b91c1c; }
  .status-default { background: #f1f5f9; color: #475569; }

  .order-title {
    font-weight: 800;
    color: #0f172a;
  }

  .order-sub {
    margin-top: 3px;
    font-size: 12px;
    color: #64748b;
  }

  .pill-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 12px;
    border: 1px solid #dbe3ec;
    background: #fff;
    color: #334155;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
  }

  .pill-link:hover {
    transform: translateY(-1px);
    border-color: #cbd5e1;
    box-shadow: 0 8px 16px rgba(15, 23, 42, 0.05);
  }

  .score-card {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .score-avatar {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    background: #ecfdf5;
    color: #059669;
    flex-shrink: 0;
  }

  .score-meta {
    min-width: 0;
  }

  .score-meta strong {
    display: block;
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
  }

  .score-meta span {
    display: block;
    margin-top: 3px;
    font-size: 12px;
    color: #64748b;
  }

  @media (max-width: 640px) {
    .dashboard-hero {
      padding: 20px;
    }

    .dashboard-card-head {
      flex-direction: column;
    }

    .dashboard-card-body {
      padding: 16px;
    }

    .dashboard-table th,
    .dashboard-table td {
      padding-left: 14px;
      padding-right: 14px;
    }
  }
</style>
@endpush

@section('content')
@php
  $orderStatusColors = [
    'pending' => '#f59e0b',
    'processing' => '#3b82f6',
    'shipped' => '#6366f1',
    'delivered' => '#10b981',
    'cancelled' => '#ef4444',
  ];

  $recentOrderStatuses = [
    'pending' => 'Pending',
    'processing' => 'Processing',
    'shipped' => 'Shipped',
    'delivered' => 'Delivered',
    'cancelled' => 'Cancelled',
  ];

  $monthlyChartLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  $monthlyChartData = array_fill(0, 12, 0);
  foreach (($monthlyRevenue ?? []) as $month => $amount) {
    $index = (int) $month - 1;
    if ($index >= 0 && $index < 12) {
      $monthlyChartData[$index] = (float) $amount;
    }
  }

@endphp

<div class="dashboard-page">
  <div class="dashboard-shell">
    <section class="dashboard-hero">
      <div>
        <div class="dashboard-eyebrow">
          <i class="fas fa-chart-line"></i>
          Store overview
        </div>
        <h1 class="dashboard-title">Dashboard</h1>
        <p class="dashboard-subtitle">
          A fast read on store performance, order flow, and customer activity with a focused admin layout.
        </p>
      </div>

      <div class="dashboard-actions">
        <a href="{{ route('admin.orders.index') }}" class="dashboard-action secondary">
          <i class="fas fa-receipt"></i>
          Orders
        </a>
        <a href="{{ route('admin.products.index') }}" class="dashboard-action secondary">
          <i class="fas fa-boxes-stacked"></i>
          Products
        </a>
        <a href="{{ route('admin.dashboard') }}" class="dashboard-action primary">
          <i class="fas fa-rotate"></i>
          Refresh
        </a>
      </div>
    </section>

    <section class="dashboard-kpis">
      <div class="dashboard-kpi --blue">
        <div class="dashboard-kpi-head">
          <div>
            <span class="dashboard-kpi-label">Total Users</span>
            <div class="dashboard-kpi-value">{{ number_format($totalUsers) }}</div>
            <div class="dashboard-kpi-sub">Registered customer accounts</div>
          </div>
          <div class="dashboard-kpi-icon"><i class="fas fa-user-group"></i></div>
        </div>
      </div>

      <div class="dashboard-kpi --green">
        <div class="dashboard-kpi-head">
          <div>
            <span class="dashboard-kpi-label">Total Orders</span>
            <div class="dashboard-kpi-value">{{ number_format($totalOrders) }}</div>
            <div class="dashboard-kpi-sub">Completed and active orders</div>
          </div>
          <div class="dashboard-kpi-icon"><i class="fas fa-bag-shopping"></i></div>
        </div>
      </div>

      <div class="dashboard-kpi --amber">
        <div class="dashboard-kpi-head">
          <div>
            <span class="dashboard-kpi-label">Total Revenue</span>
            <div class="dashboard-kpi-value">${{ number_format($totalRevenue, 2) }}</div>
            <div class="dashboard-kpi-sub">Gross sales across orders</div>
          </div>
          <div class="dashboard-kpi-icon"><i class="fas fa-sack-dollar"></i></div>
        </div>
      </div>

      <div class="dashboard-kpi --violet">
        <div class="dashboard-kpi-head">
          <div>
            <span class="dashboard-kpi-label">Average Order</span>
            <div class="dashboard-kpi-value">${{ number_format($averageOrderValue, 2) }}</div>
            <div class="dashboard-kpi-sub">Average value per order</div>
          </div>
          <div class="dashboard-kpi-icon"><i class="fas fa-chart-pie"></i></div>
        </div>
      </div>
    </section>

    <div class="dashboard-grid">
      <div class="dashboard-column">
        <section class="dashboard-card">
          <div class="dashboard-card-head">
            <div>
              <h3>Monthly Revenue</h3>
              <p>Revenue trend for the current year.</p>
            </div>
            <span class="dashboard-card-tag">
              <i class="fas fa-signal"></i>
              live
            </span>
          </div>
          <div class="dashboard-card-body">
            <div class="chart-box">
              <canvas id="monthlyRevenueChart"></canvas>
            </div>
          </div>
        </section>

        <section class="dashboard-card">
          <div class="dashboard-card-head">
            <div>
              <h3>Recent Orders</h3>
              <p>Latest customer purchases.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="pill-link">
              View all
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
          <div class="dashboard-card-body table-wrap">
            <table class="dashboard-table">
              <thead>
                <tr>
                  <th>Order</th>
                  <th>Customer</th>
                  <th>Status</th>
                  <th>Total</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentOrders as $order)
                  @php
                    $statusKey = strtolower($order->status ?? 'pending');
                    $statusClass = in_array($statusKey, ['pending', 'processing', 'shipped', 'delivered', 'cancelled'], true)
                      ? 'status-' . $statusKey
                      : 'status-default';
                  @endphp
                  <tr>
                    <td>
                      <div class="order-title">#{{ $order->id }}</div>
                      <div class="order-sub">{{ $order->items_count }} items</div>
                    </td>
                    <td>
                      <div class="order-title">{{ $order->user->name ?? 'Guest' }}</div>
                      <div class="order-sub">{{ $order->user->email ?? 'No email available' }}</div>
                    </td>
                    <td>
                      <span class="order-badge {{ $statusClass }}">
                        {{ $recentOrderStatuses[$statusKey] ?? ucfirst($statusKey) }}
                      </span>
                    </td>
                    <td class="order-title">${{ number_format((float) ($order->total ?? 0), 2) }}</td>
                    <td>
                      <div class="order-title">{{ $order->created_at?->format('M d, Y') }}</div>
                      <div class="order-sub">{{ $order->created_at?->format('h:i A') }}</div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5">
                      <div class="empty-state">No orders available yet.</div>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </section>
      </div>

      <div class="dashboard-column">
        <section class="dashboard-card">
          <div class="dashboard-card-head">
            <div>
              <h3>Order Status</h3>
              <p>Breakdown of current order states.</p>
            </div>
          </div>
          <div class="dashboard-card-body">
            <div class="status-list">
              @forelse(($orderStatus ?? []) as $status => $count)
                <div class="status-row">
                  <div class="status-left">
                    <span class="status-dot" style="background: {{ $orderStatusColors[$status] ?? '#94a3b8' }};"></span>
                    <span class="status-name">{{ ucfirst($status) }}</span>
                  </div>
                  <span class="status-pill">{{ $count }}</span>
                </div>
              @empty
                <div class="empty-state">No orders yet.</div>
              @endforelse
            </div>
          </div>
        </section>

        <section class="dashboard-card">
          <div class="dashboard-card-head">
            <div>
              <h3>Top Products</h3>
              <p>Best-performing products by order volume.</p>
            </div>
          </div>
          <div class="dashboard-card-body">
            <div class="timeline-list">
              @forelse($topProducts as $product)
                <div class="item-row">
                  <div class="item-left">
                    <span class="item-dot" style="background: #10b981;"></span>
                    <div>
                      <div class="item-name">{{ $product->name }}</div>
                      <div class="muted-line">{{ $product->order_items_count }} order items</div>
                    </div>
                  </div>
                  <span class="score-pill">${{ number_format((float) ($product->price ?? 0), 2) }}</span>
                </div>
              @empty
                <div class="empty-state">No product sales data yet.</div>
              @endforelse
            </div>
          </div>
        </section>

      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  (function () {
    var monthlyLabels = @json($monthlyChartLabels);
    var monthlyData = @json($monthlyChartData);

    var revenueCanvas = document.getElementById('monthlyRevenueChart');
    if (revenueCanvas) {
      new Chart(revenueCanvas, {
        type: 'line',
        data: {
          labels: monthlyLabels,
          datasets: [{
            label: 'Revenue',
            data: monthlyData,
            borderColor: '#7c3aed',
            backgroundColor: function (context) {
              var chart = context.chart;
              var chartCtx = chart.ctx;
              var gradient = chartCtx.createLinearGradient(0, 0, 0, 280);
              gradient.addColorStop(0, 'rgba(124, 58, 237, 0.24)');
              gradient.addColorStop(1, 'rgba(124, 58, 237, 0.02)');
              return gradient;
            },
            borderWidth: 2,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#7c3aed',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true,
            tension: 0.42
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#111827',
              titleColor: '#ffffff',
              bodyColor: '#ffffff',
              borderColor: 'transparent',
              borderWidth: 0,
              cornerRadius: 10,
              padding: 10,
              displayColors: false,
              callbacks: {
                label: function (context) {
                  return '$' + Number(context.parsed.y).toLocaleString();
                }
              }
            }
          },
          scales: {
            x: {
              grid: { display: false },
              ticks: {
                color: '#94a3b8',
                font: { family: 'Inter', size: 11 },
                maxRotation: 0
              },
              border: { display: false }
            },
            y: {
              beginAtZero: true,
              ticks: {
                color: '#94a3b8',
                font: { family: 'Inter', size: 11 },
                callback: function (value) {
                  return '$' + value;
                },
                maxTicksLimit: 5
              },
              grid: {
                color: '#f1f5f9',
                drawBorder: false
              },
              border: { display: false }
            }
          }
        }
      });
    }

  })();
</script>
@endpush
