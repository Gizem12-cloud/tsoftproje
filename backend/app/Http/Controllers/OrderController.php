<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{


    public function index(Request $request)
{
    $orders = Order::where('user_id', $request->user()->id)
        ->with('items')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return OrderResource::collection($orders);
}


    public function store(Request $request)
    {
        $user = $request->user();
        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();
    
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Sepetiniz boş.'], 422);
        }
    
        try {
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
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    
        return (new OrderResource($order->load('items')))->response()->setStatusCode(201);
    }
}