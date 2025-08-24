<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;


class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // dd($this->all());
        return [
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'user_note' => 'nullable|string|max:255',
            'ship_user_name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255',
            'ship_user_phonenumber' => [
                'required',
                'regex:/^0[3|5|7|8|9][0-9]{8}$/',
            ],
            'user_phonenumber' => [
                'required',
                'regex:/^0[3|5|7|8|9][0-9]{8}$/',
            ],
            'ship_user_address' => 'required|string|max:255',
            'shipping_fee' => 'required',
            'cart_item_ids' => 'required|array',
            'cart_item_ids.*' => 'integer|exists:cart_items,id',
        ];
    }
    public function messages(): array
    {
        return [
            'payment_method_id.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method_id.integer' => 'Phương thức thanh toán không hợp lệ.',
            'payment_method_id.exists' => 'Phương thức thanh toán không tồn tại.',

            'user_note.string' => 'Ghi chú phải là văn bản.',
            'user_note.max' => 'Ghi chú không được vượt quá 255 ký tự.',

            'ship_user_name.required' => 'Vui lòng nhập tên người nhận.',
            'ship_user_name.string' => 'Tên người nhận phải là văn bản.',
            'ship_user_name.max' => 'Tên người nhận không được vượt quá 255 ký tự.',

            'user_name.required' => 'Vui lòng nhập tên khách hàng.',
            'user_name.string' => 'Tên khách hàng phải là văn bản.',
            'user_name.max' => 'Tên khách hàng không được vượt quá 255 ký tự.',

            'user_phonenumber.required' => 'Vui lòng nhập số điện thoại khách hàng.',
            'user_phonenumber.regex' => 'Số điện thoại không đúng định dạng.',

            'ship_user_phonenumber.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'ship_user_phonenumber.regex' => 'Số điện thoại không đúng định dạng.',

            'ship_user_address.required' => 'Vui lòng nhập địa chỉ người nhận.',
            'ship_user_address.string' => 'Địa chỉ phải là văn bản.',
            'ship_user_address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',

            'shipping_method.required' => 'Vui lòng chọn phương thức vận chuyển.',
            'shipping_method.max' => 'Phương thức vận chuyển không được vượt quá 50 ký tự.',

            'shipping_fee.required' => 'Vui lòng nhập phí vận chuyển.',
            

            'cart_item_ids.required' => 'Vui lòng chọn sản phẩm trong giỏ hàng.',
            'cart_item_ids.array' => 'Giỏ hàng không hợp lệ.',
            'cart_item_ids.*.integer' => 'Mã sản phẩm trong giỏ hàng không hợp lệ.',
            'cart_item_ids.*.exists' => 'Sản phẩm trong giỏ hàng không tồn tại.',

            
        ];
    }

    
}
