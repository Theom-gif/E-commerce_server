@extends('admin.layout')

@section('title', 'Edit Category')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  .category-edit-page {
    position: relative;
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    color: #0f172a;
  }

  .category-edit-page * {
    box-sizing: border-box;
  }

  .category-edit-page::before,
  .category-edit-page::after {
    content: '';
    position: fixed;
    z-index: 0;
    pointer-events: none;
    border-radius: 999px;
    filter: blur(18px);
    opacity: 0.35;
  }

  .category-edit-page::before {
    top: 70px;
    right: -110px;
    width: 240px;
    height: 240px;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.18) 0%, rgba(16, 185, 129, 0) 72%);
  }

  .category-edit-page::after {
    bottom: 30px;
    left: -90px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.16) 0%, rgba(59, 130, 246, 0) 72%);
  }

  .category-shell {
    position: relative;
    z-index: 1;
    display: grid;
    gap: 16px;
  }

  .category-hero {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 18px;
    padding: 24px 26px;
    border-radius: 26px;
    color: #fff;
    background:
      radial-gradient(circle at top left, rgba(255, 255, 255, 0.14), transparent 34%),
      linear-gradient(135deg, #0f2a1c 0%, #111827 55%, #059669 100%);
    box-shadow: 0 20px 38px rgba(15, 23, 42, 0.16);
    overflow: hidden;
  }

  .category-hero > * {
    position: relative;
    z-index: 1;
  }

  .category-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.9);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
  }

  .category-hero h1 {
    margin: 14px 0 0;
    font-size: clamp(28px, 3vw, 40px);
    line-height: 1.04;
    letter-spacing: -0.05em;
    font-weight: 800;
  }

  .category-hero p {
    margin: 10px 0 0;
    max-width: 680px;
    color: rgba(255, 255, 255, 0.8);
    font-size: 14px;
    line-height: 1.7;
  }

  .category-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 10px;
  }

  .category-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 16px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    white-space: nowrap;
  }

  .category-action:hover {
    transform: translateY(-1px);
  }

  .category-action.secondary {
    color: #fff;
    background: rgba(255, 255, 255, 0.43);
    border: 1px solid rgba(255, 255, 255, 0.16);
  }

  .category-action.primary {
    background: #ffffff;
    color: #184318;
    box-shadow: 0 10px 22px rgba(15, 23, 42, 0.16);
  }

  .category-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(280px, 0.85fr);
    gap: 16px;
  }

  @media (max-width: 1080px) {
    .category-hero {
      flex-direction: column;
    }

    .category-actions {
      justify-content: flex-start;
    }

    .category-grid {
      grid-template-columns: 1fr;
    }
  }

  .category-card {
    background: #ffffff;
    border: 1px solid #e8edf2;
    border-radius: 22px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
    overflow: hidden;
  }

  .category-card-head {
    padding: 18px 20px;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    border-bottom: 1px solid #f1f5f9;
    background: linear-gradient(180deg, #fbfdff 0%, #ffffff 100%);
  }

  .category-card-head h2 {
    margin: 0;
    font-size: 16px;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: #0f172a;
  }

  .category-card-head p {
    margin: 4px 0 0;
    font-size: 12px;
    color: #64748b;
  }

  .category-card-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 999px;
    background: #ecfdf5;
    color: #059669;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    white-space: nowrap;
  }

  .category-card-body {
    padding: 20px;
  }

  .category-preview {
    display: grid;
    gap: 12px;
  }

  .preview-panel {
    padding: 16px;
    border-radius: 18px;
    border: 1px solid #e8edf2;
    background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
  }

  .preview-label {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #64748b;
  }

  .preview-value {
    margin-top: 8px;
    font-size: 18px;
    font-weight: 800;
    letter-spacing: -0.03em;
    color: #0f172a;
  }

  .preview-sub {
    margin-top: 6px;
    font-size: 12px;
    color: #64748b;
    line-height: 1.6;
  }

  .edit-form {
    display: grid;
    gap: 16px;
  }

  .field-group {
    display: grid;
    gap: 8px;
  }

  .field-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #475569;
  }

  .field-hint {
    font-size: 11px;
    font-weight: 600;
    color: #94a3b8;
    text-transform: none;
    letter-spacing: 0;
  }

  .field-input {
    width: 100%;
    padding: 13px 14px;
    border-radius: 14px;
    border: 1.5px solid #dbe3ec;
    background: #f8fafc;
    font-size: 14px;
    color: #0f172a;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
  }

  .field-input:focus {
    outline: none;
    background: #ffffff;
    border-color: #059669;
    box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.12);
  }

  .field-input::placeholder {
    color: #94a3b8;
  }

  .field-note {
    font-size: 12px;
    color: #64748b;
    line-height: 1.6;
  }

  .form-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
    padding-top: 4px;
  }

  .form-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid transparent;
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease;
    cursor: pointer;
  }

  .form-btn:hover {
    transform: translateY(-1px);
  }

  .form-btn.secondary {
    background: #f8fafc;
    border-color: #dbe3ec;
    color: #334155;
  }

  .form-btn.primary {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: #ffffff;
    box-shadow: 0 12px 22px rgba(5, 150, 105, 0.18);
  }

  .form-summary {
    display: grid;
    gap: 12px;
  }

  .summary-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 14px;
    border: 1px solid #eef2f7;
    border-radius: 16px;
    background: #fbfdff;
  }

  .summary-label {
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #64748b;
  }

  .summary-value {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    text-align: right;
  }

  .error-box {
    padding: 12px 14px;
    border-radius: 14px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
    font-size: 13px;
    line-height: 1.6;
  }

  @media (max-width: 640px) {
    .category-hero {
      padding: 20px;
    }

    .category-card-head {
      flex-direction: column;
    }

    .category-card-body {
      padding: 16px;
    }

    .form-actions {
      flex-direction: column;
    }

    .form-btn {
      width: 100%;
      justify-content: center;
    }
  }
