<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->wishlists()->with('product.category')->latest()->get());
    }

    public function store(Request $request, $productId)
    {
        $request->user()->wishlists()->firstOrCreate(['product_id' => $productId]);

        return response()->json(['message' => 'Added to wishlist']);
    }

    public function destroy(Request $request, $productId)
    {
        $request->user()->wishlists()->where('product_id', $productId)->delete();

        return response()->json(['message' => 'Removed from wishlist']);
    }
}
