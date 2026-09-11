<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Product;


class CategoryController extends Controller
{
    
    public function index()
{
    $categories = Category::whereNull('parent_id')
        ->where('is_active', true)
        ->with(['children' => function ($query) {
            $query->where('is_active', true);
        }])
        ->get();

    return CategoryResource::collection($categories);
}

    public function store(StoreCategoryRequest $request)
{
    $category = Category::create($request->validated());

    return response()->json($category, 201);
}

    
public function show(string $id)
{
    return new CategoryResource(Category::findOrFail($id));
}

    public function update(UpdateCategoryRequest $request, string $id)
    {
    $category = Category::findOrFail($id);
    $category->update($request->validated());

    return response()->json($category);
    }



    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
    
        $categoryIds = array_merge([$category->id], $category->descendantIds());
    
        $hasActiveProducts = Product::whereIn('category_id', $categoryIds)
            ->where('is_active', true)
            ->exists();
    
        if ($hasActiveProducts) {
            return response()->json([
                'message' => 'Bu kategoride veya alt kategorilerinde aktif ürünler var, önce onları kaldırmalısın.',
            ], 422);
        }
    
        $category->is_active = false;
        $category->save();
    
        return response()->json(['message' => 'Kategori pasif hale getirildi.']);
    }

    public function restore(string $id)
    {
    $category = Category::findOrFail($id);
    $category->is_active = true;
    $category->save();

    return response()->json(['message' => 'Kategori tekrar aktif hale getirildi.']);
    }
}
