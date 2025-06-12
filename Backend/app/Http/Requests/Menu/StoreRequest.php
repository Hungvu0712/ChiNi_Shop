<?php

namespace App\Http\Requests\Menu;

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
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'prant_id' => 'nullable|exists:menus,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên menu là bắt buộc.',
            'name.string' => 'Tên menu phải là một chuỗi ký tự.',
            'name.max' => 'Tên menu không được vượt quá 255 ký tự.',

            'url.required' => 'Đường dẫn là bắt buộc.',
            'url.string' => 'Đường dẫn phải là một chuỗi ký tự.',
            'url.max' => 'Đường dẫn không được vượt quá 255 ký tự.',

            'prant_id.exists' => 'Menu cha được chọn không tồn tại.',
        ];
    }
}
