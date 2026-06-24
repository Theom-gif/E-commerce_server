@extends('admin.layout')

@section('title', 'Categories')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  /* Layout overrides to fix grid column containment */
  /* Layout control moved to admin.layout */

  /* Custom typography & page layout */
  .cat-container {
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    color: #1e293b;
    padding: 4px;
    max-width: 100%;
    width: 100%;
  }

  .cat-container * {
    box-sizing: border-box;
  }

  /* Header design */
  .cat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    gap: 16px;
    flex-wrap: wrap;
  }
  .cat-title-area h1 {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.5px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .cat-title-area p {
    color: #64748b;
    font-size: 13.5px;
    margin: 4px 0 0;
  }

  /* Create Category button */
  .btn-create-cat {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
  }
  .btn-create-cat:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: #ffffff;
  }
  .btn-create-cat:active {
    transform: translateY(0);
  }

  /* KPI metric cards */
  .cat-kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }
  .cat-kpi-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
  }
  .cat-kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    border-color: #cbd5e1;
  }
  .cat-kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
  }
  .cat-kpi-card.kpi-total::before { background: #064e3b; }
  .cat-kpi-card.kpi-products::before { background: #10b981; }
  .cat-kpi-card.kpi-empty::before { background: #f59e0b; }

  .cat-kpi-info {
    display: flex;
    flex-direction: column;
  }
  .cat-kpi-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    letter-spacing: 0.8px;
  }
  .cat-kpi-val {
    font-size: 26px;
    font-weight: 800;
    color: #0f172a;
    margin-top: 4px;
    line-height: 1;
  }
  .cat-kpi-sub {
    font-size: 11.5px;
    color: #94a3b8;
    margin-top: 6px;
  }
  .cat-kpi-icon-wrap {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }
  .cat-kpi-card.kpi-total .cat-kpi-icon-wrap { background: #ecfdf5; color: #064e3b; }
  .cat-kpi-card.kpi-products .cat-kpi-icon-wrap { background: #ecfdf5; color: #10b981; }
  .cat-kpi-card.kpi-empty .cat-kpi-icon-wrap { background: #fffbeb; color: #f59e0b; }

  /* Toolbar design */
  .cat-toolbar-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
  }
  .cat-search-form {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    width: 100%;
  }
  .cat-search-input-wrap {
    position: relative;
    flex: 1;
    min-width: 260px;
  }
  .cat-search-input-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 14px;
    pointer-events: none;
  }
  .cat-search-input {
    width: 100%;
    padding: 10px 14px 10px 40px !important;
    border-radius: 12px !important;
    border: 1.5px solid #e2e8f0 !important;
    background: #f8fafc !important;
    font-size: 13.5px !important;
    transition: all 0.2s ease !important;
  }
  .cat-search-input:focus {
    background: #ffffff !important;
    border-color: #064e3b !important;
    box-shadow: 0 0 0 4px rgba(6, 78, 59, 0.1) !important;
    outline: none;
  }
  .cat-btn-search {
    background: #064e3b;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 13.5px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
  }
  .cat-btn-search:hover {
    background: #043e2e;
  }
  .cat-btn-clear {
    background: #f1f5f9;
    color: #475569;
    padding: 10px 16px;
    border-radius: 12px;
    font-weight: 500;
    font-size: 13.5px;
    border: 1px solid #e2e8f0;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
  }
  .cat-btn-clear:hover {
    background: #e2e8f0;
    color: #1e293b;
  }

  /* Card containing table */
  .cat-table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    overflow: hidden;
    margin-bottom: 24px;
    width: 100%;
  }
  .cat-table-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .cat-table-header h3 {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
  }
  .cat-table-header p {
    font-size: 12px;
    color: #64748b;
    margin: 2px 0 0;
  }

  /* Responsive Table Wrapper */
  .cat-table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  /* Table elements */
  .cat-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 650px; /* Ensure columns maintain proper structure when scrolling horizontally */
  }
  .cat-table th {
    background: #f8fafc;
    padding: 14px 24px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #475569;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
  }
  .cat-table td {
    padding: 16px 24px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
    vertical-align: middle;
    color: #334155;
    transition: background-color 0.15s ease;
  }
  .cat-table tbody tr:hover td {
    background-color: #f8fafc;
  }
  .cat-table tbody tr:last-child td {
    border-bottom: none;
  }

  /* Custom styled cells */
  .cat-id-badge {
    background: #f1f5f9;
    color: #64748b;
    font-family: monospace;
    font-size: 12px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-block;
  }
  .cat-name-cell {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: #0f172a;
  }
  .cat-name-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #ecfdf5;
    color: #064e3b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
  }
  .cat-slug-badge {
    background: #f8fafc;
    color: #475569;
    border: 1px dashed #cbd5e1;
    font-family: monospace;
    font-size: 12.5px;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-block;
    word-break: break-all;
    max-width: 220px;
    white-space: normal;
  }

  /* Status / count badges */
  .pill-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.2s ease;
    white-space: nowrap;
  }
  .pill-count.has-products {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
  }
  .pill-count.no-products {
    background: #f8fafc;
    color: #64748b;
    border: 1px solid #cbd5e1;
  }

  /* Action button configurations */
  .cat-actions {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .cat-btn-action {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
    text-decoration: none;
    flex-shrink: 0;
  }
  .cat-btn-action.edit {
    background: #f0fdfa;
    color: #0d9488;
    border-color: #ccfbf1;
  }
  .cat-btn-action.edit:hover {
    background: #0d9488;
    color: #ffffff;
    transform: translateY(-1px);
  }
  .cat-btn-action.delete {
    background: #fef2f2;
    color: #ef4444;
    border-color: #fee2e2;
    outline: none;
  }
  .cat-btn-action.delete:hover {
    background: #ef4444;
    color: #ffffff;
    transform: translateY(-1px);
  }

  /* Paginate overrides */
  .cat-paginate-wrap {
    padding: 16px 24px;
    border-top: 1px solid #f1f5f9;
    background: #f8fafc;
  }
  .cat-paginate-wrap nav {
    width: 100%;
  }

  /* Empty State visual */
  .cat-empty-state {
    text-align: center;
    padding: 48px 24px;
  }
  .cat-empty-icon {
    font-size: 40px;
    color: #cbd5e1;
    margin-bottom: 12px;
  }
  .cat-empty-title {
    font-size: 16px;
    font-weight: 600;
    color: #475569;
    margin: 0;
  }
  .cat-empty-desc {
    font-size: 13.5px;
    color: #94a3b8;
    margin: 4px 0 16px;
  }

  /* Small device adjustments */
  @media (max-width: 640px) {
    .cat-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
    }
    .btn-create-cat {
      width: 100%;
      justify-content: center;
    }
    .cat-search-form {
      flex-direction: column;
      align-items: stretch;
    }
    .cat-search-input-wrap {
      min-width: 100%;
    }
    .cat-btn-search, .cat-btn-clear {
      width: 100%;
      justify-content: center;
    }
  }
