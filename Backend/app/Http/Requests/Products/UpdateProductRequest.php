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
        $productId = $this->route('product');

        return [
            'name' => 'required|string|max:255|unique:products,name,' . $productId,
            'sku' => 'required|string|max:100|unique:products,sku,' . $productId,
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'quantity_warning' => 'nullable|integer|min:0',
            'weight' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'tags' => 'required|string|max:255',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120',
            'removed_attachments' => 'nullable|string',
            'active' => 'nullable|in:true,false,1,0,on,off',

            // Biến thể
            'variants' => 'nullable|array',
            'variants.*.sku' => [
                'required',
                'string',
                'max:100',
                'distinct',
                Rule::unique('variants', 'sku')->ignore($productId, 'product_id'),
            ],
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.weight' => 'nullable|string|max:50',
            'variants.*.variant_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',

            'variant_keys' => 'nullable|array',
            'variant_keys.*' => 'nullable|string',
        ];
        'name' => 'required|string|max:255|unique:products,name,' . $productId,
        'sku' => 'required|string|max:100|unique:products,sku,' . $productId,
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'quantity_warning' => 'nullable|integer|min:0',
        'weight' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'tags' => 'required|string|max:255',
        'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'attachments' => 'nullable|array',
        'attachments.*' => 'file|max:5120',
        'removed_attachments' => 'nullable|string',
        'active' => 'nullable|in:true,false,1,0,on,off',

        //Thuộc tính
        'attributeId' => 'required|array|min:1',
        'attributeValues' => 'required|array|min:1',
        // Biến thể
        'product_variants' => 'required|array',
        'product_variants.*.sku' => [
            'required',
            'string',
            'max:100',
            'distinct',
            Rule::unique('variants', 'sku')->ignore($productId, 'product_id'),
        ],
        'product_variants.*.price' => 'required|numeric|min:0',
        'product_variants.*.quantity' => 'required|integer|min:0',
        'product_variants.*.weight' => 'nullable|string|max:50',
        'product_variants.*.variant_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

        'variant_keys' => 'nullable|array',
        'variant_keys.*' => 'nullable|string',
    ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'name.string' => 'Tên sản phẩm phải là chuỗi.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên sản phẩm đã tồn tại.',

            'sku.required' => 'SKU sản phẩm không được để trống.',
            'sku.string' => 'SKU sản phẩm phải là chuỗi.',
            'sku.max' => 'SKU sản phẩm không được vượt quá 100 ký tự.',
            'sku.unique' => 'SKU sản phẩm đã tồn tại.',

            'price.required' => 'Giá sản phẩm không được để trống.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 0.',

            'quantity.required' => 'Số lượng không được để trống.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 0.',

            'quantity_warning.integer' => 'Cảnh báo số lượng phải là số nguyên.',
            'quantity_warning.min' => 'Cảnh báo số lượng phải lớn hơn hoặc bằng 0.',

            'weight.required' => 'Khối lượng không được để trống.',
            'weight.numeric' => 'Khối lượng phải là số.',
            'weight.min' => 'Khối lượng phải lớn hơn hoặc bằng 0.',

            'description.string' => 'Mô tả sản phẩm phải là chuỗi.',

            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không tồn tại.',

            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'brand_id.exists' => 'Thương hiệu không tồn tại.',

            'tags.required' => 'Tags không được để trống.',
            'tags.string' => 'Tags phải là chuỗi.',
            'tags.max' => 'Tags không được vượt quá 255 ký tự.',

            'product_image.image' => 'Ảnh sản phẩm phải là hình ảnh.',
            'product_image.mimes' => 'Ảnh sản phẩm phải có định dạng jpeg, png, jpg hoặc gif.',
            'product_image.max' => 'Ảnh sản phẩm không được vượt quá 2MB.',

            'attachments.array' => 'Tệp đính kèm không hợp lệ.',
            'attachments.*.file' => 'Mỗi tệp đính kèm phải là một tệp hợp lệ.',
            'attachments.*.max' => 'Mỗi tệp đính kèm không được vượt quá 5MB.',

            'removed_attachments.string' => 'Danh sách tệp xoá phải là chuỗi.',

            'active.in' => 'Trạng thái sản phẩm không hợp lệ.',

            // Biến thể
            'variants.array' => 'Dữ liệu biến thể không hợp lệ.',

            'variants.*.sku.required' => 'SKU của biến thể không được để trống.',
            'variants.*.sku.string' => 'SKU của biến thể phải là chuỗi.',
            'variants.*.sku.max' => 'SKU của biến thể không được vượt quá 100 ký tự.',
            'variants.*.sku.distinct' => 'SKU của các biến thể không được trùng nhau.',
            'variants.*.sku.unique' => 'SKU của biến thể đã tồn tại trong hệ thống.',

            'variants.*.price.required' => 'Giá của biến thể không được để trống.',
            'variants.*.price.numeric' => 'Giá của biến thể phải là số.',
            'variants.*.price.min' => 'Giá của biến thể phải lớn hơn hoặc bằng 0.',

            'variants.*.quantity.required' => 'Số lượng của biến thể không được để trống.',
            'variants.*.quantity.integer' => 'Số lượng của biến thể phải là số nguyên.',
            'variants.*.quantity.min' => 'Số lượng của biến thể phải lớn hơn hoặc bằng 0.',

            'variants.*.weight.string' => 'Khối lượng của biến thể phải là chuỗi.',
            'variants.*.weight.max' => 'Khối lượng của biến thể không được vượt quá 50 ký tự.',

            'variants.*.variant_image.image' => 'Ảnh của biến thể phải là hình ảnh.',
            'variants.*.variant_image.mimes' => 'Ảnh của biến thể phải có định dạng jpeg, png, jpg hoặc webp.',
            'variants.*.variant_image.max' => 'Ảnh của biến thể không được vượt quá 2MB.',
        'active.in' => 'Trạng thái sản phẩm không hợp lệ.',
        //thuộc tính
        'attributeId.required' => 'Bạn phải chọn ít nhất một thuộc tính.',
        'attributeId.*.integer' => 'Mỗi thuộc tính phải là số nguyên.',
        'attributeValues.required' => 'Bạn phải chọn giá trị cho các thuộc tính.',
    
        // Biến thể
        'product_variants.array' => 'Dữ liệu biến thể không hợp lệ.',
        'product_variants.required' => 'Trường product_variants bắt buộc phải nhập',

        'product_variants.*.sku.required' => 'SKU của biến thể không được để trống.',
        'product_variants.*.sku.string' => 'SKU của biến thể phải là chuỗi.',
        'product_variants.*.sku.max' => 'SKU của biến thể không được vượt quá 100 ký tự.',
        'product_variants.*.sku.distinct' => 'SKU của các biến thể không được trùng nhau.',
        'product_variants.*.sku.unique' => 'SKU của biến thể đã tồn tại trong hệ thống.',

        'product_variants.*.price.required' => 'Giá của biến thể không được để trống.',
        'product_variants.*.price.numeric' => 'Giá của biến thể phải là số.',
        'product_variants.*.price.min' => 'Giá của biến thể phải lớn hơn hoặc bằng 0.',

        'product_variants.*.quantity.required' => 'Số lượng của biến thể không được để trống.',
        'product_variants.*.quantity.integer' => 'Số lượng của biến thể phải là số nguyên.',
        'product_variants.*.quantity.min' => 'Số lượng của biến thể phải lớn hơn hoặc bằng 0.',

        'product_variants.*.weight.string' => 'Khối lượng của biến thể phải là chuỗi.',
        'product_variants.*.weight.max' => 'Khối lượng của biến thể không được vượt quá 50 ký tự.',

        'product_variants.*.variant_image.image' => 'Ảnh của biến thể phải là hình ảnh.',
        'product_variants.*.variant_image.mimes' => 'Ảnh của biến thể phải có định dạng jpeg, png, jpg hoặc webp.',
        'product_variants.*.variant_image.max' => 'Ảnh của biến thể không được vượt quá 2MB.',

            'variant_keys.array' => 'Danh sách khóa biến thể không hợp lệ.',
            'variant_keys.*.string' => 'Khóa biến thể phải là chuỗi.',
        ];
    }
}
