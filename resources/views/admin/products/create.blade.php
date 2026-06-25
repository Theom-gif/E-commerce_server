@extends('admin.layout')

@section('title', 'New Product')

@section('header')
  <h1>New Product</h1>
  <p>Add product to catalog.</p>
@endsection

@section('styles')
<style>
  /* ── Page layout ──────────────────────────────────── */
  .product-create {
    max-width: 660px;
    margin: 0 auto;
    padding: 2rem 1rem 6rem;
    font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', sans-serif;
  }

  .product-create .page-eyebrow {
    font-size: 11px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #9CA3AF;
    margin: 0 0 4px;
  }

  .product-create .page-title {
    font-size: 22px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 1.75rem;
  }

  /* ── Form card ────────────────────────────────────── */
  .form-panel {
    background: #ffffff;
    border: 0.5px solid #E5E7EB;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
  }

  /* ── Section labels ───────────────────────────────── */
  .form-section-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    color: #9CA3AF;
    padding: 0.9rem 1.5rem 0.5rem;
    border-bottom: 0.5px solid #F3F4F6;
    margin: 0;
    background: #FAFAFA;
  }

  /* ── Fields container ─────────────────────────────── */
  .form-fields {
    padding: 0 1.5rem 0.5rem;
  }

  /* ── Individual field ─────────────────────────────── */
  .field {
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding: 0.85rem 0;
    border-bottom: 0.5px solid #F3F4F6;
  }

  .field:last-child {
    border-bottom: none;
  }

  .field .label {
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    letter-spacing: 0.02em;
  }

  .field .label .req {
    color: #5B6AF0;
    margin-left: 2px;
  }

  /* ── Inputs, select, textarea ─────────────────────── */
  .field input[type="text"],
  .field input[type="number"],
  .field select,
  .field textarea {
    border: 0.5px solid #D1D5DB;
    border-radius: 8px;
    padding: 9px 12px;
    font-size: 14px;
    font-family: inherit;
    color: #111827;
    background: #ffffff;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
    width: 100%;
    box-sizing: border-box;
  }

  .field input[type="text"]:focus,
  .field input[type="number"]:focus,
  .field select:focus,
  .field textarea:focus {
    border-color: #5B6AF0;
    box-shadow: 0 0 0 3px rgba(91, 106, 240, 0.12);
  }

  .field input::placeholder,
  .field textarea::placeholder {
    color: #D1D5DB;
  }

  .field textarea {
    resize: vertical;
    min-height: 96px;
    line-height: 1.6;
  }

  /* ── Two-column row (price + stock) ───────────────── */
  .field-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    padding: 0.85rem 1.5rem;
    border-bottom: 0.5px solid #F3F4F6;
  }

  .field-row-2 .field {
    padding: 0;
    border-bottom: none;
  }

  /* ── File upload zone ─────────────────────────────── */
  .file-upload-zone {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: 1.5px dashed #D1D5DB;
    border-radius: 10px;
    padding: 1.5rem 1rem;
    text-align: center;
    cursor: pointer;
    background: #FAFAFA;
    transition: border-color 0.15s, background 0.15s;
  }

  .file-upload-zone:hover {
    border-color: #5B6AF0;
    background: #F5F6FF;
  }

  .file-upload-zone svg {
    color: #9CA3AF;
  }

  .file-upload-zone .upload-label {
    font-size: 13px;
    font-weight: 500;
    color: #374151;
  }

  .file-upload-zone .upload-hint {
    font-size: 11px;
    color: #9CA3AF;
    margin: 0;
  }

  .file-upload-zone input[type="file"] {
    display: none;
  }

  /* ── Sticky action bar ────────────────────────────── */
  .form-action-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.9rem 1.5rem;
    border-top: 0.5px solid #E5E7EB;
    background: #ffffff;
    position: sticky;
    bottom: 0;
  }

  /* ── Buttons ──────────────────────────────────────── */
  .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 500;
    font-family: inherit;
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.12s, border-color 0.12s;
    line-height: 1;
  }

  .btn-secondary {
    background: #ffffff;
    border: 0.5px solid #D1D5DB;
    color: #6B7280;
  }

  .btn-secondary:hover {
    background: #F9FAFB;
    border-color: #9CA3AF;
    color: #374151;
  }

  .btn-primary {
    background: #5B6AF0;
    border: none;
    color: #ffffff;
  }

  .btn-primary:hover {
    background: #4857E8;
  }

  .btn-primary:active {
    transform: scale(0.98);
  }
</style>
@endsection

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