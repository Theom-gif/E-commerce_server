@extends('admin.layout')
@section('title', 'Order #' . $order->id)

@push('styles')
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

  .details-card {
    background: #fff;
    border: 1px solid #e8edf2;
    border-radius: 20px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
    overflow: hidden;
    margin-bottom: 24px;
  }

  .details-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 32px;
    border-bottom: 1px solid #e2e8f0;
    background: linear-gradient(180deg, #fbfdff 0%, #ffffff 100%);
  }

  .details-header-title h2 {
    margin: 0;
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .details-header-title p {
    margin: 6px 0 0;
    color: #64748b;
    font-size: 14px;
  }

  .details-actions {
    display: flex;
    gap: 12px;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn-outline {
    background: #fff;
    border: 1px solid #cbd5e1;
    color: #334155;
    box-shadow: 0 2px 4px rgba(15, 23, 42, 0.02);
  }

  .btn-outline:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(15, 23, 42, 0.04);
  }

  .btn-danger {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
  }

  .btn-danger:hover {
    background: #dc2626;
    color: #fff;
    border-color: #dc2626;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(220, 38, 38, 0.15);
  }

  .info-table {
    width: 100%;
    border-collapse: collapse;
  }

  .info-table th {
    text-align: left;
    padding: 18px 32px;
    background: #f8fafc;
    color: #64748b;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    border-bottom: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    width: 25%;
  }

  .info-table td {
    padding: 18px 32px;
    background: #fff;
    color: #0f172a;
    font-size: 15px;
    font-weight: 500;
    border-bottom: 1px solid #e2e8f0;
  }

  .info-table tr:last-child th,
  .info-table tr:last-child td {
    border-bottom: none;
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 800;
    text-transform: capitalize;
  }

  .status-completed, .status-delivered { background: #ecfdf5; color: #047857; }
  .status-pending { background: #fffbeb; color: #b45309; }
  .status-processing { background: #eff6ff; color: #1d4ed8; }
  .status-shipped { background: #eef2ff; color: #4f46e5; }
  .status-cancelled { background: #fef2f2; color: #b91c1c; }
  .status-default { background: #f1f5f9; color: #475569; }

  .section-title {
    padding: 24px 32px 18px;
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
    background: #fcfdff;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .items-table {
    width: 100%;
    border-collapse: collapse;
  }

  .items-table th {
    padding: 16px 32px;
    text-align: left;
    background: #f8fafc;
    color: #64748b;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e2e8f0;
  }

  .items-table td {
    padding: 20px 32px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    font-size: 15px;
    vertical-align: middle;
  }

  .items-table tbody tr:hover td {
    background: #fcfdff;
  }

  .items-table .product-name {
    font-weight: 700;
    color: #0f172a;
  }

  .items-table .subtotal {
    font-weight: 800;
    color: #0f172a;
  }
</style>
@endpush

@section('content')
<div class="orders-page">
  <div class="orders-shell">

    <div class="details-card">
      <div class="details-header">
        <div class="details-header-title">
          <h2><i class="fas fa-file-invoice" style="color: #3b82f6;"></i> Order #{{ $order->id }}</h2>
          <p>Review the complete details of this order below.</p>
        </div>
        <div class="details-actions">
          <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Orders
          </a>
          <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Delete this order?');" style="margin: 0;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
              <i class="fas fa-trash"></i> Delete
            </button>
          </form>
        </div>
      </div>

      <table class="info-table">
        <tbody>
          <tr>
            <th><i class="fas fa-user" style="margin-right: 8px;"></i> Customer Name</th>
            <td>{{ $order->user->name ?? 'Guest' }}</td>
          </tr>
          <tr>
            <th><i class="fas fa-envelope" style="margin-right: 8px;"></i> Email Address</th>
            <td>{{ $order->user->email ?? 'No email available' }}</td>
          </tr>
          <tr>
            <th><i class="fas fa-calendar-alt" style="margin-right: 8px;"></i> Order Date</th>
            <td>{{ $order->created_at?->format('F d, Y \a\t h:i A') }}</td>
          </tr>
          <tr>
            <th><i class="fas fa-info-circle" style="margin-right: 8px;"></i> Status</th>
            <td>
              @php
                $statusKey = strtolower($order->status ?? 'pending');
                $statusClass = in_array($statusKey, ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'completed'], true)
                  ? 'status-' . $statusKey
                  : 'status-default';
              @endphp
              <span class="status-badge {{ $statusClass }}">
                {{ $order->status }}
              </span>
            </td>
          </tr>
          <tr>
            <th><i class="fas fa-dollar-sign" style="margin-right: 8px;"></i> Total Amount</th>
            <td style="font-weight: 800; font-size: 18px; color: #10b981;">
              ${{ number_format($order->total, 2) }}
            </td>
          </tr>
        </tbody>
      </table>

      <h3 class="section-title">
        <i class="fas fa-box" style="color: #6366f1;"></i> Order Items ({{ $order->items->count() }})
      </h3>

      <div style="overflow-x: auto;">
        <table class="items-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @forelse($order->items as $item)
              <tr>
                <td class="product-name">{{ $item->product->name ?? 'Unknown product' }}</td>
                <td>${{ number_format($item->price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td class="subtotal">${{ number_format($item->price * $item->quantity, 2) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" style="text-align: center; padding: 40px; color: #64748b;">
                  <i class="fas fa-box-open" style="font-size: 32px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                  No products in this order.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
@endsection
