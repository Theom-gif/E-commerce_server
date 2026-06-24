<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin')</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="brand">Admin</div>
      <nav class="nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-item @if(request()->routeIs('admin.dashboard')) active @endif">
          <i class="fas fa-th-large"></i>
          <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="nav-item @if(request()->routeIs('admin.categories.*')) active @endif">
          <i class="fas fa-list"></i>
          <span>Categories</span>
        </a>
        <a href="{{ route('admin.products.index') }}" class="nav-item @if(request()->routeIs('admin.products.*')) active @endif">
          <i class="fas fa-box"></i>
          <span>Products</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="nav-item @if(request()->routeIs('admin.orders.*')) active @endif">
          <i class="fas fa-shopping-bag"></i>
          <span>Orders</span>
        </a>
      </nav>
      <form action="{{ route('admin.logout') }}" method="POST" class="logout">
        @csrf
        <button type="submit" class="nav-item">
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
  </div>
</body>
</html>
