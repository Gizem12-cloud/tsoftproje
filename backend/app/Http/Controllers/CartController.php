<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Http\Resources\CartResource;


class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::with('items.product')->firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        return new CartResource($cart);
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'integer|min:1',
    ]);

    $product = Product::findOrFail($validated['product_id']);

    if (!$product->is_active) {
        return response()->json(['message' => 'Bu ürün artık satışta değil.'], 422);
    }

    $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

    $item = $cart->items()->where('product_id', $product->id)->first();

    if ($item) {
        $item->quantity += $validated['quantity'] ?? 1;
        $item->save();
    } else {
        $item = $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $validated['quantity'] ?? 1,
        ]);
    }

    return (new CartResource($cart->load('items.product')))->response()->setStatusCode(201);
}


public function destroy(Request $request, string $productId)
{
    $cart = Cart::where('user_id', $request->user()->id)->first();

    if (!$cart) {
        return response()->json(['message' => 'Sepet bulunamadı.'], 404);
    }

    $deleted = $cart->items()->where('product_id', $productId)->delete();

    if (!$deleted) {
        return response()->json(['message' => 'Bu ürün sepetinde yok.'], 404);
    }

    return new CartResource($cart->load('items.product'));
}

public function decrease(Request $request, string $productId)
{
    $cart = Cart::where('user_id', $request->user()->id)->first();

    if (!$cart) {
        return response()->json(['message' => 'Sepet bulunamadı.'], 404);
    }

    $item = $cart->items()->where('product_id', $productId)->first();

    if (!$item) {
        return response()->json(['message' => 'Bu ürün sepetinde yok.'], 404);
    }

    if ($item->quantity <= 1) {
        $item->delete();
    } else {
        $item->quantity -= 1;
        $item->save();
    }

    return new CartResource($cart->load('items.product'));
}
}