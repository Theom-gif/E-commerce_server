<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class StoreAssistantService
{
    /**
     * Build a structured response for a natural-language shopping request.
     */
    public function respond(string $message, ?User $user = null, int $limit = 5): array
    {
        $normalized = $this->normalize($message);
        $intent = $this->detectIntent($normalized);
        $terms = $this->extractTerms($normalized);

        $categories = $this->findCategories($normalized, $terms, $limit);
        $products = $this->findProducts($normalized, $terms, $categories->pluck('id')->all(), $limit);

        return [
            'intent' => $intent,
            'message' => $this->buildMessage($intent, $message, $products, $categories, $user),
            'categories' => $categories->map(fn (Category $category) => $this->formatCategory($category))->values(),
            'products' => $products->map(fn (Product $product) => $this->formatProduct($product))->values(),
            'actions' => $this->buildActions($intent, $user),
            'query' => [
                'message' => $message,
                'terms' => $terms,
            ],
        ];
    }

    private function normalize(string $message): string
    {
        $message = Str::lower($message);
        $message = preg_replace('/[^a-z0-9\s]+/i', ' ', $message) ?? $message;

        return trim(preg_replace('/\s+/', ' ', $message) ?? $message);
    }

    private function detectIntent(string $message): string
    {
        $scores = [
            'checkout' => 0,
            'cart' => 0,
            'category' => 0,
            'product' => 0,
            'general' => 0,
        ];

        foreach ([
            'checkout' => ['checkout', 'pay', 'purchase', 'buy now', 'place order', 'complete order'],
            'cart' => ['cart', 'basket', 'bag'],
            'category' => ['category', 'categories', 'browse', 'collection', 'type'],
            'product' => ['product', 'products', 'item', 'items', 'find', 'search', 'show me', 'look for'],
        ] as $intent => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($message, $keyword)) {
                    $scores[$intent] += strlen($keyword);
                }
            }
        }

        arsort($scores);

        return array_key_first(array_filter($scores, fn (int $score) => $score > 0)) ?? 'general';
    }

    private function extractTerms(string $message): array
    {
        $stopWords = [
            'a', 'an', 'and', 'any', 'for', 'get', 'help', 'i', 'in', 'is', 'it', 'me',
            'of', 'on', 'please', 'show', 'something', 'the', 'to', 'want', 'with', 'you',
            'my', 'need', 'look', 'find', 'browse',
        ];

        $terms = array_values(array_filter(
            explode(' ', $message),
            fn (string $term) => strlen($term) > 1 && !in_array($term, $stopWords, true)
        ));

        return array_values(array_unique($terms));
    }

    /**
     * @param array<int, string> $terms
     * @return Collection<int, Category>
     */
    private function findCategories(string $message, array $terms, int $limit): Collection
    {
        $keywords = array_values(array_unique(array_filter(array_merge([$message], $terms))));

        $query = Category::query()->withCount('products');

        if ($keywords !== []) {
            $query->where(function ($builder) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $builder->orWhere('name', 'like', "%{$keyword}%")
                        ->orWhere('slug', 'like', "%{$keyword}%");
                }
            });
        }

        $categories = $query->latest()->limit($limit)->get();

        if ($categories->isEmpty() && $message !== '') {
            $categories = Category::query()
                ->withCount('products')
                ->latest()
                ->limit($limit)
                ->get();
        }

        return $categories;
    }

    /**
     * @param array<int, string> $terms
     * @param array<int, int> $categoryIds
     * @return Collection<int, Product>
     */
    private function findProducts(string $message, array $terms, array $categoryIds, int $limit): Collection
    {
        $keywords = array_values(array_unique(array_filter(array_merge([$message], $terms))));

        $query = Product::query()->with('category');

        $query->where(function ($builder) use ($keywords, $categoryIds) {
            foreach ($keywords as $keyword) {
                $builder->orWhere('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            }

            if ($categoryIds !== []) {
                $builder->orWhereIn('category_id', $categoryIds);
            }
        });

        $products = $query->latest()->limit($limit)->get();

        if ($products->isEmpty() && $categoryIds !== []) {
            $products = Product::query()
                ->with('category')
                ->whereIn('category_id', $categoryIds)
                ->latest()
                ->limit($limit)
                ->get();
        }

        return $products;
    }

    private function formatCategory(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'product_count' => $category->products_count ?? $category->products()->count(),
            'url' => "/api/categories/{$category->id}",
        ];
    }

    private function formatProduct(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => (float) $product->price,
            'stock' => (int) $product->stock,
            'image' => $product->image,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug,
            ] : null,
            'url' => "/api/products/{$product->id}",
        ];
    }

    private function buildMessage(string $intent, string $message, Collection $products, Collection $categories, ?User $user): string
    {
        $productCount = $products->count();
        $categoryCount = $categories->count();

        return match ($intent) {
            'checkout' => $this->buildCheckoutMessage($user),
            'cart' => 'I can help with your cart. Open `/api/cart` to review items, then use `/api/checkout` to place the order.',
            'category' => $categoryCount > 0
                ? "I found {$categoryCount} category match" . ($categoryCount === 1 ? '' : 'es') . " and {$productCount} product" . ($productCount === 1 ? '' : 's') . '.'
                : 'I could not find a matching category, but I can still search products if you want to narrow the request.',
            'product' => $productCount > 0
                ? "I found {$productCount} product" . ($productCount === 1 ? '' : 's') . " related to \"{$message}\"."
                : 'I could not find an exact product match. Try a simpler product name, brand, or category.',
            default => $productCount > 0 || $categoryCount > 0
                ? 'Here are the best store matches I found for your request.'
                : 'I can help you find products, browse categories, review the cart, or explain checkout. Try asking for a product name or category.',
        };
    }

    private function buildCheckoutMessage(?User $user): string
    {
        if (! $user) {
            return 'Checkout requires sign-in. Log in, add items to your cart, then POST to `/api/checkout` to place the order.';
        }

        $cartItems = $user->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return 'Your cart is empty right now. Add items first, then POST to `/api/checkout` to place the order.';
        }

        $total = $cartItems->sum(fn ($item) => (float) $item->product->price * (int) $item->quantity);

        return 'You have ' . $cartItems->count() . ' item' . ($cartItems->count() === 1 ? '' : 's') . ' in the cart, totaling $' . number_format($total, 2) . '.';
    }

    private function buildActions(string $intent, ?User $user): array
    {
        $actions = [
            [
                'label' => 'Browse products',
                'url' => '/api/products',
            ],
            [
                'label' => 'Browse categories',
                'url' => '/api/categories',
            ],
        ];

        if ($intent === 'checkout' || $intent === 'cart') {
            $actions[] = [
                'label' => 'View cart',
                'url' => '/api/cart',
                'requires_auth' => true,
            ];
            $actions[] = [
                'label' => 'Checkout',
                'url' => '/api/checkout',
                'requires_auth' => true,
            ];
        }

        if ($user) {
            $actions[] = [
                'label' => 'My orders',
                'url' => '/api/orders',
                'requires_auth' => true,
            ];
        }

        return $actions;
    }
}