</style>
@endpush

@section('content')
<div class="category-edit-page">
  <div class="category-shell">
    <section class="category-hero">
      <div>
        {{-- <div class="category-eyebrow">
          <i class="fas fa-pen-to-square"></i>
          Category editor
        </div>
        <h1>Edit Category</h1>
        <p>Update the category name and keep your catalog structure organized without leaving the admin panel.</p>
      </div> --}}

      <div class="category-actions">
        <a href="{{ route('admin.categories.index') }}" class="category-action secondary">
          <i class="fas fa-arrow-left"></i>
          Back to categories
        </a>
      </div>
    </section>

    <div class="category-grid">
      <section class="category-card">
        <div class="category-card-head">
          <div>
            <h2>Category Details</h2>
            <p>Change the visible name used across the storefront.</p>
          </div>
          <span class="category-card-tag">
            <i class="fas fa-tag"></i>
            ID #{{ $category->id }}
          </span>
        </div>

        <div class="category-card-body">
          @if ($errors->any())
            <div class="error-box">
              <strong>Please fix the highlighted issue.</strong>
              <div>{{ $errors->first() }}</div>
            </div>
          @endif

          <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="edit-form">
            @csrf
            @method('PUT')

            <div class="field-group">
              <label class="field-label" for="name">
                Category Name
                <span class="field-hint">Required</span>
              </label>
              <input
                id="name"
                type="text"
                name="name"
                class="field-input"
                value="{{ old('name', $category->name) }}"
                placeholder="Enter category name"
                required
              >
              <div class="field-note">Use a short, clear name that customers can recognize quickly.</div>
            </div>

            <div class="form-actions">
              <a href="{{ route('admin.categories.index') }}" class="form-btn secondary">
                <i class="fas fa-xmark"></i>
                Cancel
              </a>
              <button type="submit" class="form-btn primary">
                <i class="fas fa-check"></i>
                Update Category
              </button>
            </div>
          </form>
        </div>
      </section>

      <aside class="category-card">
        <div class="category-card-head">
          <div>
            <h2>Quick Preview</h2>
            <p>A small snapshot of the category record.</p>
          </div>
        </div>

        <div class="category-card-body">
          <div class="category-preview">
            <div class="preview-panel">
              <div class="preview-label">Current name</div>
              <div class="preview-value">{{ $category->name }}</div>
              <div class="preview-sub">This is what appears in the admin list and storefront filters.</div>
            </div>

            <div class="form-summary">
              <div class="summary-item">
                <div class="summary-label">Category ID</div>
                <div class="summary-value">#{{ $category->id }}</div>
              </div>
              <div class="summary-item">
                <div class="summary-label">Updated At</div>
                <div class="summary-value">{{ $category->updated_at?->format('M d, Y') ?? 'N/A' }}</div>
              </div>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>
</div>
@endsection
