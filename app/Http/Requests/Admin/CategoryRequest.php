<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryRequest extends FormRequest
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
            'category_name' => 'required',
            'url' => 'required|regex:/^[\pL\s\-]+$/u'
        ];
    }

    public function messages()
    {
        return [
            'category_name.required' => 'Category Nmae is required',
            'url.required' => 'Category URL is required',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->route('category')) {
            $this->merge([
                'id' => $this->route('category'),
            ]);
        }
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $categoryQuery = Category::where('url', $this->input('url'));

            if ($this->filled('id')) {
                $categoryQuery->where('id', '!=', $this->input('id'));
            }

            if ($categoryQuery->count() > 0) {
                $validator->errors()->add('url', 'Category already exists!');
            }
        });
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            Redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
