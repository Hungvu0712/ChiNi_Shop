@extends('client.layouts.master')
@section('title', 'Đổi mật khẩu')
@section('css')
<style>
    .password-form {
        max-width: 500px;
        margin: 0 auto;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        background: white;
    }
    .password-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .password-header h2 {
        font-weight: 600;
        color: #2d3748;
    }
    .password-header p {
        color: #718096;
    }
    .form-label {
        font-weight: 500;
        color: #4a5568;
    }
    .form-control {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
    }
    .form-control:focus {
        border-color: #4299e1;
        box-shadow: 0 0 0 0.2rem rgba(66, 153, 225, 0.25);
    }
    .btn-save {
        background-color: #4299e1;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s;
    }
    .btn-save:hover {
        background-color: #3182ce;
    }
    .alert-success {
        color: #38a169;
        background-color: #f0fff4;
        border-color: #c6f6d5;
        padding: 10px;
        border-radius: 8px;
        margin-top: 15px;
    }
</style>
@endsection

@section('content')
<div class="container py-5" style="margin-top: 100px">
    <div class="password-form">
        <div class="password-header">
            <h2>{{ __('Đổi mật khẩu') }}</h2>
            <p class="mt-2">{{ __('Đảm bảo tài khoản của bạn sử dụng mật khẩu dài và ngẫu nhiên để bảo mật.') }}</p>
        </div>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-4">
                <label for="update_password_current_password" class="form-label">{{ __('Mật khẩu hiện tại') }}</label>
                <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
                @error('current_password', 'updatePassword')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="update_password_password" class="form-label">{{ __('Mật khẩu mới') }}</label>
                <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password">
                @error('password', 'updatePassword')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="update_password_password_confirmation" class="form-label">{{ __('Xác nhận mật khẩu') }}</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                @error('password_confirmation', 'updatePassword')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <button type="submit" class="btn btn-save text-white">
                    {{ __('Lưu thay đổi') }}
                </button>

                @if (session('status') === 'password-updated')
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="alert-success">
                        {{ __('Đã lưu thay đổi.') }}
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')

@endsection
