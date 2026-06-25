<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($search = $request->query('search')) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        $products = $query->latest()->paginate(12);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
            'author' => ['nullable', 'string', 'max:255'],
            'featured' => ['nullable', 'boolean'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:255'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'binding' => ['nullable', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
        ]);

        $data = [
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $this->uniqueProductSlug($validated['name']),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'] ?? 0,
            'image' => $this->processProductImage($request->file('image')),
            'author' => $validated['author'] ?? null,
            'featured' => $request->has('featured'),
            'subtitle' => $validated['subtitle'] ?? null,
            'isbn' => $validated['isbn'] ?? null,
            'pages' => $validated['pages'] ?? null,
            'binding' => $validated['binding'] ?? null,
            'publisher' => $validated['publisher'] ?? null,
        ];

        Product::create($data);

        return redirect()->route('admin.products.index')->with('status', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = new Product();
        if ($id) {
            $product = Product::query()->findOrFail($id);
        }
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::query()->findOrFail($id);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
            'author' => ['nullable', 'string', 'max:255'],
            'featured' => ['nullable', 'boolean'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:255'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'binding' => ['nullable', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
        ]);

        $product->category_id = $validated['category_id'];
        $product->name = $validated['name'];
        $product->slug = $this->uniqueProductSlug($validated['name'], $product->id);
        $product->description = $validated['description'] ?? null;
        $product->price = $validated['price'];
        $product->stock = $validated['stock'] ?? 0;
        $product->image = $this->processProductImage($request->file('image'), $product->image);
        $product->author = $validated['author'] ?? null;
        $product->featured = $request->has('featured');
        $product->subtitle = $validated['subtitle'] ?? null;
        $product->isbn = $validated['isbn'] ?? null;
        $product->pages = $validated['pages'] ?? null;
        $product->binding = $validated['binding'] ?? null;
        $product->publisher = $validated['publisher'] ?? null;

        $product->save();

        return redirect()->route('admin.products.index')->with('status', 'Product updated successfully.');
    }

    private function processProductImage(?object $file, ?string $existingPath = null, bool $store = true): ?string
    {
        if (!$file) {
            return $existingPath;
        }

        $basename = time().'_'.Str::random(12).'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
        $path = 'product_images/'.$basename;

        if ($store) {
            Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));
        }

        return $path;
    }

    private function uniqueProductSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $exists = Product::query()
            ->when($ignoreId, fn ($q, $id) => $q->where('id', '!=', $id))
            ->where('slug', $slug)
            ->exists();

        $suffix = 1;
        while ($exists) {
            $slug = $base.'-'.$suffix++;
            $exists = Product::query()
                ->when($ignoreId, fn ($q, $id) => $q->where('id', '!=', $id))
                ->where('slug', $slug)
                ->exists();
        }

        return $slug;
    }

    public function destroy($id)
    {
        $product = Product::query()->findOrFail($id);

        $this->deleteProductImage($product->image);

        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted successfully.');
    }

    private function deleteProductImage(?string $image): void
    {
        if (!$image) {
            return;
        }

        $path = ltrim($image, '/');

        if (!str_starts_with($path, 'product_images/')) {
            $path = 'product_images/'.basename($path);
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
