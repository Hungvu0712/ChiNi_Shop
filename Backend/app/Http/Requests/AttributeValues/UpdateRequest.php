<?php

namespace App\Http\Requests\AttributeValues;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'value' => [
                'required',
                'string',
                'max:255',
                Rule::unique('attribute_values', 'value')->ignore($this->route('attribute_value')),
            ],
        ];
    }

    public function messages(){
        return [
            'value.required' => 'Không thể bỏ trống',
            'value.string' => 'Giá trị phải là một chuỗi ký tự',
            'value.max' => 'Giá trị không dc quá 255 ký tự',
            'value.unique' => 'Đã tồn tại trong hệ thống',
        ];
    }
}
