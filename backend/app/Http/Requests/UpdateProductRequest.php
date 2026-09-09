<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
{
    return true;
}

public function rules(): array
{
    return [
        'name' => 'sometimes|required|string|max:255',
        'slug' => 'sometimes|required|string|max:255|unique:products,slug,' . $this->route('id'),
        'description' => 'nullable|string',
        'price' => 'sometimes|required|numeric|min:0',
        'stock' => 'sometimes|required|integer|min:0',
        'category_id' => 'sometimes|required|exists:categories,id',
        'brand' => 'nullable|string|max:255',
    ];
}
}
