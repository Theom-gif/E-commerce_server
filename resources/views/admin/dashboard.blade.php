@extends('admin.layout')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --card: #ffffff;
    --shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.05);
    --shadow-lg: 0 8px 24px rgba(0,0,0,0.09);
    --radius-sm: 10px;
  }

  .page-header {
    margin-bottom: 18px;
  }

  .page-header h1 {
    font-size: 26px;
    font-weight: 800;
    color: var(--text);
    letter-spacing: -0.5px;
  }

  .page-header p {
    margin-top: 4px;
    color: var(--muted);
    font-size: 14px;
  }

  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 18px;
  }

  .kpi {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 120px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .kpi:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
  }

  .kpi::before {
    content: '';
    position: absolute;
    left: 0;
    top: 14px;
    bottom: 14px;
    width: 4px;
    border-radius: 0 4px 4px 0;
  }

  .kpi.blue   { background: linear-gradient(135deg,#eff6ff,#ffffff); border-color: #bfdbfe; }
  .kpi.blue::before { background: linear-gradient(180deg,#3b82f6,#2563eb); }

  .kpi.green  { background: linear-gradient(135deg,#ecfdf5,#ffffff); border-color: #a7f3d0; }
  .kpi.green::before { background: linear-gradient(180deg,#10b981,#059669); }

  .kpi.purple { background: linear-gradient(135deg,#f5f3ff,#ffffff); border-color: #ddd6fe; }
  .kpi.purple::before { background: linear-gradient(180deg,#8b5cf6,#7c3aed); }

  .kpi.orange { background: linear-gradient(135deg,#fff7ed,#ffffff); border-color: #fed7aa; }
  .kpi.orange::before { background: linear-gradient(180deg,#f97316,#ea580c); }

  .kpi-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--muted);
  }

  .kpi-value {
    margin-top: 8px;
    font-size: 26px;
    font-weight: 800;
    letter-spacing: -0.6px;
    color: var(--text);
  }

  .kpi-sub {
    margin-top: 6px;
    font-size: 11px;
    font-weight: 700;
    color: #10b981;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .kpi-sub::before {
    content: '↗';
    font-size: 12px;
  }

  .kpi-icon {
    position: absolute;
    right: 14px;
    top: 16px;
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    font-size: 17px;
    color: #ffffff;
  }

  .kpi.blue .kpi-icon   { background: linear-gradient(135deg,#3b82f6,#2563eb); box-shadow: 0 4px 12px rgba(59,130,246,.3); }
  .kpi.green .kpi-icon  { background: linear-gradient(135deg,#10b981,#059669); box-shadow: 0 4px 12px rgba(16,185,129,.3); }
  .kpi.purple .kpi-icon { background: linear-gradient(135deg,#8b5cf6,#7c3aed); box-shadow: 0 4px 12px rgba(139,92,246,.3); }
  .kpi.orange .kpi-icon { background: linear-gradient(135deg,#f97316,#ea580c); box-shadow: 0 4px 12px rgba(249,115,22,.3); }

  .panels {
    display: grid;
    grid-template-columns: 3fr 2fr;
    gap: 14px;
  }

  .panel {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    min-height: 320px;
  }

  .panel-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    background: #fafafa;
    border-bottom: 1px solid #f3f4f6;
    font-weight: 700;
    font-size: 14px;
    color: var(--text);
  }

  .panel-legend {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    padding: 5px 12px;
    border-radius: 999px;
    background: #f3f4f6;
  }

  .panel-legend::before {
    content: '';
    width: 10px;
    height: 10px;
    border-radius: 3px;
    background: linear-gradient(135deg,#8b5cf6,#7c3aed);
    box-shadow: 0 1px 3px rgba(139,92,246,.35);
  }

  .panel-body {
    flex: 1;
    padding: 16px 18px;
    overflow-y: auto;
  }

  .chart-box {
    position: relative;
    width: 100%;
    height: 260px;
  }

  .chart-box canvas {
    width: 100% !important;
    height: 100% !important;
  }

  .status-wrap {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .status-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 9px 12px;
    background: #f9fafb;
    border-radius: var(--radius-sm);
    border: 1px solid transparent;
    transition: 0.12s ease;
  }

  .status-row:hover {
    background: #fff;
    border-color: var(--border);
    box-shadow: var(--shadow);
  }

  .status-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .status-dot {
    width: 9px;
    height: 9px;
    border-radius: 999px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.15);
  }

  .status-name {
    font-weight: 700;
    font-size: 13px;
    color: #111827;
    text-transform: capitalize;
  }

  .status-pill {
    font-size: 12px;
    font-weight: 700;
    color: #111827;
    background: #fff;
    padding: 3px 10px;
    border-radius: 999px;
    box-shadow: var(--shadow);
    min-width: 44px;
    text-align: center;
  }

  .empty {
    color: #94a3b8;
    text-align: center;
    padding: 40px 0;
    font-size: 14px;
  }

  @media (max-width: 1366px) {
    .kpi-grid { grid-template-columns: repeat(4, 1fr); gap: 10px; }
    .panels { gap: 10px; }
  }

  @media (max-width: 1200px) {
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .panels { grid-template-columns: 1fr; }
  }

  @media (max-width: 768px) {
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .panel { min-height: 260px; }
    .chart-box { height: 220px; }
  }

  @media (max-width: 480px) {
    .kpi-grid { grid-template-columns: 1fr; }
    .panel { min-height: auto; }
    .chart-box { height: 210px; }
  }
</style>
@endpush

@section('content')
<div class="main-inner">
    <div class="page-header">
      <h1>Dashboard</h1>
      <p>Welcome back! Here's your business overview.</p>
    </div>

    <div class="kpi-grid">
      <div class="kpi blue">
        <div>
          <div class="kpi-label">Total Users</div>
          <div class="kpi-value">{{ number_format($totalUsers) }}</div>
          <div class="kpi-sub">12% from last month</div>
        </div>
        <div class="kpi-icon"><i class="fas fa-user-group"></i></div>
      </div>

      <div class="kpi green">
        <div>
          <div class="kpi-label">Total Orders</div>
          <div class="kpi-value">{{ number_format($totalOrders) }}</div>
          <div class="kpi-sub">8% from last month</div>
        </div>
        <div class="kpi-icon"><i class="fas fa-shopping-bag"></i></div>
      </div>

      <div class="kpi purple">
        <div>
          <div class="kpi-label">Total Revenue</div>
          <div class="kpi-value">${{ number_format($totalRevenue, 2) }}</div>
          <div class="kpi-sub">23% from last month</div>
        </div>
        <div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div>
      </div>

      <div class="kpi orange">
        <div>
          <div class="kpi-label">Total Products</div>
          <div class="kpi-value">{{ number_format($totalProducts) }}</div>
          <div class="kpi-sub">5% from last month</div>
        </div>
        <div class="kpi-icon"><i class="fas fa-box"></i></div>
      </div>
    </div>

    <div class="panels">
      <div class="panel">
        <div class="panel-head">
          <span>Monthly Revenue</span>
          <span class="panel-legend">Revenue</span>
        </div>
        <div class="panel-body">
          <div class="chart-box">
            <canvas id="monthlyRevenueChart"></canvas>
          </div>
        </div>
      </div>

      <div class="panel">
        <div class="panel-head">Order Status</div>
        <div class="panel-body">
          @php
            $statuses = $orderStatus ?? [];
            $colors = [
              'pending' => '#f59e0b',
              'processing' => '#3b82f6',
              'shipped' => '#6366f1',
              'delivered' => '#10b981',
              'cancelled' => '#ef4444',
            ];
          @endphp

          @forelse($statuses as $status => $count)
            <div class="status-row">
              <div class="status-left">
                <span class="status-dot" style="background: {{ $colors[$status] ?? '#6b7280' }}; "></span>
                <span class="status-name">{{ ucfirst($status) }}</span>
              </div>
              <span class="status-pill">{{ $count }}</span>
            </div>
          @empty
            <div class="empty">No orders yet</div>
          @endforelse
        </div>
      </div>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  (function () {
    var labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    var data = labels.map(function () { return 0; });
    var monthlyRevenue = @json($monthlyRevenue ?? []);
    var keys = Object.keys(monthlyRevenue || {});
    if (keys.length) {
      labels.forEach(function (m) {
        var idx = (parseInt(m, 10) - 1);
        if (monthlyRevenue[idx + 1] != null) {
          data[idx] = monthlyRevenue[idx + 1];
        }
      });
    }

    var ctx = document.getElementById('monthlyRevenueChart');
    if (!ctx) return;

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Revenue',
          data: data,
          borderColor: '#7c3aed',
          backgroundColor: function (context) {
            var chart = context.chart;
            var chartCtx = chart.ctx;
            var gradient = chartCtx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, 'rgba(124, 58, 237, 0.25)');
            gradient.addColorStop(1, 'rgba(124, 58, 237, 0.02)');
            return gradient;
          },
          borderWidth: 2,
          pointBackgroundColor: '#ffffff',
          pointBorderColor: '#7c3aed',
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6,
          pointHoverBackgroundColor: '#7c3aed',
          pointHoverBorderColor: '#ffffff',
          pointHoverBorderWidth: 2,
          fill: true,
          tension: 0.4
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
            cornerRadius: 6,
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
              color: '#9ca3af',
              font: { family: 'Inter', size: 11 },
              maxRotation: 0
            },
            border: { display: false }
          },
          y: {
            beginAtZero: true,
            ticks: {
              color: '#9ca3af',
              font: { family: 'Inter', size: 11 },
              callback: function (v) { return '$' + v; },
              maxTicksLimit: 4
            },
            grid: {
              color: '#f3f4f6',
              drawBorder: false
            },
            border: { display: false }
          }
        }
      }
    });
  })();
</script>
@endpush
