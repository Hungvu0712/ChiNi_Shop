<x-guest-layout>
    <div class="login-container">
        <div class="login-card">
            <!-- Session Status -->
            <x-auth-session-status class="alert alert-success mb-4" :status="session('status')" />

            <div class="login-header">
                <h2 class="login-title">Đăng Nhập</h2>
                <p class="login-subtitle">Chào mừng trở lại! Vui lòng đăng nhập để tiếp tục</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input id="email"
                               class="form-control"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="Nhập email của bạn">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="text-danger mt-1 small" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input id="password"
                               class="form-control"
                               type="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="Nhập mật khẩu">
                        <button class="btn btn-outline-secondary toggle-password" type="button">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="text-danger mt-1 small" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input id="remember_me"
                               type="checkbox"
                               class="form-check-input"
                               name="remember">
                        <label for="remember_me" class="form-check-label">Ghi nhớ đăng nhập</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="text-decoration-none" href="{{ route('password.request') }}">
                            Quên mật khẩu?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                    Đăng Nhập
                </button>

                <!-- Divider -->
                <div class="divider my-4">
                    <span class="divider-line"></span>
                    <span class="divider-text">Hoặc</span>
                    <span class="divider-line"></span>
                </div>

                <!-- Social Login -->
                <div class="social-login">
                    <a href="{{ route('auth.google') }}" class="btn btn-outline-primary w-100 mb-2">
                        <span style="color: red"><i class="bi bi-google"></i></span>
                        Đăng nhập với Google
                    </a>
                </div>

                <!-- Register Link -->
                <div class="text-center mt-4">
                    <p class="text-muted">Chưa có tài khoản?
                        <span style="color:#4299e1"><a href="{{ route('register') }}" class="text-decoration-none">Đăng ký ngay</a></span>
                    </p>
                </div>
            </form>
        </div>
    </div>

</x-guest-layout>

<style>
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f8f9fa;
    padding: 20px;
}

.login-card {
    width: 100%;
    max-width: 450px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2.5rem;
}

.login-header {
    text-align: center;
    margin-bottom: 2rem;
}

.login-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.login-subtitle {
    color: #718096;
    font-size: 0.9375rem;
}

.form-label {
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 0.5rem;
}

.input-group-text {
    background-color: #f8fafc;
    border-right: none;
}

.form-control {
    border-left: none;
    padding-left: 0;
}

.form-control:focus {
    box-shadow: none;
    border-color: #ced4da;
}

.toggle-password {
    border-left: none;
}

.divider {
    display: flex;
    align-items: center;
}

.divider-line {
    flex: 1;
    height: 1px;
    background-color: #e2e8f0;
}

.divider-text {
    padding: 0 1rem;
    color: #718096;
    font-size: 0.875rem;
}

.social-login .btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.social-icon {
    width: 18px;
    height: 18px;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .login-card {
        padding: 1.5rem;
    }
}
</style>

<script>
document.querySelector('.toggle-password').addEventListener('click', function() {
    const input = document.getElementById('password');
    const icon = this.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
});
</script>
