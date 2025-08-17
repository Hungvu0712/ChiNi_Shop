<?php

namespace App\Http\Requests\Permission;

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
                Rule::unique('permissions', 'name')->ignore($this->route('permission')),
            ],
        ];
    }

    public function messages(){
        return [
            'name.required' => '*Không được bỏ trống',
            'name.string' => '*Phải là một chuỗi ký tự',
            'name.max' => '*Không được quá 255 ký tự',
            'name.unique' => '*Đã có trong CSDL',
        ];
    }
}
