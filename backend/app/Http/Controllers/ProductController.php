<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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

   
    public function store(Request $request)
    {
        //
    }

   
    public function show(string $id)
    {
        return Product::findOrFail($id);
    }

    
    public function update(Request $request, string $id)
    {
        //
    }

    
    public function destroy(string $id)
    {
        //
    }
}
