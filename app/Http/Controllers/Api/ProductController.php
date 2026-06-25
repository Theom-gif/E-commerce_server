<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Resolve the product image to a full URL.
     * - If already an absolute http(s) URL → return as-is
     * - If a local storage path → convert to storage URL
     * - Otherwise → return null (fallback handled by frontend)
     */
    private function resolveImageUrl(?string $image): ?string
    {
        if (!$image) {
            return null;
        }

        // Already an absolute URL (e.g. Unsplash, CDN, etc.)
        if (preg_match('/^https?:\/\//i', $image)) {
            return $image;
        }

        // Local storage path → convert to full public URL
        return Storage::disk('public')->url($image);
    }

    /**
     * Append resolved image_url to a product array.
     */
    private function withImageUrl(array $product): array
    {
        $product['image'] = $this->resolveImageUrl($product['image'] ?? null);
        return $product;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $paginated = $query->latest()->paginate(12);

        // Resolve image URLs in the paginated data
        $paginated->getCollection()->transform(function ($product) {
            $product->image = $this->resolveImageUrl($product->image);
            return $product;
        });

        return response()->json($paginated);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with('category', 'reviews.user')->findOrFail($id);
        $product->image = $this->resolveImageUrl($product->image);
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    public function search($keyword)
    {
        $products = Product::with('category')
            ->where('name', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%")
            ->limit(12)
            ->get();

        // Resolve image URLs
        $products->transform(function ($product) {
            $product->image = $this->resolveImageUrl($product->image);
            return $product;
        });

        return response()->json($products);
    }
}

