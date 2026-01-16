<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function add_to_cart_merges_duplicate_items_correctly()
    {
        $user = User::factory()->create();

        $product = Product::factory()->create([
            'stock' => 10,
            'price' => 100,
        ]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/cart/items', [
                'product_id' => $product->id,
                'qty' => 2,
            ]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/cart/items', [
                'product_id' => $product->id,
                'qty' => 3,
            ]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'qty' => 5, // 2 + 3
        ]);
    }

    /** @test */
    public function checkout_fails_when_stock_is_insufficient_and_does_not_deduct_stock_or_clear_cart()
    {
        $user = User::factory()->create();

        $product = Product::factory()->create([
            'stock' => 1,
            'price' => 100,
        ]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/cart/items', [
                'product_id' => $product->id,
                'qty' => 2, // more than stock
            ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/cart/checkout');

        $response->assertStatus(400);

        // Stock should NOT change
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 1,
        ]);

        // Cart item should STILL exist
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'qty' => 2,
        ]);
    }
}
