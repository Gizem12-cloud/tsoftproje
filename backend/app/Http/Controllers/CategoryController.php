<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::all();
    }

    public function store(StoreCategoryRequest $request)
{
    $category = Category::create($request->validated());

    return response()->json($category, 201);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    $category->is_active = false;
    $category->save();

    return response()->json(['message' => 'Kategori pasif hale getirildi.']);
    }
}
