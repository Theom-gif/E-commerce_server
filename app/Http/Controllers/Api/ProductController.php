<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Resolve the product image to a full URL.
     */
    private function resolveImageUrl(?string $image): ?string
    {
        if (!$image) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $image)) {
            return $image;
        }

        return Storage::disk('public')->url($image);
    }

    /**
     * Apply resolved image URLs to a collection of products.
     */
    private function resolveImages($products): void
    {
        $products->transform(function ($product) {
            $product->image = $this->resolveImageUrl($product->image);
            return $product;
        });
    }

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
        $this->resolveImages($paginated->getCollection());

        return response()->json($paginated);
    }

    public function show($id)
    {
        $product = Product::with('category', 'reviews.user')->findOrFail($id);
        $product->image = $this->resolveImageUrl($product->image);

        return response()->json($product);
    }

    public function search($keyword)
    {
        $products = Product::with('category')
            ->where('name', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%")
            ->limit(12)
            ->get();

        $this->resolveImages($products);

        return response()->json($products);
    }
}
