<?php

namespace App\Http\Requests\Role;

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
                Rule::unique('roles', 'name')->ignore($this->route('role')),
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
