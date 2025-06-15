<?php

namespace App\Http\Requests\Vouchers;

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

    // Các quy tắc kiểm tra dữ liệu nhập vào
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255|unique:vouchers,code',
            'title' => 'required|string|max:255',
            'voucher_type' => 'required|in:discount,freeship',
            'value' => 'required|numeric|min:0',
            'discount_type' => 'required|in:amount,percent',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount_value' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'limit' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ];
    }

    // Tùy chỉnh thông báo lỗi bằng tiếng Việt
    public function messages(): array
    {
        return [
            'code.required' => 'Vui lòng nhập mã voucher.',
            'code.unique' => 'Mã voucher đã tồn tại.',
            'title.required' => 'Vui lòng nhập tiêu đề voucher.',
            'voucher_type.required' => 'Vui lòng chọn loại voucher.',
            'voucher_type.in' => 'Loại voucher không hợp lệ.',
            'value.required' => 'Vui lòng nhập giá trị giảm.',
            'value.numeric' => 'Giá trị giảm phải là số.',
            'discount_type.required' => 'Vui lòng chọn kiểu giảm.',
            'discount_type.in' => 'Kiểu giảm không hợp lệ.',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'limit.required' => 'Vui lòng nhập số lượt sử dụng.',
            'limit.integer' => 'Lượt sử dụng phải là số nguyên.',
            'limit.min' => 'Lượt sử dụng phải ít nhất là 1.',
        ];
    }
}
