<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

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

    return $query->get();
}

   
    public function store(StoreProductRequest $request)   
    {
        $product = Product::create($request->validated());

        return response()->json($product, 201);
    }

   
    public function show(string $id)
    {
        return Product::findOrFail($id);
    }

    
    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
    
        return response()->json($product);
    }

    
    public function destroy(string $id)
{
    $product = Product::findOrFail($id);
    $product->is_active = false;
    $product->save();

    return response()->json(['message' => 'Ürün pasif hale getirildi.']);
}
}
