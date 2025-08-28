<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'filter_name' => 'required|string|max:255',
            'filter_column' => 'required|string|max:255',
            'sort' => 'nullable|integer|min:0',
            'status' => 'required|boolean',
            'category_ids' => 'required|array',
            'category_ids.*' => 'integer|exists:categories,id',
        ];
    }
}
