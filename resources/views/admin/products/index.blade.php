@extends('admin.layout')

@section('title', 'Products')

@section('header')
  <h1>Products</h1>
  <p>Manage your product catalog.</p>
@endsection

@section('toolbar')
  <h2 class="toolbar-title">Products</h2>
  <form method="GET" action="{{ route('admin.products.index') }}" class="search">
    <input type="text" name="search" placeholder="Search products…" value="{{ request('search') }}">
    <button type="submit">Search</button>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">Create Product</a>
  </form>
@endsection

@section('content')
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Category</th>
          <th>Price</th>
          <th>Stock</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $product)
          <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category->name ?? '-' }}</td>
            <td>${{ number_format($product->price, 2) }}</td>
            <td>{{ $product->stock }}</td>
            <td style="text-align:center;">
              <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary btn-sm">Edit</a>
              <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-primary btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr class="empty-state"><td colspan="6">No products found.</td></tr>
        @endforelse
      </tbody>
    </table>

    <div style="padding:14px 16px;">
      {{ $products->links() }}
    </div>
  </div>
@endsection
