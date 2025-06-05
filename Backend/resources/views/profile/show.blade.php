@extends('client.layouts.master')
@section('title', 'Thông tin tài khoản')
@section('css')
<style>
    :root {
        --primary-color: #3b82f6;
        --primary-hover: #2563eb;
        --secondary-color: #64748b;
        --light-color: #f8fafc;
        --dark-color: #1e293b;
        --border-radius: 0.5rem;
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --transition: all 0.3s ease;
    }

    .profile-container {
        max-width: 1200px;
        margin: 6rem auto 2rem;
        padding: 0 1rem;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 992px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
    }

    .profile-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        padding: 2rem;
        transition: var(--transition);
    }

    .profile-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius);
        font-weight: 500;
        transition: var(--transition);
        text-decoration: none;
        cursor: pointer;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
        border: 1px solid var(--primary-color);
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
        transform: translateY(-1px);
    }

    .btn-secondary {
        background-color: var(--secondary-color);
        color: white;
        border: 1px solid var(--secondary-color);
    }

    .btn-secondary:hover {
        background-color: #475569;
        transform: translateY(-1px);
    }

    .btn-dark {
        background-color: var(--dark-color);
        color: white;
        border: 1px solid var(--dark-color);
    }

    .btn-dark:hover {
        background-color: #334155;
        transform: translateY(-1px);
    }

    .avatar-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .avatar-container {
        position: relative;
        width: 120px;
        height: 120px;
        margin-bottom: 1rem;
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e2e8f0;
    }

    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: var(--transition);
        color: white;
        cursor: pointer;
    }

    .avatar-container:hover .avatar-overlay {
        opacity: 1;
    }

    .profile-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark-color);
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid #e2e8f0;
        border-radius: var(--border-radius);
        font-size: 0.875rem;
        transition: var(--transition);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-error {
        font-size: 0.75rem;
        color: #ef4444;
        margin-top: 0.25rem;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    .activity-log {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        padding: 2rem;
        margin-top: 2rem;
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-radius: var(--border-radius);
        transition: var(--transition);
    }

    .activity-item:hover {
        background-color: #f8fafc;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e0f2fe;
        display: flex;
        justify-content: center;
        align-items: center;
        color: var(--primary-color);
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-description {
        font-size: 0.875rem;
        color: var(--dark-color);
    }

    .activity-time {
        font-size: 0.75rem;
        color: var(--secondary-color);
        margin-top: 0.25rem;
    }

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-grid">
        <!-- Profile Information Section -->
        <div class="profile-card">
            <div class="card-header">
                <h2 class="card-title">Thông tin tài khoản</h2>
            </div>

            <form action="{{ route('profile.update') }}" enctype="multipart/form-data" method="post">
                @csrf
                @method('patch')

                <div class="avatar-section">
                    <div class="avatar-container">
                        <img src="{{ Auth::user()->profile->avatar ?? 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}"
                             alt="Profile Avatar" class="profile-avatar" id="avatar-preview">
                        <label for="avatar-upload" class="avatar-overlay">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="avatar-upload" name="avatar" accept="image/*" class="hidden-upload">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" placeholder="Nhập họ tên">
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" placeholder="Email" disabled>
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input
    type="text"
    name="phone"
    class="form-control"
    value="{{ optional($user->profile)->phone ?? '' }}"
    placeholder="{{ optional($user->profile)->phone ? '' : 'Nhập số điện thoại' }}"
>
                    @error('phone')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Địa chỉ</label>
                    <input
    type="text"
    name="address"
    class="form-control"
    value="{{ optional($user->profile)->address ?? '' }}"
    placeholder="{{ optional($user->profile)->address ? '' : 'Nhập địa chỉ' }}"
>
                    @error('address')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Giới tính</label>
                    <input type="text" name="gender" class="form-control" value="{{ optional($user->profile)->gender ?? '' }}"
                                placeholder="{{ optional($user->profile)->gender ? '' : 'Nhập giới tính' }}">
                            @error('gender')
                                <div class="form-error" style="color: red">{{ $message }}</div>
                            @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="birthday" class="form-control" value="{{ optional($user->profile)->birthday ?? 'Chưa có' }}">
                    @error('birthday')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="action-buttons">

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i> Cập nhật
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Change Section -->
        <div class="profile-card">
            <div class="card-header">
                <h2 class="card-title">Thay đổi mật khẩu</h2>
            </div>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                @method('put')
                <div class="form-group">
                    <label for="old_password" class="form-label">Mật khẩu hiện tại</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" required>
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label">Mật khẩu mới</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-dark">
                        <i class="fas fa-key mr-2"></i> Đổi mật khẩu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Log Section -->
    <div class="activity-log">
        <div class="card-header">
            <h2 class="card-title">Nhật ký hoạt động</h2>
        </div>

        <div class="activity-item">
            <div class="activity-icon">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <div class="activity-content">
                <div class="activity-description">
                    Đăng nhập thành công
                </div>
                <div class="activity-time">
                    2 giờ trước
                </div>
            </div>
        </div>

        <div class="activity-item">
            <div class="activity-icon">
                <i class="fas fa-pencil-alt"></i>
            </div>
            <div class="activity-content">
                <div class="activity-description">
                    Cập nhật thông tin tài khoản
                </div>
                <div class="activity-time">
                    1 ngày trước
                </div>
            </div>
        </div>

        <div class="activity-item">
            <div class="activity-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <div class="activity-content">
                <div class="activity-description">
                    Thêm phương thức thanh toán mới
                </div>
                <div class="activity-time">
                    3 ngày trước
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview avatar when selected
    document.getElementById('avatar-upload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('avatar-preview').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
