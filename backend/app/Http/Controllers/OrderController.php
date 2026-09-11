<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Sepetiniz boş.'], 422);
        }

        $order = DB::transaction(function () use ($cart, $user) {
            $totalAmount = $cart->items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
        
            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'status' => 'pending',
                'total_amount' => $totalAmount,
            ]);
        
            foreach ($cart->items as $item) {
                $product = $item->product;
            
                if (!$product->is_active || $product->stock < $item->quantity) {
                    throw new \Exception("Yetersiz stok: {$product->name}");
                }
            
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $product->price,
                    'quantity' => $item->quantity,
                ]);
            
                $product->decrement('stock', $item->quantity);
            }
            $cart->items()->delete();
            return $order;
        });

        return response()->json($order->load('items'), 201);
    }
}