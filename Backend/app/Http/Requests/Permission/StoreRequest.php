<?php

namespace App\Http\Requests\Permission;

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
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
        ];
    }

    public function messages(){
        return [
            'name.required' => '*Không được bỏ trống',
            'name.string' => '*Tên phải là 1 chuỗi',
            'name.max' => '*Tên không được quá 255 ký tự',
            'name.unique' => '*Đã có trong CSDL',
        ];
    }
}
