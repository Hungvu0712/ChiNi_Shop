<x-guest-layout>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2 class="auth-title">Đăng ký tài khoản</h2>
                <p class="auth-subtitle">Tạo tài khoản để bắt đầu trải nghiệm</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Tên đăng nhập</label>
                    <div class="input-wrapper">
                        <i class="bi bi-person input-icon"></i>
                        <input id="name" class="form-input" type="text" name="name"
                            value="{{ old('name') }}" placeholder="Nhập tên đăng nhập" autofocus autocomplete="name"
                            required>
                    </div>
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i class="bi bi-envelope input-icon"></i>
                        <input id="email" class="form-input" type="email" name="email"
                            value="{{ old('email') }}" placeholder="Nhập địa chỉ email" autocomplete="email" required>
                    </div>
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock input-icon"></i>
                        <input id="password" class="form-input" type="password" name="password"
                            placeholder="Nhập mật khẩu" autocomplete="new-password" required>
                        <button type="button" class="password-toggle">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock input-icon"></i>
                        <input id="password_confirmation" class="form-input" type="password"
                            name="password_confirmation" placeholder="Nhập lại mật khẩu" autocomplete="new-password"
                            required>
                        <button type="button" class="password-toggle">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
                <small class="form-text text-muted">
                    <span style="color: rgb(10, 10, 10); font-size: 12px">Lưu ý : Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt</span>
                </small>


                <div class="form-actions mt-3">
                    <button type="submit" class="auth-button">
                        Đăng ký
                    </button>
                </div>

                <div class="auth-footer">
                    <p>Đã có tài khoản? <a href="{{ route('login') }}" class="auth-link">Đăng nhập ngay</a></p>
                </div>
            </form>

            <div class="separator">Hoặc đăng nhập bằng email</div>

            <!-- Social Login -->
            <div class="social-login">
                <a href="{{ route('auth.google') }}" class="btn btn-outline-primary w-100 mb-2">
                    <span style="color: red"><i class="bi bi-google"></i></span>
                    Đăng nhập với Google
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>


<style>
    .auth-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 1rem;
    }

    .auth-card {
        width: 100%;
        max-width: 500px;
        /* Đã tăng chiều rộng */
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 2.5rem;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .auth-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .auth-subtitle {
        color: #718096;
        font-size: 0.9375rem;
    }

    .google-login {
        text-align: center;
        margin-bottom: 1rem;
    }

    .google-button {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 500;
        color: #444;
        background: #fff;
        transition: background-color 0.2s;
        text-decoration: none;
    }

    .google-button:hover {
        background-color: #f1f1f1;
    }

    .separator {
        text-align: center;
        font-size: 0.875rem;
        color: #999;
        margin: 1rem 0;
        position: relative;
    }

    .separator::before,
    .separator::after {
        content: "";
        height: 1px;
        width: 30%;
        background: #ddd;
        position: absolute;
        top: 50%;
    }

    .separator::before {
        left: 0;
    }

    .separator::after {
        right: 0;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #4a5568;
        font-size: 0.9375rem;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        color: #a0aec0;
        font-size: 1rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9375rem;
        transition: all 0.2s;
        background-color: #f8fafc;
    }

    .form-input:focus {
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.16);
        outline: none;
        background-color: white;
    }

    .password-toggle {
        position: absolute;
        right: 1rem;
        background: none;
        border: none;
        color: #a0aec0;
        cursor: pointer;
        font-size: 1rem;
    }

    .form-error {
        color: #e53e3e;
        font-size: 0.8125rem;
        margin-top: 0.25rem;
    }

    .auth-button {
        width: 100%;
        padding: 0.875rem;
        background-color: #4299e1;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .auth-button:hover {
        background-color: #3182ce;
    }

    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.9375rem;
        color: #718096;
    }

    .auth-link {
        color: #4299e1;
        text-decoration: none;
        font-weight: 500;
    }

    .auth-link:hover {
        text-decoration: underline;
    }
</style>

<script>
    document.querySelectorAll('.password-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });
</script>
