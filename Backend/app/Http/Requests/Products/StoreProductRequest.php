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
            'images' => 'required|array',
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
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.string' => 'Tên sản phẩm phải là chuỗi.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',

            'sku.required' => 'Mã SKU là bắt buộc.',
            'sku.string' => 'Mã SKU phải là chuỗi.',
            'sku.max' => 'Mã SKU không được vượt quá 100 ký tự.',
            'sku.unique' => 'Mã SKU đã tồn tại.',

            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'price.min' => 'Giá sản phẩm không được nhỏ hơn 0.',

            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng không được nhỏ hơn 0.',

            'quantity_warning.integer' => 'Ngưỡng cảnh báo phải là số nguyên.',
            'quantity_warning.min' => 'Ngưỡng cảnh báo không được nhỏ hơn 0.',

            'weight.required' => 'Khối lượng là bắt buộc.',
            'weight.numeric' => 'Khối lượng phải là số.',
            'weight.min' => 'Khối lượng không được nhỏ hơn 0.',

            'description.string' => 'Mô tả phải là chuỗi.',

            'category_id.required' => 'Danh mục là bắt buộc.',
            'category_id.exists' => 'Danh mục đã chọn không tồn tại.',

            'brand_id.required' => 'Thương hiệu là bắt buộc.',
            'brand_id.exists' => 'Thương hiệu đã chọn không tồn tại.',

            'tags.required' => 'Thẻ sản phẩm là bắt buộc.',
            'tags.string' => 'Thẻ sản phẩm phải là chuỗi.',

            'product_image.required' => 'Ảnh đại diện sản phẩm là bắt buộc.',
            'product_image.image' => 'Ảnh đại diện phải là định dạng ảnh.',
            'product_image.mimes' => 'Ảnh đại diện chỉ chấp nhận jpeg, png, jpg, gif.',
            'product_image.max' => 'Ảnh đại diện không được vượt quá 2MB.',

            'images.required' => 'Bộ ảnh sản phẩm là bắt buộc.',
            'images.array' => 'Bộ ảnh phải là mảng.',
            'images.*.image' => 'Mỗi ảnh phải là định dạng hình ảnh.',
            'images.*.mimes' => 'Ảnh chỉ chấp nhận jpeg, png, jpg, gif.',
            'images.*.max' => 'Mỗi ảnh không được lớn hơn 2MB.',

            'attachments.array' => 'Tệp đính kèm phải là mảng.',
            'attachments.*.file' => 'Mỗi tệp đính kèm phải là tệp hợp lệ.',
            'attachments.*.max' => 'Mỗi tệp đính kèm không được lớn hơn 5MB.',

            'removed_attachments.string' => 'Danh sách tệp xoá phải là chuỗi.',

            'active.in' => 'Trường trạng thái kích hoạt không hợp lệ.',
        ];
    }
}
