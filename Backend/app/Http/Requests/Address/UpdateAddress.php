<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddress extends FormRequest
{
    /**
     * Cho phép request này được thực hiện.
     */
    public function authorize(): bool
    {
        return true; // Cho phép request được xử lý
    }

    /**
     * Luật kiểm tra dữ liệu.
     */
    public function rules(): array
    {
        return [
            'fullname'          => 'required|string|max:255',
            'phone'             => ['required', 'regex:/^(0[3|5|7|8|9])[0-9]{8}$/'],
            'address'           => 'required|string|max:255',
            'specific_address'  => 'required|string|max:255',
            'is_default'        => 'nullable|boolean',
        ];
    }

    /**
     * Thông báo lỗi tuỳ chỉnh.
     */
    public function messages(): array
    {
        return [
            'fullname.required'         => 'Vui lòng nhập họ và tên.',
            'phone.required'            => 'Vui lòng nhập số điện thoại.',
            'phone.regex'               => 'Số điện thoại không hợp lệ.',
            'address.required'          => 'Vui lòng chọn tỉnh/thành, quận/huyện, phường/xã.',
            'specific_address.required' => 'Vui lòng nhập địa chỉ cụ thể.',
        ];
    }
}
