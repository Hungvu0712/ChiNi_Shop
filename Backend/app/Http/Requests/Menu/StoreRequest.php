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
            'parent_id' => 'nullable|exists:menus,id',
            'order_index' => 'required|integer|min:1|unique:menus,order_index',
        ];
    }

    public function messages(): array
    {
        return [
            // name
            'name.required' => 'Tên menu là bắt buộc.',
            'name.string' => 'Tên menu phải là một chuỗi ký tự.',
            'name.max' => 'Tên menu không được vượt quá 255 ký tự.',

            // url
            'url.required' => 'Đường dẫn là bắt buộc.',
            'url.string' => 'Đường dẫn phải là một chuỗi ký tự.',
            'url.max' => 'Đường dẫn không được vượt quá 255 ký tự.',

            // parent_id
            'parent_id.exists' => 'Menu cha được chọn không tồn tại.',

            // order_index
            'order_index.required' => 'Thứ tự hiển thị là bắt buộc.',
            'order_index.integer' => 'Thứ tự hiển thị phải là số nguyên.',
            'order_index.min' => 'Thứ tự hiển thị phải lớn hơn hoặc bằng 1.',
            'order_index.unique' => 'Thứ tự hiển thị này đã được sử dụng. Vui lòng chọn giá trị khác.',
        ];
    }
}
