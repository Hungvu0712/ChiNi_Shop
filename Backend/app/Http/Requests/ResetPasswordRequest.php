<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;

use function PHPSTORM_META\map;

class ResetPasswordRequest extends FormRequest
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
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::min(8) // tối thiểu 8 ký tự
                ->letters() // ít nhất 1 chữ cái
                ->mixedCase() // có cả chữ hoa và thường
                ->numbers() // có số
                ->symbols() // có ký tự đặc biệt
                ->uncompromised(), // không nằm trong danh sách rò rỉ (breach list)
                ]
        ];
    }

    public function messages(){
        return [
                'email.required' => 'Vui lòng nhập email',
                'email.email' => 'Chưa đúng định dạng email',

                'password.required' => 'Vui lòng nhập mật khẩu',
                'password.confiirmed' => 'Mật khẩu và xác nhận không khớp',
                'password.min' => 'Mật khẩu tối thiểu 8 ký tự',
                'password.letters' => 'Mật khẩu phải chứa ít nhất một ký tự chữ.',
                'password.mixed' => 'Mật khẩu phải bao gồm cả chữ hoa và chữ thường.',
                'password.numbers' => 'Mật khẩu phải chứa ít nhất một chữ số.',
                'password.symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt.',
                'password.uncompromised' => 'Mật khẩu này đã từng bị rò rís. Vui sối chọn mật khẩu khác.',
        ];
    }
}
