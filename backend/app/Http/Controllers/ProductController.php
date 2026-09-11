<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\CartItem;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
    
        if ($request->has('category')) {
            $query->where('category_id', $request->query('category'));
        }
    
        if ($request->has('search')) {
            $query->where('name', 'ilike', '%' . $request->query('search') . '%');
        }
    
        if ($request->has('brand')) {
            $query->where('brand', $request->query('brand'));
        }
    
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->query('min_price'));
        }
    
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->query('max_price'));
        }
        $query->where('is_active', true);
        $query->orderBy('id');
        return ProductResource::collection($query->paginate(15));
    }




   
    public function store(StoreProductRequest $request)   
    {
        $product = Product::create($request->validated());

        return (new ProductResource($product))->response()->setStatusCode(201);
    }

   
    public function show(string $id)
    {
        return new ProductResource(Product::findOrFail($id));

    }

    
    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
    
        return new ProductResource($product);
    }

    
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = false;
        $product->save();
    
        CartItem::where('product_id', $product->id)->delete();
    
        return response()->json(['message' => 'Ürün pasif hale getirildi.']);
    }


    public function restore(string $id)
    {
    $product = Product::findOrFail($id);
    $product->is_active = true;
    $product->save();

    return response()->json(['message' => 'Ürün tekrar aktif hale getirildi.']);
    }
}
