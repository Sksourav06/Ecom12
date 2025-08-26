<?php

namespace App\Http\Requests\Admin;

use App\Models\Brand;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BrandRequest extends FormRequest
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
            'name' => 'required',
            'url' => 'required|regex:/^[\pL\s\-]+$/u'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Brand Name is required',
            'url.required' => 'Brand URL is required',
        ];
    }
    public function prepareForValidation()
    {
        if ($this->route('brand')) {
            $this->merge([
                'id' => $this->route('brand'),
            ]);
        }
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $brandcount = Brand::where('url', $this->input('url'));
            if ($this->filled('id')) {
                $brandcount->where('id', '!=', $this->input('id'));
            }
            if ($brandcount->count() > 0) {
                $validator->error()->add('url', 'Brand already exists!');
            }
        });
    }


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
