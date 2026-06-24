<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreAssistantTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_matching_products_and_categories_for_a_search_request(): void
    {
        $category = Category::create([
            'name' => 'Shoes',
            'slug' => 'shoes',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Running Shoes',
            'slug' => 'running-shoes',
            'description' => 'Lightweight shoes for daily training.',
            'price' => 79.99,
            'stock' => 12,
            'image' => null,
        ]);

        $response = $this->postJson('/api/assistant', [
            'message' => 'Show me shoes',
        ]);

        $response->assertOk()
            ->assertJsonPath('intent', 'product')
            ->assertJsonPath('categories.0.id', $category->id)
            ->assertJsonPath('products.0.id', $product->id)
            ->assertJsonPath('actions.0.url', '/api/products');
    }

    public function test_it_uses_cart_context_for_checkout_requests(): void
    {
        $category = Category::create([
            'name' => 'Accessories',
            'slug' => 'accessories',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Leather Wallet',
            'slug' => 'leather-wallet',
            'description' => 'A simple leather wallet.',
            'price' => 45.00,
            'stock' => 8,
            'image' => null,
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        Sanctum::actingAs($user);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->postJson('/api/assistant', [
            'message' => 'I want to checkout now',
        ]);

        $response->assertOk()
            ->assertJsonPath('intent', 'checkout')
            ->assertJsonPath('actions.2.url', '/api/cart')
            ->assertJsonPath('actions.3.url', '/api/checkout');
    }
}
