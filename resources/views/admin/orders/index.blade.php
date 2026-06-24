@extends('admin.layout')
@section('title', 'Orders')

@push('styles')
<style>
  .db * { box-sizing: border-box; }
  .db-kpis { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-top: 20px; margin-bottom: 20px; }
  @media(max-width: 960px) { .db-kpis { grid-template-columns: repeat(2, 1fr); } }
  @media(max-width: 500px) { .db-kpis { grid-template-columns: 1fr; } }
  .db-kpi { border-radius: 16px; padding: 22px 20px; color: #0F172A; }
  .db-kpi.--indigo { background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); color: #1E1B4B; }
  .db-kpi.--green { background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); color: #064E3B; }
  .db-kpi.--amber { background: linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 100%); color: #7C2D12; }
  .db-kpi.--purple { background: linear-gradient(135deg, #F5F3FF 0%, #EDE9FE 100%); color: #2E1065; }
  .db-kpi-label { font-size: 10.5px; font-weight: 700; letter-spacing: .10em; text-transform: uppercase; opacity: .75; margin: 0 0 8px; }
  .db-kpi-value { font-size: 32px; font-weight: 800; letter-spacing: -1.5px; margin: 0 0 8px; }
  .db-kpi-sub { font-size: 11.5px; opacity: .7; font-weight: 500; margin: 0; }
  .db-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; }
  .db-panel { background: #fff; border: 1px solid #E8EDF2; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 4px rgba(15,23,42,.04); }
  .db-panel-head { padding: 16px 20px; border-bottom: 1px solid #F1F5F9; background: #FAFBFD; display: flex; align-items: center; justify-content: space-between; }
  .db-panel-head h3 { font-size: 13px; font-weight: 700; color: #0F172A; margin: 0; letter-spacing: .01em; }
  .db-panel-body { padding: 16px 20px; }
  .chart-wrap { position: relative; height: 240px; }
  .chart-wrap.tall { height: 300px; }
  .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
  .page-header h1 { font-size: 21px; font-weight: 800; color: #0F172A; margin: 0; letter-spacing: -.4px; }
  .page-header p { margin: 0; color: #64748B; font-size: 13px; }
  .db-live { display: inline-flex; align-items: center; gap: 6px; background: #ECFDF5; color: #059669; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 999px; letter-spacing: .04em; text-transform: uppercase; border: 1px solid rgba(16,185,129,.2); }
  .db-live-dot { width: 6px; height: 6px; border-radius: 50%; background: #10B981; animation: pulse 1.8s ease infinite; }
  @keyframes pulse { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: .35; transform: scale(.8); } }
</style>
@endsection

@section('content')
<div class="db">
  <div class="page-header">
    <div>
      <h1>Orders</h1>
      <p>Orders overview with live business metrics.</p>
    </div>
    <span class="db-live"><span class="db-live-dot"></span> Live overview</span>
  </div>

  @include('admin.partials.dashboard-summary')

  <div class="db-grid">
    <div class="db-panel">
      <div class="db-panel-head"><h3>Recent Customer Orders</h3></div>
      <div class="db-panel-body">
        <table style="width:100%;border-collapse:collapse;font-size:13px;">
          <thead>
            <tr>
              <th style="text-align:left;font-size:10.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94A3B8;padding:14px 0 10px;border-bottom:1px solid #F1F5F9;">Order</th>
              <th style="text-align:left;font-size:10.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94A3B8;padding:14px 0 10px;border-bottom:1px solid #F1F5F9;">Customer</th>
              <th style="text-align:left;font-size:10.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94A3B8;padding:14px 0 10px;border-bottom:1px solid #F1F5F9;">Status</th>
              <th style="text-align:right;font-size:10.5px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94A3B8;padding:14px 0 10px;border-bottom:1px solid #F1F5F9;">Total</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentOrders as $order)
              <tr>
                <td style="padding:13px 0;color:#1E293B;vertical-align:middle;">#{{ $order->id }}</td>
                <td style="padding:13px 0;color:#1E293B;vertical-align:middle;">{{ $order->user->name ?? '—' }}</td>
                <td style="padding:13px 0;color:#64748B;vertical-align:middle;">{{ $order->status ?: 'pending' }}</td>
                <td style="text-align:right;padding:13px 0;color:#64748B;font-weight:700;">${{ number_format((float) ($order->total ?? 0), 2) }}</td>
              </tr>
            @empty
              <tr><td colspan="4" style="text-align:center;color:#94A3B8;padding:40px 0;">No orders found.</td></tr>
            @endforelse
          </tbody>
        </table>
        <div style="padding:14px 16px;">
          <a href="{{ route('admin.orders.index') }}" style="color:#4F46E5;font-weight:700;font-size:13px;">View all orders</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
