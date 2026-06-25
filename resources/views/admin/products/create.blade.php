@extends('admin.layout')

@section('title', 'New Product')

@section('header')
  <h1>New Product</h1>
  <p>Add product to catalog.</p>
@endsection

@include('admin.products._styles')

@section('content')
  <div class="product-create">
    <p class="page-eyebrow">Catalog</p>
    <h1 class="page-title">New product</h1>

    <div class="form-panel">
      <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf

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
                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
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
            <input id="name" type="text" name="name" value="{{ old('name') }}"
              placeholder="e.g. Classic Linen Shirt" required>
          </div>

          <div class="field">
            <label class="label" for="description">Description</label>
            <textarea id="description" name="description"
              rows="4" placeholder="What makes this product special…">{{ old('description') }}</textarea>
          </div>

          <div class="field-row-2">
            <div class="field" style="border-bottom:none; padding:0;">
              <label class="label" for="author">Author / Brand</label>
              <input id="author" type="text" name="author" value="{{ old('author') }}" placeholder="e.g. John Doe">
            </div>
            <div class="field" style="border-bottom:none; padding:0; display:flex; flex-direction:row; align-items:center; gap:10px; margin-top: 28px;">
              <input id="featured" type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} style="width:auto;">
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
            <input id="price" type="number" step="0.01" min="0"
              name="price" value="{{ old('price') }}" placeholder="0.00" required>
          </div>

          <div class="field">
            <label class="label" for="stock">
              Stock <span class="req">*</span>
            </label>
            <input id="stock" type="number" min="0"
              name="stock" value="{{ old('stock', 0) }}" placeholder="0" required>
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
              <span class="upload-label">Click to upload an image</span>
              <p class="upload-hint">PNG, JPG, WEBP — max 5 MB</p>
              <input id="image" type="file" name="image" accept="image/*">
            </label>
          </div>
        </div>

        {{-- Action bar --}}
        <div class="form-action-bar">
          <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            ← Back
          </a>
          <button type="submit" class="btn btn-primary">
            + Create product
          </button>
        </div>

      </form>
    </div>
  </div>
@endsection