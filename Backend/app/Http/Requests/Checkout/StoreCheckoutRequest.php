<?php

namespace App\Http\Requests\Checkout;

use App\Models\Product;
use App\Models\Voucher;
use App\Models\Variant;
use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function prepareForValidation()
    {
        // Lấy tất cả dữ liệu từ request
        $data = $this->all();

        // Loại bỏ các trường null hoặc rỗng (''), kể cả trong mảng con
        $filteredData = array_filter($data, function ($value) {
            if (is_array($value)) {
                // Nếu là mảng, loại bỏ các giá trị null/rỗng trong mảng
                return count(array_filter($value, function ($item) {
                    return $item !== null && $item !== '';
                })) > 0;
            }
            // Nếu không phải mảng, loại bỏ null hoặc chuỗi rỗng
            return $value !== null && $value !== '';
        });

        // Ghi đè lại dữ liệu request
        $this->replace($filteredData);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Mua giỏ hàng
            'cart_item_ids' => 'required_without:product_id|array',
            'cart_item_ids.*' => 'integer|exists:cart_items,id', // Kiểm tra từng ID của cart_item_ids
            
        ];
    }
}
