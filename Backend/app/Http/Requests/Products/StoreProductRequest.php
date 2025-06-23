<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'quantity_warning' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'tags' => 'nullable|string',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120',
            'removed_attachments' => 'nullable|string',
            'active' => 'nullable|in:true,false,1,0,on,off',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'sku.required' => 'Vui lòng nhập mã SKU.',
            'sku.unique' => 'SKU đã tồn tại trong hệ thống.',
            'sku.max' => 'SKU không được vượt quá 100 ký tự.',
            'price.required' => 'Vui lòng nhập giá sản phẩm.',
            'price.numeric' => 'Giá phải là số hợp lệ.',
            'price.min' => 'Giá phải lớn hơn hoặc bằng 0.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng không được âm.',
            'quantity_warning.integer' => 'Cảnh báo tồn kho phải là số nguyên.',
            'quantity_warning.min' => 'Cảnh báo tồn kho không được âm.',
            'weight.numeric' => 'Trọng lượng phải là số.',
            'weight.min' => 'Trọng lượng không được âm.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'brand_id.exists' => 'Thương hiệu không hợp lệ.',
            'product_image.image' => 'Ảnh chính phải là tệp hình ảnh.',
            'product_image.mimes' => 'Ảnh chính chỉ chấp nhận các định dạng: jpeg, png, jpg, gif.',
            'product_image.max' => 'Ảnh chính không được vượt quá 2MB.',
            'images.*.image' => 'Mỗi ảnh đính kèm phải là hình ảnh.',
            'images.*.mimes' => 'Ảnh đính kèm chỉ chấp nhận jpeg, png, jpg, gif.',
            'images.*.max' => 'Mỗi ảnh đính kèm không được vượt quá 2MB.',
            'attachments.*.file' => 'Tệp đính kèm không hợp lệ.',
            'attachments.*.max' => 'Tệp đính kèm không được vượt quá 5MB.',
            'active.in' => 'Trạng thái kích hoạt không hợp lệ.',
        ];
    }
}
