<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Category Detail - Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
  <div class="flex min-h-screen">
    <aside class="hidden md:flex md:flex-col w-64 bg-white border-r border-gray-200">
      <div class="px-6 py-5 flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm">A</div>
        <span class="text-xl font-semibold tracking-tight">Admin</span>
      </div>
      <nav class="flex-1 px-3 space-y-1">
        <a href="/admin/dashboard" class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600">
          <span class="flex items-center gap-3"><i class="fas fa-th-large w-5 text-center"></i> Dashboard</span>
        </a>
        <a href="/admin/categories" class="flex items-center justify-between px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700">
          <span class="flex items-center gap-3"><i class="fas fa-list w-5 text-center"></i> Categories</span>
        </a>
        <a href="/admin/products" class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600">
          <span class="flex items-center gap-3"><i class="fas fa-box w-5 text-center"></i> Products</span>
        </a>
        <a href="/admin/orders" class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600">
          <span class="flex items-center gap-3"><i class="fas fa-shopping-bag w-5 text-center"></i> Orders</span>
        </a>
        <a href="#" class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600">
          <span class="flex items-center gap-3"><i class="fas fa-gear w-5 text-center"></i> Settings</span>
        </a>
        <a href="/admin/logout" class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600">
          <span class="flex items-center gap-3"><i class="fas fa-arrow-right-from-bracket w-5 text-center"></i> Logout</span>
        </a>
      </nav>
    </aside>

    <main class="flex-1 w-full">
      <div class="max-w-3xl mx-auto px-8 py-8">
        <div class="flex items-center justify-between gap-4 flex-wrap">
          <div>
            <h1 class="text-3xl font-bold text-gray-900" style="margin:0;font-size:26px">Category</h1>
            <p class="text-gray-600">Category details.</p>
          </div>
          <div class="flex items-center gap-2">
            <a href="/admin/categories" class="px-4 py-2 rounded-lg text-sm border border-gray-300 text-gray-700 hover:bg-gray-50">Back</a>
            <a href="/admin/categories/{{ $category->id }}/edit" class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm hover:bg-gray-800">Edit</a>
          </div>
        </div>

        <div class="mt-6 bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-4">
          <div>
            <div class="text-xs font-medium text-gray-500">ID</div>
            <div class="text-sm text-gray-900">{{ $category->id }}</div>
          </div>
          <div>
            <div class="text-xs font-medium text-gray-500">Name</div>
            <div class="text-sm text-gray-900">{{ $category->name }}</div>
          </div>
          <div>
            <div class="text-xs font-medium text-gray-500">Slug</div>
            <div class="text-sm text-gray-900">{{ $category->slug }}</div>
          </div>
          <div>
            <div class="text-xs font-medium text-gray-500">Created</div>
            <div class="text-sm text-gray-900">{{ $category->created_at?->format('M d, Y H:i') }}</div>
          </div>
          <div>
            <div class="text-xs font-medium text-gray-500">Updated</div>
            <div class="text-sm text-gray-900">{{ $category->updated_at?->format('M d, Y H:i') }}</div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
