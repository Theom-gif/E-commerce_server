<?php
// Fix old products: delete ones with local image paths, update image URLs for the rest

use App\Models\Product;

// Find and delete old products with local image paths (not http URLs)
$oldProducts = Product::where('image', 'NOT LIKE', 'http%')
    ->orWhereNull('image')
    ->get();

echo "Old products with local/broken images: " . $oldProducts->count() . PHP_EOL;

foreach ($oldProducts as $p) {
    echo "Deleting: [{$p->id}] {$p->name} (image: {$p->image})" . PHP_EOL;
    $p->delete();
}

echo "Done. Total products now: " . Product::count() . PHP_EOL;
