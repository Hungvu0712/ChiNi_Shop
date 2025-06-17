<?php

namespace App\Http\Requests\Attributes;

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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('attributes')->ignore($this->route('attribute')),
            ],
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Vui lòng nhập tên thuộc tính',
            'name.string' => 'Tên thuộc tính phải là 1 chuỗi',
            'name.max' => 'Tên thuộc tính không được vượt quá 255 ký tự',
            'name.unique' => 'Tên thuộc tính đã tồn tại',
        ];
    }
}
