<?php

namespace App\Http\Requests\Vouchers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vouchers', 'code')->ignore($this->route('voucher')),
            ],
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
                $rules['max_discount_value'] = 'required|numeric|min:0|max:99999999.99|gte:value';
            } else {
                // amount
                $rules['value'] = 'required|numeric|min:1';
                $rules['max_discount_value'] = 'nullable|numeric|min:0|max:99999999.99';
            }

            $rules['min_order_value'] = 'nullable|numeric|min:0|max:99999999.99';
        }

        return $rules;
    }

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
            'value.min' => 'Giá trị giảm phải lớn hơn hoặc bằng 1.',
            'value.max' => 'Phần trăm giảm giá tối đa là 100%.',

            'discount_type.required' => 'Vui lòng chọn kiểu giảm.',
            'discount_type.in' => 'Kiểu giảm không hợp lệ.',

            'min_order_value.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_order_value.min' => 'Giá trị đơn hàng tối thiểu không được âm.',
            'min_order_value.max' => 'Giá trị đơn hàng tối thiểu không được vượt quá 99999999.99.',

            'max_discount_value.required' => 'Vui lòng nhập mức giảm tối đa.',
            'max_discount_value.numeric' => 'Mức giảm tối đa phải là số.',
            'max_discount_value.min' => 'Mức giảm tối đa không được âm.',
            'max_discount_value.max' => 'Mức giảm tối đa không được vượt quá 99999999.99.',
            'max_discount_value.gte' => 'Mức giảm tối đa phải lớn hơn hoặc bằng giá trị giảm.',

            'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',

            'limit.required' => 'Vui lòng nhập số lượt sử dụng.',
            'limit.integer' => 'Lượt sử dụng phải là số nguyên.',
            'limit.min' => 'Lượt sử dụng phải ít nhất là 1.',
        ];
    }
}
