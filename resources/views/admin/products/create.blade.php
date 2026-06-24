@extends('admin.layout')

@section('title', 'New Product')

@section('header')
  <h1>New Product</h1>
  <p>Add product to catalog.</p>
@endsection

@section('content')
  <div class="card form-panel">
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="field">
        <label class="label" for="category_id">Category</label>
        <select id="category_id" name="category_id" required>
          <option value="">Select category</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="field">
        <label class="label" for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required>
      </div>

      <div class="field">
        <label class="label" for="description">Description</label>
        <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
      </div>

      <div class="field">
        <label class="label" for="price">Price</label>
        <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" required>
      </div>

      <div class="field">
        <label class="label" for="stock">Stock</label>
        <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', 0) }}" required>
      </div>

      <div class="field">
        <label class="label" for="image">Image</label>
        <input id="image" type="file" name="image" accept="image/*">
      </div>

      <div style="display:flex; gap:10px;">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary">Create Product</button>
      </div>
    </form>
  </div>
@endsection
