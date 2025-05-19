@extends('client.layouts.master')
@section('title', 'Thay đổi thông tin tài khoản')
@section('css')

@endsection
<style>
    .profile-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        margin-top: 100px
    }

    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        margin-top: 5px;
    }

    .profile-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    .edit-button {
        background-color: #3498db;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .edit-button:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
    }

    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        padding: 2rem;
    }

    .profile-avatar-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 2rem;
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
        border: 4px solid #ecf0f1;
    }

    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: white;
        cursor: pointer;
    }

    .avatar-container:hover .avatar-overlay {
        opacity: 1;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        color: #2c3e50;
    }

    .profile-details {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: 8px;
        transition: background-color 0.2s ease;
    }

    .detail-item:hover {
        background-color: #f8f9fa;
    }

    .detail-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e8f4fc;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #3498db;
        font-size: 1rem;
    }

    .detail-content {
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .detail-label {
        font-size: 0.8rem;
        color: #7f8c8d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 1rem;
        font-weight: 500;
        color: #2c3e50;
        margin-top: 0.2rem;
    }

    .avatar-upload-container {
        position: relative;
        width: 140px;
        margin: 0 auto 1.5rem;
    }

    .avatar-wrapper {
        position: relative;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .avatar-upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
        color: white;
        flex-direction: column;
    }

    .avatar-wrapper:hover .avatar-upload-overlay {
        opacity: 1;
    }

    .upload-content {
        text-align: center;
        transform: translateY(10px);
        transition: transform 0.3s ease;
    }

    .avatar-wrapper:hover .upload-content {
        transform: translateY(0);
    }

    .upload-content i {
        font-size: 1.5rem;
        margin-bottom: 5px;
    }

    .upload-content span {
        font-size: 0.8rem;
        display: block;
        font-weight: 500;
    }

    .hidden-upload {
        display: none;
    }

    .profile-name {
        text-align: center;
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0.5rem 0 0;
        color: #2c3e50;
    }

    /* Animation when new avatar is selected */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .avatar-uploaded {
        animation: fadeIn 0.5s ease;
    }

    /* Font Awesome icons (you can include the actual library or use SVG) */
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
</style>
@section('content')
    <div class="profile-container">

        <form action="{{ route('profile.update') }}" enctype="multipart/form-data" method="post">
            @csrf
            @method('patch')
            <div class="profile-card">
                <div class="profile-avatar-section">
                    <div class="avatar-upload-container">
                        <div class="avatar-wrapper">
                            <img src="{{ Auth::user()->avatar ?? 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}"
                                alt="Profile Avatar" class="profile-avatar" id="avatar-preview">

                            <label for="avatar-upload" class="avatar-upload-overlay">
                                <div class="upload-content">
                                    <i class="fas fa-camera"></i>
                                    <span>Change Avatar</span>
                                </div>
                                <input type="file" id="avatar-upload" name="avatar" accept="image/*"
                                    class="hidden-upload">
                            </label>
                        </div>
                    </div>
                    <h2 class="profile-name">{{ $user->name }}</h2>
                </div>

                <div class="profile-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">Full Name</span>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                                placeholder="Full Name">
                            @error('name')
                                <div class="form-error" style="color: red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">Email</span>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                                placeholder="Email" disabled>
                            @error('email')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">Phone Number</span>
                            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}"
                                placeholder="Số điện thoại">
                            @error('phone')
                                <div class="form-error" style="color: red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">Address</span>
                            <input type="text" name="address" class="form-control" value="{{ $user->address }}"
                                placeholder="Địa chỉ">
                            @error('address')
                                <div class="form-error" style="color: red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fa-solid fa-genderless"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">Giới tính</span>
                            <select name="sex" id="" class="form-control">
                                <option value="Nam" {{ $user->sex == 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ $user->sex == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                            </select>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fa-solid fa-cake-candles"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">Ngày sinh</span>
                            <input type="date" name="birthday" class="form-control" value="{{ $user->birthday }}"
                                placeholder="Birthday">
                            @error('birthday')
                                <div class="form-error" style="color: red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-header">
                <div class="profile-actions">
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        document.getElementById('avatar-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const avatarPreview = document.getElementById('avatar-preview');
                    avatarPreview.src = event.target.result;
                    avatarPreview.classList.add('avatar-uploaded');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
