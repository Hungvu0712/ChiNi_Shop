<?php

namespace App\Http\Requests\Vouchers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
    public function rules()
    {
        $rules = [
            'code' => 'required|unique:vouchers,code',
            'title' => 'required|string|max:255',
            'voucher_type' => 'required|in:discount,freeship',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'limit' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ];

        if ($this->voucher_type === 'discount') {
            $rules['discount_type'] = 'required|in:amount,percent';

            if ($this->discount_type === 'percent') {
                $rules['value'] = 'required|numeric|min:1|max:100';
                $rules['max_discount_value'] = 'required|numeric|min:0';
            } else {
                $rules['value'] = 'required|numeric|min:1';
                $rules['max_discount_value'] = 'required|numeric|min:0|gte:value'; // ✅ Chặn nhỏ hơn value
            }

            $rules['min_order_value'] = 'nullable|numeric|min:0';
        }



        return $rules;
    }

    // Tùy chỉnh thông báo lỗi bằng tiếng Việt
    public function messages()
    {
        return [
            'code.required' => 'Vui lòng nhập mã voucher.',
            'code.unique' => 'Mã voucher đã tồn tại.',
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'voucher_type.required' => 'Vui lòng chọn loại voucher.',
            'voucher_type.in' => 'Loại voucher không hợp lệ.',
            'discount_type.required' => 'Vui lòng chọn kiểu giảm.',
            'discount_type.in' => 'Kiểu giảm không hợp lệ.',
            'value.required' => 'Vui lòng nhập giá trị giảm.',
            'value.numeric' => 'Giá trị giảm phải là số.',
            'value.min' => 'Giá trị giảm phải lớn hơn 0.',
            'value.max' => 'Giá trị phần trăm tối đa là 100%.', // ✅ Thêm dòng này!
            'min_order_value.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_order_value.min' => 'Giá trị đơn hàng tối thiểu phải lớn hơn hoặc bằng 0.',
            'max_discount_value.numeric' => 'Mức giảm tối đa phải là số.',
            'max_discount_value.min' => 'Mức giảm tối đa phải lớn hơn hoặc bằng 0.',
            'max_discount_value.gte' => 'Mức giảm tối đa phải lớn hơn hoặc bằng giá trị giảm.',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải bằng hoặc sau ngày bắt đầu.',
            'limit.required' => 'Vui lòng nhập giới hạn lượt dùng.',
            'limit.integer' => 'Giới hạn lượt dùng phải là số.',
            'limit.min' => 'Giới hạn lượt dùng ít nhất phải là 1.',
            'is_active.boolean' => 'Trạng thái kích hoạt không hợp lệ.',
        ];
    }
}
