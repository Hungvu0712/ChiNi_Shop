<?php

namespace App\Http\Requests\Brands;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:brands,name'],
            'brand_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    public function messages(){
        return [
            'name.required' => '*Không được bỏ trống',
            'name.string' => '*Không phải ký tự',
            'name.max' => '*Tối đa 255 ký tự',
            'name.unique' => '*Đã tồn tại trong CSDL',

            'brand_image.image' => '*Phải là file hình',
            'brand_image.mimes' => '*Phải là file jpeg,png,jpg,gif',
            'brand_image.max' => '*Tối đa 2MB',
        ];
    }
}
