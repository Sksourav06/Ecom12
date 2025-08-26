<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
            'type' => 'required|string|max:255',
            'link' => 'nullable|url|max:255',
            'title' => 'required|string|max:255',
            'alt' => 'nullable|string|max:255',
            'sort' => 'nullable|integer|min:0',
            'status' => 'nullable|in:0,1'
        ];
        if ($this->inMethod('post') || $this->hasFile('image')) {
            $rule['image'] = 'required|image:jpg,jpeg,png,gif|max:2048';//Max 2MB
        }
        return $rule;
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Please select the banner type',
            'title.required' => 'Banner title is required',
            'image.required' => 'please upload a banner image',
            'image.image' => 'Uploaded file must be an image',
            'image.mimes' => 'Allowed image types are jpg,jpeg,png,gif',
            'image.max' => 'image size must be less than 2mb',
        ];
    }
}
