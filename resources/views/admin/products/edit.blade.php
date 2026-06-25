@extends('admin.layout')

@section('title', 'Edit Product')

@section('header')
  <h1>Edit Product</h1>
  <p>Update product details and image.</p>
@endsection

@include('admin.products._styles')

@section('content')
  <div class="product-create">
    <p class="page-eyebrow">Catalog</p>
    <h1 class="page-title">Edit product</h1>

    <div class="form-panel">
      <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Classification --}}
        <p class="form-section-label">Classification</p>
        <div class="form-fields">
          <div class="field">
            <label class="label" for="category_id">
              Category <span class="req">*</span>
            </label>
            <select id="category_id" name="category_id" required>
              <option value="">Select a category…</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Details --}}
        <p class="form-section-label">Details</p>
        <div class="form-fields">
          <div class="field">
            <label class="label" for="name">
              Name <span class="req">*</span>
            </label>
            <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" required>
          </div>

          <div class="field">
            <label class="label" for="description">Description</label>
            <textarea id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
          </div>

          <div class="field-row-2">
            <div class="field" style="border-bottom:none; padding:0;">
              <label class="label" for="author">Author / Brand</label>
              <input id="author" type="text" name="author" value="{{ old('author', $product->author) }}" placeholder="e.g. John Doe">
            </div>
            <div class="field" style="border-bottom:none; padding:0; display:flex; flex-direction:row; align-items:center; gap:10px; margin-top: 28px;">
              <input id="featured" type="checkbox" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }} style="width:auto;">
              <label class="label" for="featured" style="margin:0; cursor:pointer;">Mark as Featured Product</label>
            </div>
          </div>
        </div>

        {{-- Pricing & inventory (2-col row) --}}
        <p class="form-section-label">Pricing &amp; inventory</p>
        <div class="field-row-2">
          <div class="field">
            <label class="label" for="price">
              Price <span class="req">*</span>
            </label>
            <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" required>
          </div>

          <div class="field">
            <label class="label" for="stock">
              Stock <span class="req">*</span>
            </label>
            <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', $product->stock) }}" required>
          </div>
        </div>

        {{-- Media --}}
        <p class="form-section-label">Media</p>
        <div class="form-fields" style="padding-bottom:1.25rem;">
          <div class="field" style="border-bottom:none;">
            <label class="label" for="image">Product image</label>
            <label class="file-upload-zone" for="image">
              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="17 8 12 3 7 8"/>
                <line x1="12" y1="3" x2="12" y2="15"/>
              </svg>
              <span class="upload-label">Click to upload a new image</span>
              <p class="upload-hint">PNG, JPG, WEBP — max 5 MB</p>
              <input id="image" type="file" name="image" accept="image/*">
            </label>
            <div class="preview">
              @if($product->image && str_starts_with($product->image, 'product_images/'))
                <p style="font-size:12px; color:#64748b; margin-top:10px; font-weight:600;">Current Image:</p>
                <img src="{{ asset('storage/'.$product->image) }}" alt="Current Image" style="margin-top:4px;">
              @endif
            </div>
          </div>
        </div>

        {{-- Action bar --}}
        <div class="form-action-bar">
          <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            ← Back
          </a>
          <button type="submit" class="btn btn-primary">
            ✓ Update product
          </button>
        </div>

      </form>
    </div>
  </div>
@endsection
