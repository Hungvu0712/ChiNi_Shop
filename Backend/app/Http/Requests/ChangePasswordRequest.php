<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
        return [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất :min ký tự.',
            'password.confirmed' => 'Mật khẩu mới và xác nhận không khớp.',
        ];
    }

/*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */

/*******  f8d13621-77ed-4b10-a451-a33dad9e2cfb  *******/
    public function attributes(): array
    {
        return [
            'current_password' => 'mật khẩu hiện tại',
            'password' => 'mật khẩu mới',
            'password_confirmation' => 'xác nhận mật khẩu',
        ];
    }
}
