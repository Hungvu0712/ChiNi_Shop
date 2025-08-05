<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAddress extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
public function authorize()
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
        return [
            'fullname'          => 'required|string|max:255',
            'phone'             => ['required', 'regex:/^(0[3|5|7|8|9])+([0-9]{8})$/'],
            'address'           => 'required|string|max:255',
            'specific_address'  => 'required|string|max:255',
            'is_default'        => 'nullable|boolean',
        ];
    }

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
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()
                ->back()
                ->withErrors($validator, 'addAddress') // Gán lỗi vào error bag addAddress
                ->withInput()
        );
    }
}
