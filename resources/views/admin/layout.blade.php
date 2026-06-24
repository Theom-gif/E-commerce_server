<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  /* Consistent sidebar/navbar sizing across all admin pages */
  body {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
  }
  .app {
    display: flex !important;
    min-height: 100vh;
    width: 100%;
  }
  .sidebar {
    width: 280px !important;
    flex-shrink: 0;
    position: sticky !important;
    top: 0;
    height: 100vh;
    display: flex;
    flex-direction: column;
    background: #ffffff;
    color: #1e293b;
    padding: 26px 18px;
    border-right: 1px solid #f1f5f9;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03);
    z-index: 10;
  }
  .main {
    flex: 1;
    min-height: 100vh;
    padding: 24px 28px;
    margin-left: 0 !important;
    box-sizing: border-box;
  }
</style>
  @stack('styles')
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="brand">
        <div class="brand-logo">
          <i class="fas fa-cubes"></i>
        </div>
        <div class="brand-text">
          <span class="brand-name">EcoStore</span>
          <span class="brand-badge">Admin Panel</span>
        </div>
      </div>
      <nav class="nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-item @if(request()->routeIs('admin.dashboard')) active @endif">
          <i class="fas fa-chart-pie"></i>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="nav-item @if(request()->routeIs('admin.categories.*')) active @endif">
          <i class="fas fa-tags"></i>
          <span>Categories</span>
        </a>
        <a href="{{ route('admin.products.index') }}" class="nav-item @if(request()->routeIs('admin.products.*')) active @endif">
          <i class="fas fa-boxes-stacked"></i>
          <span>Products</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="nav-item @if(request()->routeIs('admin.orders.*')) active @endif">
          <i class="fas fa-receipt"></i>
          <span>Orders</span>
        </a>
      </nav>
      
      <div class="user-profile">
        <div class="user-avatar">
          {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
        </div>
        <div class="user-info">
          <span class="user-name">{{ auth()->user()->name ?? 'Admin User' }}</span>
          <span class="user-role">Administrator</span>
        </div>
      </div>

      <form action="{{ route('admin.logout') }}" method="POST" class="logout">
        @csrf
        <button type="submit" class="nav-item logout-btn">
          <i class="fas fa-arrow-right-from-bracket"></i>
          <span>Logout</span>
        </button>
      </form>
    </aside>

    <main class="main">
      @if(session('status'))
        <div class="alert">{{ session('status') }}</div>
      @endif

    @yield('content')
    </main>

    @stack('scripts')
  </div>
</body>
</html>
