<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($productId)
{
    return Review::where('product_id', $productId)->with('user')->latest()->get();
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
    public function store(Request $request, $productId)
{
    $request->validate(['rating' => 'required|integer|min:1|max:5', 'comment' => 'nullable|string']);

    $review = $request->user()->reviews()->create([
        'product_id' => $productId,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return response()->json($review);
}


}
