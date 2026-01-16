<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;


class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        $cart = Cart::firstOrCreate([
            'user_id' => $user->id,
        ]);

        $product = Product::findOrFail($request->product_id);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->increment('qty', $request->qty);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'qty' => $request->qty,
                'price_at_time' => $product->price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
        ], 201);
    }

    public function index(Request $request)
{
    $user = $request->user();

    $cart = Cart::with('items.product')
        ->where('user_id', $user->id)
        ->first();

    if (!$cart) {
        return response()->json([
            'items' => [],
            'total' => 0,
        ]);
    }

    $total = $cart->items->sum(function ($item) {
        return $item->qty * $item->price_at_time;
    });

    return response()->json([
        'items' => $cart->items,
        'total' => $total,
    ]);
}
public function update(Request $request, Product $product)
{
    $request->validate([
        'qty' => 'required|integer|min:1',
    ]);

    $cart = Cart::where('user_id', $request->user()->id)->firstOrFail();

    $item = CartItem::where('cart_id', $cart->id)
        ->where('product_id', $product->id)
        ->firstOrFail();

    $item->update([
        'qty' => $request->qty,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Cart item updated',
    ]);
}
public function delete(Request $request, Product $product)
{
    $cart = Cart::where('user_id', $request->user()->id)->firstOrFail();

    CartItem::where('cart_id', $cart->id)
        ->where('product_id', $product->id)
        ->delete();

    return response()->json([
        'success' => true,
        'message' => 'Item removed from cart',
    ]);
}

public function checkout(Request $request)
{
    $user = $request->user();

    $cart = Cart::with('items.product')
        ->where('user_id', $user->id)
        ->first();

    if (!$cart || $cart->items->isEmpty()) {
        return response()->json([
            'message' => 'Cart is empty'
        ], 400);
    }

    DB::beginTransaction();

    try {
        foreach ($cart->items as $item) {

            // STOCK CHECK
            if ($item->product->stock < $item->qty) {
                throw new \Exception(
                    'Insufficient stock for product: ' . $item->product->name
                );
            }

            //  DEDUCT STOCK
            $item->product->decrement('stock', $item->qty);
        }

        // 🧹 CLEAR CART
        $cart->items()->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Checkout completed successfully'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 400);
    }
}

}
