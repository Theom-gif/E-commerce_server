@extends('admin.layout')

@section('title', 'Categories')

@section('header')
  <h1>Categories</h1>
  <p>Manage your product categories.</p>
@endsection

@section('content')
  <div class="card">
    <form method="GET" action="{{ route('admin.categories.index') }}" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
      <input type="text" name="search" placeholder="Search categories…" value="{{ request('search') }}" style="width:260px;">
      <button type="submit" class="btn btn-primary btn-sm">Search</button>
      <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm" style="margin-left:auto;">
        <i class="fas fa-plus"></i> Create Category
      </a>
    </form>
  </div>

  <div class="card" style="padding:0; overflow:hidden;">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Slug</th>
          <th>Products</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $category)
          <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->slug }}</td>
            <td>{{ $category->products_count }}</td>
            <td style="text-align:center;">
              <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-secondary btn-sm">Edit</a>
              <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this category?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-primary btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="empty">No categories found.</td></tr>
        @endforelse
      </tbody>
    </table>

    <div style="padding:12px 16px;">
      {{ $categories->links() }}
    </div>
  </div>
@endsection
