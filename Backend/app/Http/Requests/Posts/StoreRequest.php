<?php

namespace App\Http\Requests\Posts;

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

    public function rules(): array
    {
        return [
            'post_category_id' => 'required|exists:post_categories,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ];
    }

    public function messages(): array
    {
        return [
            'post_category_id.required' => 'Vui lòng chọn danh mục bài viết.',
            'post_category_id.exists' => 'Danh mục không tồn tại.',
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'excerpt.required' => 'Vui lòng nhập mô tả ngắn.',
            'content.required' => 'Vui lòng nhập nội dung.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}
