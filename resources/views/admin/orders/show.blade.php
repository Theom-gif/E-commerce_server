<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order #{{ $order->id }} - Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body>
  <div class="main">
    <div class="page-header-bar">
      <div>
        <div class="label">Order</div>
        <div class="value">#{{ $order->id }}</div>
      </div>
      <div style="display:flex; gap:10px;">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back</a>
        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Delete this order?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-primary">Delete Order</button>
        </form>
      </div>
    </div>

    <div class="order-meta">
      <div class="order-meta-grid">
        <div class="order-meta-item">
          <div class="label">Customer</div>
          <div class="value">{{ $order->user->name ?? 'Guest' }}</div>
        </div>
        <div class="order-meta-item">
          <div class="label">Total</div>
          <div class="value">${{ number_format($order->total, 2) }}</div>
        </div>
        <div class="order-meta-item">
          <div class="label">Status</div>
          <div class="value" style="text-transform: capitalize;">{{ $order->status }}</div>
        </div>
        <div class="order-meta-item">
          <div class="label">Created</div>
          <div class="value">{{ $order->created_at?->format('Y-m-d H:i') }}</div>
        </div>
      </div>
    </div>

    <br>
    <div class="items-panel">
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @forelse($order->items as $item)
            <tr>
              <td>{{ $item->product->name ?? 'Unknown product' }}</td>
              <td>${{ number_format($item->price, 2) }}</td>
              <td>{{ $item->quantity }}</td>
              <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
          @empty
            <tr class="empty-state"><td colspan="4">No items.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
