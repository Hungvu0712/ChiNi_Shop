<?php

namespace App\Http\Requests\Banner;

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
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('banners')->ignore($this->route('banner')),
            ],
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'required|string|max:255',
            'content' => 'nullable|string',
            'active' => 'nullable|in:true,false,1,0,on,off',
        ];
    }

    public function message(){
        return [
            'title.required' => 'Tiêu đề là bắt buộc',
            'title.string' => 'Tiêu đề phải là chuỗi',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'title.unique' => 'Tiêu đề đã tồn tại',

            'banner_image.image' => 'Hình ảnh phải là file ảnh',
            'banner_image.mimes' => 'Hình ảnh phải là file ảnh',
            'banner_image.max' => 'Hình ảnh không được vượt quá 2MB',

            'link.required' => 'Link là bắt buộc',
            'link.string' => 'Link phải là chuỗi',
            'link.max' => 'Link không được vượt quá 255 ký tự',
            
            'content.string' => 'Nội dung phải là chuỗi',

            'active.in' => 'Trạng thái không hợp lệ',
        ];
    }
}
