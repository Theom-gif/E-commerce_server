@extends('admin.layout')

@section('title', 'Edit Product')

@section('header')
  <h1>Edit Product</h1>
  <p>Update product details and image.</p>
@endsection

@section('content')
  <div class="card form-panel">
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="field">
        <label class="label" for="category_id">Category</label>
        <select id="category_id" name="category_id" required>
          <option value="">Select category</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="field">
        <label class="label" for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" required>
      </div>

      <div class="field">
        <label class="label" for="description">Description</label>
        <textarea id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
      </div>

      <div class="field">
        <label class="label" for="price">Price</label>
        <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" required>
      </div>

      <div class="field">
        <label class="label" for="stock">Stock</label>
        <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', $product->stock) }}" required>
      </div>

      <div class="field">
        <label class="label" for="image">Image</label>
        <input id="image" type="file" name="image" accept="image/*">
        <div class="preview">
          @if($product->image && str_starts_with($product->image, 'product_images/'))
            <img src="{{ asset('storage/'.$product->image) }}" alt="">
          @endif
        </div>
      </div>

      <div style="display:flex; gap:10px;">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary">Update Product</button>
      </div>
    </form>
  </div>
@endsection
