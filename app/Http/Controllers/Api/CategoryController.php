<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::withCount('products')->latest()->get());
    }

    public function show(Category $category)
    {
        return response()->json($category->load('products'));
    }
}
