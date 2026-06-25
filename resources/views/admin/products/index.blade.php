@extends('admin.layout')

@section('title', 'Products')

@push('styles')
<style>
  /* ── Page background ─────────────────────────── */
  .pt-page { background: #F5F6FA; min-height: 100vh; padding: 24px; }

  /* ── Toolbar ─────────────────────────────────── */
  .pt-toolbar {
    display: flex; align-items: center;
    justify-content: space-between;
    flex-wrap: wrap; gap: 12px; margin-bottom: 20px;
  }
  .pt-toolbar-left h2 { font-size: 19px; font-weight: 700; color: #1a1a2e; margin: 0 0 2px; }
  .pt-toolbar-left p  { font-size: 12px; color: #8890a4; margin: 0; }
  .pt-toolbar-right   { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

  .pt-search {
    display: flex; align-items: center;
    border: 1.5px solid #E2E6F0; border-radius: 10px;
    overflow: hidden; background: #fff; transition: border-color .15s;
  }
  .pt-search:focus-within { border-color: #6C63FF; }
  .pt-search input {
    border: none; outline: none; padding: 8px 13px;
    font-size: 13px; color: #1a1a2e; background: transparent; width: 220px;
  }
  .pt-search input::placeholder { color: #c5cad8; }
  .pt-search button {
    border: none; background: none; padding: 8px 11px;
    cursor: pointer; color: #94A3B8; font-size: 14px;
    border-left: 1px solid #F1F5F9; transition: background .12s;
  }
  .pt-search button:hover { background: #F8FAFC; color: #6C63FF; }

  .pt-btn-create {
    display: inline-flex; align-items: center; gap: 6px;
    background: linear-gradient(135deg, #6C63FF, #8B82FF);
    color: #fff; border: none; border-radius: 10px;
    padding: 9px 18px; font-size: 13px; font-weight: 600;
    cursor: pointer; text-decoration: none;
    box-shadow: 0 4px 14px rgba(108,99,255,.35);
    transition: box-shadow .15s, transform .1s;
  }
  .pt-btn-create:hover { box-shadow: 0 6px 20px rgba(108,99,255,.45); transform: translateY(-1px); }
  .pt-btn-create:active { transform: scale(.98); }

  /* ── KPI stat cards ──────────────────────────── */
  .pt-stats {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 12px; margin-bottom: 20px;
  }
  @media(max-width:900px) { .pt-stats { grid-template-columns: repeat(2,1fr); } }
  @media(max-width:500px) { .pt-stats { grid-template-columns: 1fr; } }

  .pt-stat {
    border-radius: 14px; padding: 16px 18px;
    position: relative; overflow: hidden;
    transition: transform .18s ease, box-shadow .18s ease;
  }
  .pt-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
  .pt-stat::after {
    content: ''; position: absolute; bottom: -16px; right: -16px;
    width: 72px; height: 72px; border-radius: 50%;
    background: rgba(255,255,255,.12); pointer-events: none;
  }
  .pt-stat.--purple { background: linear-gradient(135deg, #6C63FF, #9B94FF); }
  .pt-stat.--green  { background: linear-gradient(135deg, #10B981, #34D399); }
  .pt-stat.--amber  { background: linear-gradient(135deg, #F59E0B, #FCD34D); }
  .pt-stat.--red    { background: linear-gradient(135deg, #EF4444, #F87171); }

  .pt-stat-label {
    font-size: 10.5px; font-weight: 700; letter-spacing: .07em;
    text-transform: uppercase; color: rgba(255,255,255,.7); margin-bottom: 6px;
  }
  .pt-stat-val  { font-size: 24px; font-weight: 800; color: #fff; letter-spacing: -.5px; }
  .pt-stat-sub  { font-size: 11px; color: rgba(255,255,255,.55); margin-top: 3px; }

  /* ── Table card ──────────────────────────────── */
  .pt-card {
    background: #fff; border-radius: 16px;
    border: 1px solid #EAECF4; overflow: hidden;
    box-shadow: 0 2px 12px rgba(30,40,90,.06);
  }

  .pt-table { width: 100%; border-collapse: collapse; }

  .pt-table thead tr { background: linear-gradient(90deg, #6C63FF 0%, #8B82FF 100%); }
  .pt-table thead th {
    padding: 13px 16px; font-size: 11px; font-weight: 700;
    color: rgba(255,255,255,.88); text-transform: uppercase;
    letter-spacing: .07em; text-align: left; white-space: nowrap;
  }
  .pt-table thead th.th-center { text-align: center; }

  .pt-table tbody tr { border-bottom: 1px solid #F3F4F9; transition: background .12s; }
  .pt-table tbody tr:last-child { border-bottom: none; }
  .pt-table tbody tr:hover { background: #F8F7FF; }
  .pt-table tbody td { padding: 13px 16px; font-size: 13px; color: #1a1a2e; vertical-align: middle; }

  .pt-image-cell { width: 74px; }
  .pt-thumb,
  .pt-thumb-placeholder {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    border: 1px solid #E2E6F0;
    background: #F8FAFC;
  }
  .pt-thumb {
    display: block;
    object-fit: cover;
  }
  .pt-thumb-placeholder {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #94A3B8;
    font-size: 16px;
  }
  .pt-name { font-weight: 600; color: #1a1a2e; }
  .pt-price{ font-weight: 700; color: #1a1a2e; }

  /* Category pills — colored per category via data attribute */
  .pt-cat { display: inline-block; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 999px; }
  .cat-electronics { background: #EEF2FF; color: #4338CA; }
  .cat-furniture   { background: #FDF2F8; color: #9D174D; }
  .cat-accessories { background: #ECFDF5; color: #065F46; }
  .cat-clothing    { background: #FFF7ED; color: #C2410C; }
  .cat-food        { background: #FEF9C3; color: #854D0E; }
  .cat-default     { background: #F1F5F9; color: #475569; }

  /* Stock badges */
  .pt-stock { display: inline-flex; align-items: center; gap: 4px; font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 999px; }
  .pt-stock.high { background: #D1FAE5; color: #065F46; }
  .pt-stock.low  { background: #FEF3C7; color: #92400E; }
  .pt-stock.out  { background: #FEE2E2; color: #991B1B; }
  .pt-stock i    { font-size: 11px; }

  /* Action buttons */
  .pt-actions { display: flex; align-items: center; justify-content: center; gap: 6px; }
  .pt-btn-edit {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 13px; border-radius: 8px;
    border: 1.5px solid #6C63FF; background: #F0EFFF;
    color: #4338CA; font-size: 12px; font-weight: 600;
    cursor: pointer; text-decoration: none; transition: background .12s;
  }
  .pt-btn-edit:hover { background: #E0DEFF; }
  .pt-btn-delete {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 13px; border-radius: 8px;
    border: 1.5px solid #FECACA; background: #FEF2F2;
    color: #991B1B; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: background .12s;
  }
  .pt-btn-delete:hover { background: #FECACA; }

  /* Empty state */
  .pt-empty { text-align: center; padding: 56px 16px; color: #94A3B8; font-size: 13px; }
  .pt-empty i { font-size: 30px; display: block; margin-bottom: 10px; opacity: .4; }

  /* Pagination */
  .pt-pagination {
    display: flex; align-items: center; justify-content: space-between;
    padding: 13px 16px; border-top: 1px solid #F1F5F9;
    background: #FAFBFF; gap: 12px; flex-wrap: wrap;
  }
  .pt-pagination-info { font-size: 12px; color: #8890a4; }
  .pt-pagination nav { display: flex; align-items: center; gap: 3px; }
  .pt-pagination nav span,
  .pt-pagination nav a {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 30px; height: 30px; padding: 0 6px;
    border-radius: 8px; border: 1px solid #E2E6F0;
    background: #fff; font-size: 12px; font-weight: 500;
    color: #64748B; text-decoration: none; transition: all .12s;
  }
  .pt-pagination nav a:hover { border-color: #6C63FF; color: #6C63FF; background: #F0EFFF; }
  .pt-pagination nav span[aria-current="page"] {
    background: #6C63FF; color: #fff; border-color: #6C63FF;
    box-shadow: 0 3px 8px rgba(108,99,255,.35);
  }
  .pt-pagination nav span.disabled { opacity: .4; cursor: not-allowed; }
</style>
@endpush

@section('content')
<div class="pt-page">

  {{-- Toolbar --}}
  <div class="pt-toolbar">
    <div class="pt-toolbar-left">
      <h2>Products</h2>
      <p>Manage your product catalog.</p>
    </div>
    <div class="pt-toolbar-right">
      <form method="GET" action="{{ route('admin.products.index') }}" class="pt-search">
        <input type="text" name="search" placeholder="Search products…" value="{{ request('search') }}">
        <button type="submit" aria-label="Search"><i class="fas fa-search"></i></button>
      </form>
      <a href="{{ route('admin.products.create') }}" class="pt-btn-create">
        <i class="fas fa-plus"></i> Create product
      </a>
    </div>
  </div>

  {{-- KPI stat cards --}}
  <div class="pt-stats">
    <div class="pt-stat --purple">
      <div class="pt-stat-label">Total products</div>
      <div class="pt-stat-val">{{ number_format($totalProducts ?? $products->total()) }}</div>
      <div class="pt-stat-sub">Active listings</div>
    </div>
    <div class="pt-stat --green">
      <div class="pt-stat-label">In stock</div>
      <div class="pt-stat-val">{{ number_format($inStock ?? 0) }}</div>
      <div class="pt-stat-sub">Available units</div>
    </div>
    <div class="pt-stat --amber">
      <div class="pt-stat-label">Low stock</div>
      <div class="pt-stat-val">{{ number_format($lowStock ?? 0) }}</div>
      <div class="pt-stat-sub">≤ 10 units</div>
    </div>
    <div class="pt-stat --red">
      <div class="pt-stat-label">Out of stock</div>
      <div class="pt-stat-val">{{ number_format($outOfStock ?? 0) }}</div>
      <div class="pt-stat-sub">Needs restocking</div>
    </div>
  </div>

  {{-- Table card --}}
  <div class="pt-card">
    <table class="pt-table">
      <thead>
        <tr>
          <th>Image</th>
          <th>Product name</th>
          <th>Category</th>
          <th>Price</th>
          <th>Stock</th>
          <th class="th-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $product)
          @php
            $catSlug = $product->category
              ? strtolower(str_replace(' ', '-', $product->category->name))
              : 'default';
            $catClass = in_array($catSlug, ['electronics','furniture','accessories','clothing','food'])
              ? 'cat-' . $catSlug
              : 'cat-default';
          @endphp
          <tr>
            <td class="pt-image-cell">
              @if($product->image)
                <img
                  src="{{ asset('storage/' . $product->image) }}"
                  alt="{{ $product->name }}"
                  class="pt-thumb"
                >
              @else
                <span class="pt-thumb-placeholder" title="No image">
                  <i class="fas fa-image"></i>
                </span>
              @endif
            </td>

            <td><span class="pt-name">{{ $product->name }}</span></td>

            <td>
              @if($product->category)
                <span class="pt-cat {{ $catClass }}">{{ $product->category->name }}</span>
              @else
                <span style="color:#CBD5E1;">—</span>
              @endif
            </td>

            <td class="pt-price">${{ number_format($product->price, 2) }}</td>

            <td>
              @if($product->stock === 0)
                <span class="pt-stock out">
                  <i class="fas fa-ban"></i> Out of stock
                </span>
              @elseif($product->stock <= 10)
                <span class="pt-stock low">
                  <i class="fas fa-exclamation-triangle"></i> {{ $product->stock }} low
                </span>
              @else
                <span class="pt-stock high">
                  <i class="fas fa-check-circle"></i> {{ $product->stock }}
                </span>
              @endif
            </td>

            <td>
              <div class="pt-actions">
                <a href="{{ route('admin.products.edit', $product) }}" class="pt-btn-edit">
                  <i class="fas fa-pen"></i> Edit
                </a>
                <form
                  action="{{ route('admin.products.destroy', $product) }}"
                  method="POST"
                  style="display:inline;"
                  onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')"
                >
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="pt-btn-delete">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="pt-empty">
              <i class="fas fa-box-open"></i>
              No products found.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div class="pt-pagination">
      <span class="pt-pagination-info">
        Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
      </span>
      {{ $products->withQueryString()->links('admin.pagination') }}
    </div>
  </div>

</div>
@endsection
