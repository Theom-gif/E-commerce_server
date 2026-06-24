@extends('admin.layout')
@section('title', 'Dashboard')

@push('styles')
<style>
  .db * { box-sizing: border-box; }

  /* ── KPI grid ─────────────────────────────────── */
  .db-kpis {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 20px;
  }
  @media(max-width:960px) { .db-kpis { grid-template-columns: repeat(2,1fr); } }
  @media(max-width:500px) { .db-kpis { grid-template-columns: 1fr; } }

  .db-kpi {
    border-radius: 14px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    transition: transform .18s ease, box-shadow .18s ease;
  }
  .db-kpi:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.10); }

  .db-kpi.--indigo { background: #6366F1; }
  .db-kpi.--green  { background: #10B981; }
  .db-kpi.--amber  { background: #F59E0B; }
  .db-kpi.--purple { background: #8B5CF6; }

  /* Subtle inner glow circle */
  .db-kpi::after {
    content: '';
    position: absolute;
    top: -20px; right: -20px;
    width: 100px; height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
    pointer-events: none;
  }

  .db-kpi-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: rgba(255,255,255,.2);
    display: grid; place-items: center;
    color: #fff; font-size: 15px;
    margin-bottom: 14px;
  }
  .db-kpi-label {
    font-size: 11px; font-weight: 600;
    letter-spacing: .08em; text-transform: uppercase;
    color: rgba(255,255,255,.75);
    margin: 0 0 6px;
  }
  .db-kpi-value {
    font-size: 30px; font-weight: 800;
    color: #fff; line-height: 1;
    letter-spacing: -1px; margin: 0 0 6px;
  }
  .db-kpi-sub {
    font-size: 12px;
    color: rgba(255,255,255,.65);
  }

  /* ── Bottom grid ──────────────────────────────── */
  .db-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 16px;
  }

  .db-panel {
    border: 1px solid #E2E8F0;
    border-radius: 14px;
    overflow: hidden;
  }
  .db-panel-head {
    padding: 14px 18px;
    border-bottom: 1px solid #E2E8F0;
    background: #F8FAFC;
    display: flex; align-items: center;
    justify-content: space-between;
  }
  .db-panel-head h3 {
    font-size: 13px; font-weight: 700;
    color: #0F172A; margin: 0;
  }
  .db-panel-body { padding: 0 18px; }

  /* Table inside panel */
  .db-table { width: 100%; border-collapse: collapse; font-size: 13px; }
  .db-table thead th {
    font-size: 10.5px; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    color: #94A3B8;
    padding: 12px 0 10px;
    border-bottom: 1px solid #F1F5F9;
    text-align: left;
  }
  .db-table thead th:last-child { text-align: right; }
  .db-table tbody tr { border-bottom: 1px solid #F1F5F9; }
  .db-table tbody tr:last-child { border-bottom: none; }
  .db-table tbody td { padding: 11px 0; color: #1E293B; vertical-align: middle; }
  .db-table tbody td:last-child { text-align: right; color: #64748B; font-weight: 600; }

  .db-cat-dot {
    display: inline-block;
    width: 7px; height: 7px;
    border-radius: 50%;
    margin-right: 8px;
    vertical-align: middle;
  }
  .db-empty { text-align: center; color: #94A3B8; padding: 32px 0; font-size: 13px; }

  /* Live badge */
  .db-live {
    display: inline-flex; align-items: center; gap: 5px;
    background: #ECFDF5; color: #059669;
    font-size: 11px; font-weight: 700;
    padding: 3px 10px; border-radius: 999px;
  }
  .db-live-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: #10B981;
    animation: pulse 1.8s ease infinite;
  }
  @keyframes pulse {
    0%,100% { opacity: 1; }
    50%      { opacity: .3; }
  }
</style>
@endpush

@section('content')
<div class="db">

  {{-- Page title row --}}
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
      <h1 style="font-size:20px;font-weight:700;color:#0F172A;margin:0 0 2px;">Dashboard</h1>
      <p style="font-size:13px;color:#64748B;margin:0;">Welcome back. Here is your business overview.</p>
    </div>
    <span class="db-live"><span class="db-live-dot"></span> Live</span>
  </div>

  {{-- KPI Cards --}}
  <div class="db-kpis">
    <div class="db-kpi --indigo">
      <div class="db-kpi-icon"><i class="fas fa-dollar-sign"></i></div>
      <p class="db-kpi-label">Total Revenue</p>
      <p class="db-kpi-value">${{ number_format($totalRevenue, 2) }}</p>
      <p class="db-kpi-sub">Avg order: ${{ number_format($averageOrderValue, 2) }}</p>
    </div>

    <div class="db-kpi --green">
      <div class="db-kpi-icon"><i class="fas fa-shopping-bag"></i></div>
      <p class="db-kpi-label">Total Orders</p>
      <p class="db-kpi-value">{{ number_format($totalOrders) }}</p>
      <p class="db-kpi-sub">Lifetime orders placed</p>
    </div>

    <div class="db-kpi --amber">
      <div class="db-kpi-icon"><i class="fas fa-box"></i></div>
      <p class="db-kpi-label">Total Products</p>
      <p class="db-kpi-value">{{ number_format($totalProducts) }}</p>
      <p class="db-kpi-sub">Active listings</p>
    </div>

    <div class="db-kpi --purple">
      <div class="db-kpi-icon"><i class="fas fa-folder"></i></div>
      <p class="db-kpi-label">Total Categories</p>
      <p class="db-kpi-value">{{ number_format($totalCategories) }}</p>
      <p class="db-kpi-sub">Product categories</p>
    </div>
  </div>

  {{-- Bottom panels --}}
  <div class="db-grid">
    <div class="db-panel">
      <div class="db-panel-head">
        <h3>Top Categories</h3>
      </div>
      <div class="db-panel-body">
        @php
          $dotColors = ['#6366F1','#10B981','#F59E0B','#8B5CF6','#EF4444','#06B6D4'];
        @endphp
        <table class="db-table">
          <thead>
            <tr>
              <th>Category</th>
              <th style="text-align:right;">Products</th>
            </tr>
          </thead>
          <tbody>
            @forelse($topCategories as $i => $topCategory)
              <tr>
                <td>
                  <span class="db-cat-dot" style="background:{{ $dotColors[$i % count($dotColors)] }};"></span>
                  {{ $topCategory->name }}
                </td>
                <td>{{ number_format($topCategory->products_count) }}</td>
              </tr>
            @empty
              <tr><td colspan="2" class="db-empty">No categories yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection