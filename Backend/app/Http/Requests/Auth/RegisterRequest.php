<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules;
class RegisterRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email:rfc,dns',
                'max:255','unique:users,email,',
                'regex:/^[\w\.\-]+@((gmail\.com)|([a-zA-Z0-9\-]+\.(edu)(\.[a-zA-Z]{2,})?))$/'
            ],
            'password' => [
                    'required',
                    'confirmed',
                    Password::min(8) // tối thiểu 8 ký tự
                        ->letters() // ít nhất 1 chữ cái
                        ->mixedCase() // có cả chữ hoa và thường
                        ->numbers() // có số
                        ->symbols() // có ký tự đặc biệt
                        ->uncompromised(), // không nằm trong danh sách rò rỉ (breach list)
                ],

            'phone' => [
                        'nullable',
                        'string',
                        'regex:/^(\+84|0)[0-9]{9,10}$/',
                        'max:15',
                        'unique:users,phone'
                    ],

            'address' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'string', 'max:255'],
            'sex' => ['nullable', 'in:Nam,Nữ,Khác'],
            'birthday' => ['nullable', 'date'],
            'role' => ['nullable', 'in:admin,user,editor'],
            'status' => ['nullable', 'in:active,inactive'],
            'google_id' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Vui lòng nhập tên',
            'name.string' => 'Tên phải là 1 chuỗi',
            'name.max' => 'Tên không được quá 255 ký tự',

            'email.required' => 'Vui lòng nhập email',
            'email.string' => 'Email phải là 1 chuỗi',
            'email.max' => 'Email không được quá 255 ký tự',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'email.lowercase' => 'Email không viết chữ hoa',
            'email.regex' => 'Email chỉ được sử dụng đuôi @gmail.com hoặc các email kết thúc bằng .edu.',

            'password.required' => 'Vui lòng nhập mật khẻu',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.mixed' => 'Mật khẩu phải bao gồm cả chữ hoa và chữ thường.',
            'password.letters' => 'Mật khẩu phải chứa ít nhất một ký tự chữ.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một chữ số.',
            'password.symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt.',
            'password.uncompromised' => 'Mật khẩu này đã từng bị rò rỉ dữ liệu. Vui lòng chọn mật khẩu khác.',
            'password.confirmed' => 'Mật khẩu không trùng khớp.',

            'phone.string' => 'Số điện thoại phải là 1 chuỗi',
            'phone.max' => 'Số điện thoại không được quá 15 ký tự',
            'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập đúng định dạng (+84 hoặc 0, theo sau là 9–10 số).',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',

        ];
    }
}
