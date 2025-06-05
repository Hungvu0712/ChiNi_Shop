<?php

namespace App\Http\Requests;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(03|05|07|08|09)[0-9]{8}$/',
                Rule::unique(Profile::class)->ignore($this->user()->id)
            ],
            'address' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'sex' => ['nullable', 'in:,Nam,Nữ,Khác'],
            'birthday' => [
                'nullable',
                'date',
                'before_or_equal:today'
            ],
        ];
    }

    public function messages(): array
{
    return [
        'name.required' => 'Vui lòng nhập họ tên.',
        'name.string' => 'Họ tên phải là một chuỗi ký tự.',
        'name.max' => 'Họ tên không được vượt quá 255 ký tự.',

        'phone.string' => 'Số điện thoại phải là chuỗi.',
        'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập đúng định dạng (VD: 09xxxxxxxx).',
        'phone.unique' => 'Số điện thoại đã được sử dụng.',

        // Địa chỉ
        'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
        'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',

        // Ảnh đại diện
        'avatar.image' => 'Ảnh đại diện phải là hình ảnh.',
        'avatar.mimes' => 'Ảnh đại diện phải có định dạng: jpeg, png, jpg hoặc gif.',
        'avatar.max' => 'Ảnh đại diện không được vượt quá 2MB.',

        // Giới tính
        'sex.in' => 'Giới tính không hợp lệ. Vui lòng chọn Nam, Nữ hoặc Khác.',

        // Ngày sinh
        'birthday.date' => 'Ngày sinh phải là một ngày hợp lệ.',
        'birthday.before_or_equal' => 'Ngày sinh không được lớn hơn ngày hiện tại.',
    ];
}

}