</style>
@endpush

@section('content')
<div class="cat-container">
  <!-- Header -->
  <div class="cat-header">
    <div class="cat-title-area">
      <h1><i class="fas fa-folder-tree" style="color: #064e3b;"></i> Product Categories</h1>
      <p>Organize, analyze, and manage catalog groups for your storefront.</p>
    </div>
    <div>
      <a href="{{ route('admin.categories.create') }}" class="btn-create-cat">
        <i class="fas fa-plus"></i>
        <span>Create Category</span>
      </a>
    </div>
  </div>

  <!-- KPI Row -->
  <div class="cat-kpi-grid">
    <!-- Total Categories -->
    <div class="cat-kpi-card kpi-total">
      <div class="cat-kpi-info">
        <span class="cat-kpi-label">Total Categories</span>
        <span class="cat-kpi-val">{{ $categories->total() }}</span>
        <span class="cat-kpi-sub">Across all pages</span>
      </div>
      <div class="cat-kpi-icon-wrap">
        <i class="fas fa-tags"></i>
      </div>
    </div>

    <!-- Active Products in page -->
    <div class="cat-kpi-card kpi-products">
      <div class="cat-kpi-info">
        <span class="cat-kpi-label">Assigned Products</span>
        <span class="cat-kpi-val">{{ $categories->sum('products_count') }}</span>
        <span class="cat-kpi-sub">On this page</span>
      </div>
      <div class="cat-kpi-icon-wrap">
        <i class="fas fa-box-open"></i>
      </div>
    </div>

    <!-- Empty Categories in page -->
    <div class="cat-kpi-card kpi-empty">
      <div class="cat-kpi-info">
        <span class="cat-kpi-label">Empty Categories</span>
        <span class="cat-kpi-val">{{ $categories->filter(fn($c) => $c->products_count == 0)->count() }}</span>
        <span class="cat-kpi-sub">On this page</span>
      </div>
      <div class="cat-kpi-icon-wrap">
        <i class="fas fa-triangle-exclamation"></i>
      </div>
    </div>
  </div>

  <!-- Toolbar / Search -->
  <div class="cat-toolbar-card">
    <form method="GET" action="{{ route('admin.categories.index') }}" class="cat-search-form">
      <div class="cat-search-input-wrap">
        <i class="fas fa-search"></i>
        <input type="text" name="search" class="cat-search-input" placeholder="Search categories by name..." value="{{ request('search') }}">
      </div>
      <button type="submit" class="cat-btn-search">
        <span>Search</span>
      </button>
      @if(request('search'))
        <a href="{{ route('admin.categories.index') }}" class="cat-btn-clear">
          <i class="fas fa-xmark" style="margin-right: 6px;"></i> Clear
        </a>
      @endif
    </form>
  </div>

  <!-- Table Card -->
  <div class="cat-table-card">
    <div class="cat-table-header">
      <div>
        <h3>Categories Catalog</h3>
        <p>Displaying page {{ $categories->currentPage() }} of {{ $categories->lastPage() }}</p>
      </div>
    </div>

    <div class="cat-table-responsive">
      <table class="cat-table">
        <thead>
          <tr>
            <th width="10%">ID</th>
            <th width="35%">Category Name</th>
            <th width="30%">URL Slug</th>
            <th width="15%">Product Count</th>
            <th width="10%" style="text-align: center;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $category)
            <tr>
              <td>
                <span class="cat-id-badge">#{{ $category->id }}</span>
              </td>
              <td>
                <div class="cat-name-cell">
                  <div class="cat-name-icon">
                    <i class="fas fa-folder"></i>
                  </div>
                  <span>{{ $category->name }}</span>
                </div>
              </td>
              <td>
                <span class="cat-slug-badge">{{ $category->slug }}</span>
              </td>
              <td>
                @if($category->products_count > 0)
                  <span class="pill-count has-products">
                    <i class="fas fa-check-circle" style="font-size: 11px;"></i>
                    {{ $category->products_count }} products
                  </span>
                @else
                  <span class="pill-count no-products">
                    <i class="fas fa-circle-minus" style="font-size: 11px;"></i>
                    0 products
                  </span>
                @endif
              </td>
              <td>
                <div class="cat-actions">
                  <a href="{{ route('admin.categories.edit', $category) }}" class="cat-btn-action edit" title="Edit Category">
                    <i class="fas fa-pen"></i>
                  </a>
                  <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.');" style="margin: 0; display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cat-btn-action delete" title="Delete Category">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5">
                <div class="cat-empty-state">
                  <div class="cat-empty-icon">
                    <i class="fas fa-folder-open"></i>
                  </div>
                  <h4 class="cat-empty-title">No Categories Found</h4>
                  <p class="cat-empty-desc">
                    @if(request('search'))
                      No categories matched your search criteria "{{ request('search') }}".
                    @else
                      Start by creating your first storefront category using the action button above.
                    @endif
                  </p>
                  @if(request('search'))
                    <a href="{{ route('admin.categories.index') }}" class="cat-btn-search" style="text-decoration: none;">
                      <span>Clear Search Filter</span>
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($categories->hasPages())
      <div class="cat-paginate-wrap">
        {{ $categories->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
