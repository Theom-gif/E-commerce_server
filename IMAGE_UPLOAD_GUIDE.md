# Image Upload Guide: Fixing Broken Images

If images uploaded from the Admin panel are successfully saving to the database but appear as "broken" links on the frontend (or even in the Admin panel), it is almost always due to Laravel's file storage architecture.

## Why this happens

When you upload a file using Laravel's `public` disk (e.g., `$request->file('image')->store('product_images', 'public')`), Laravel saves the physical file into the `storage/app/public` directory.

However, the web server (like Nginx, Apache, or `php artisan serve`) only serves files directly from the `public/` directory. By default, the web server **cannot** look inside the `storage/` directory for security reasons.

Because of this, an image path like `storage/product_images/example.jpg` returns a `404 Not Found` error.

## How to fix it (The Solution)

You must create a "symbolic link" (a shortcut) that links the `public/storage` directory to the hidden `storage/app/public` directory.

### Step 1: Run the storage link command
Open your terminal in the root of the backend (`E-commerce_server`) and run:

```bash
php artisan storage:link
```

This single command tells your operating system to create a mirror link. Anything placed in `storage/app/public` will instantly become accessible from `http://your-app-url/storage/`.

### Step 2: Ensure your API returns the correct URL
When sending the product data to the frontend, you must ensure you are sending the **full, absolute URL**, not just the database string.

If your database saves: `product_images/iphone-18.jpg`
The frontend needs: `http://localhost:8000/storage/product_images/iphone-18.jpg`

To fix this, update your backend API Controller (`app/Http/Controllers/Api/ProductController.php`) to use Laravel's `asset()` helper:

```php
// Bad: Frontend won't know the base URL
'image' => $product->image,

// Good: Automatically generates http://localhost:8000/storage/...
'image' => $product->image ? asset('storage/' . $product->image) : null,
```

### Step 3: Check `.env` APP_URL
Ensure the `APP_URL` variable in your `.env` file is set correctly to your backend's running URL (e.g., `APP_URL=http://localhost:8000`). The `asset()` helper uses this value to generate the first part of the link.

---
**Summary Checklist:**
1. Did you run `php artisan storage:link`?
2. Does `public/storage` exist in your folder structure now?
3. Is your API returning `asset('storage/...')` instead of just the raw path?
4. Is your `APP_URL` correct in `.env`?
