<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'name' => 'required|string|max:255',
            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($this->product),
            ],
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'quantity_warning' => 'nullable|integer|min:0',
            'weight' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'tags' => 'required|string',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120',

            'removed_attachments' => 'nullable|string',
            'active' => 'boolean',
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
            'sku.unique' => 'Mã SKU đã tồn tại, vui lòng chọn mã khác.',

            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'price.min' => 'Giá sản phẩm không được nhỏ hơn 0.',

            'quantity.required' => 'Số lượng tồn kho là bắt buộc.',
            'quantity.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'quantity.min' => 'Số lượng tồn kho không được nhỏ hơn 0.',

            'quantity_warning.integer' => 'Ngưỡng cảnh báo tồn kho phải là số nguyên.',
            'quantity_warning.min' => 'Ngưỡng cảnh báo không được nhỏ hơn 0.',

            'weight.required' => 'Khối lượng sản phẩm là bắt buộc.',
            'weight.numeric' => 'Khối lượng sản phẩm phải là số.',
            'weight.min' => 'Khối lượng sản phẩm không được nhỏ hơn 0.',

            'description.string' => 'Mô tả sản phẩm phải là chuỗi.',

            'category_id.required' => 'Danh mục sản phẩm là bắt buộc.',
            'category_id.exists' => 'Danh mục đã chọn không hợp lệ.',

            'brand_id.required' => 'Thương hiệu sản phẩm là bắt buộc.',
            'brand_id.exists' => 'Thương hiệu đã chọn không hợp lệ.',

            'tags.required' => 'Thẻ sản phẩm là bắt buộc.',
            'tags.string' => 'Thẻ sản phẩm phải là chuỗi.',

            'product_image.image' => 'Ảnh đại diện phải là hình ảnh.',
            'product_image.mimes' => 'Ảnh đại diện chỉ chấp nhận định dạng jpeg, png, jpg, gif.',
            'product_image.max' => 'Ảnh đại diện không được lớn hơn 2MB.',

            'images.array' => 'Bộ ảnh sản phẩm phải là một mảng.',
            'images.*.image' => 'Mỗi ảnh trong bộ ảnh phải là hình ảnh.',
            'images.*.mimes' => 'Mỗi ảnh chỉ chấp nhận định dạng jpeg, png, jpg, gif.',
            'images.*.max' => 'Mỗi ảnh không được vượt quá 2MB.',

            'attachments.array' => 'Tệp đính kèm phải là một mảng.',
            'attachments.*.file' => 'Mỗi tệp đính kèm phải là tệp hợp lệ.',
            'attachments.*.max' => 'Mỗi tệp đính kèm không được vượt quá 5MB.',

            'removed_attachments.string' => 'Danh sách tệp xoá phải là chuỗi.',

            'active.boolean' => 'Trường trạng thái kích hoạt chỉ nhận giá trị đúng/sai.',
        ];
    }
}
