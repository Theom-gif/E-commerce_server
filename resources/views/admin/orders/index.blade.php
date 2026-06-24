@extends('admin.layout')

@section('title', 'Orders')

@section('header')
  <h1>Orders</h1>
  <p>Track and manage customer orders.</p>
@endsection

@section('toolbar')
  <h2 class="toolbar-title">Orders</h2>
  <form method="GET" action="{{ route('admin.orders.index') }}" class="search">
    <select name="status">
      <option value="">All statuses</option>
      @foreach(['pending','processing','shipped','completed','cancelled','canceled'] as $s)
        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    <button type="submit">Filter</button>
  </form>
@endsection

@section('content')
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer</th>
          <th>Total</th>
          <th>Status</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
          <tr>
            <td>#{{ $order->id }}</td>
            <td>{{ $order->user->name ?? 'Guest' }}</td>
            <td>${{ number_format($order->total, 2) }}</td>
            <td>
              <span class="status-pill
                @if($order->status === 'completed') completed @endif
                @if($order->status === 'cancelled' || $order->status === 'canceled') cancelled @endif
              ">{{ ucfirst($order->status) }}</span>
            </td>
            <td style="text-align:center;">
              <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm">View</a>
              <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this order?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-primary btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr class="empty-state"><td colspan="5">No orders found.</td></tr>
        @endforelse
      </tbody>
    </table>

    <div style="padding:14px 16px;">
      {{ $orders->links() }}
    </div>
  </div>
@endsection
