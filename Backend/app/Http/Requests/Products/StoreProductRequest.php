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
            'weight' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'tags' => 'required|string',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120',
            'removed_attachments' => 'nullable|string',
            'active' => 'nullable|in:true,false,1,0,on,off',
        ];
    }

    public function messages(): array
{
    return [
        'name.required' => 'Tên sản phẩm không được để trống.',
        'name.string' => 'Tên sản phẩm phải là chuỗi.',
        'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',

        'sku.required' => 'Mã SKU không được để trống.',
        'sku.string' => 'Mã SKU phải là chuỗi.',
        'sku.max' => 'Mã SKU không được vượt quá 100 ký tự.',
        'sku.unique' => 'Mã SKU đã tồn tại trong hệ thống.',

        'price.required' => 'Giá sản phẩm không được để trống.',
        'price.numeric' => 'Giá sản phẩm phải là số.',
        'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 0.',

        'quantity.required' => 'Số lượng sản phẩm không được để trống.',
        'quantity.integer' => 'Số lượng phải là số nguyên.',
        'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 0.',

        'quantity_warning.integer' => 'Số lượng cảnh báo phải là số nguyên.',
        'quantity_warning.min' => 'Số lượng cảnh báo phải lớn hơn hoặc bằng 0.',

        'weight.numeric' => 'Khối lượng phải là số.',
        'weight.min' => 'Khối lượng phải lớn hơn hoặc bằng 0.',

        'description.string' => 'Mô tả phải là chuỗi.',

        'category_id.required' => 'Danh mục không được để trống.',
        'category_id.exists' => 'Danh mục đã chọn không hợp lệ.',

        'brand_id.exists' => 'Thương hiệu đã chọn không hợp lệ.',

        'tags.string' => 'Từ khóa phải là chuỗi.',

        'product_image.image' => 'Ảnh sản phẩm phải là hình ảnh.',
        'product_image.mimes' => 'Ảnh sản phẩm phải có định dạng jpeg, png, jpg, gif.',
        'product_image.max' => 'Ảnh sản phẩm không được vượt quá 2MB.',

        'attachments.array' => 'Danh sách tệp đính kèm không hợp lệ.',
        'attachments.*.file' => 'Từng tệp đính kèm phải là file.',
        'attachments.*.max' => 'Từng tệp đính kèm không được vượt quá 5MB.',

        'removed_attachments.string' => 'Dữ liệu tệp đính kèm đã xoá không hợp lệ.',

        'active.in' => 'Trạng thái hoạt động không hợp lệ.',
    ];
}

}
