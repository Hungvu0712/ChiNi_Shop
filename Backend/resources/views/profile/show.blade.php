@extends('client.layouts.master')
@section('title', 'Thông tin tài khoản')
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

    /* Font Awesome icons (you can include the actual library or use SVG) */
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
</style>
@section('content')
    <div class="profile-container">

        <div class="profile-card">
            <div class="profile-avatar-section">
                <div class="avatar-container">
                    {{-- <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Profile Avatar"
                        class="profile-avatar"> --}}
                    <img src="{{ Auth::user()->avatar ? Auth::user()->avatar : asset('https://cdn-icons-png.flaticon.com/512/149/149071.png') }}"
                        alt="Profile Avatar"
                        class="profile-avatar">
                    <div class="avatar-overlay">
                        <i class="fas fa-camera"></i>
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
                        @if ($user->name == null)
                            <span class="detail-value">Chưa cập nhật</span>
                        @else
                            <span class="detail-value">{{ $user->name }}</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Email</span>
                        @if ($user->email == null)
                            <span class="detail-value">Chưa cập nhật</span>
                        @else
                            <span class="detail-value">{{ $user->email }}</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Phone Number</span>
                        @if ($user->phone == null)
                            <span class="detail-value">Chưa cập nhật</span>
                        @else
                            <span class="detail-value">{{ $user->phone }}</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Address</span>
                        @if ($user->address == null)
                            <span class="detail-value">Chưa cập nhật</span>
                        @else
                            <span class="detail-value">{{ $user->address }}</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fa-solid fa-genderless"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Giới tính</span>
                        @if ($user->sex == null)
                            <span class="detail-value">Chưa cập nhật</span>
                        @else
                            <span class="detail-value">{{ $user->sex }}</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fa-solid fa-cake-candles"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Ngày sinh</span>
                        @if ($user->birthday == null)
                            <span class="detail-value">Chưa cập nhật</span>
                        @else
                            <span class="detail-value">{{ $user->birthday }}</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-icon">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Ngày tạo </span>
                        <span class="detail-value">{{ $user->created_at }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-header">
            <div class="profile-actions">
                <a href="{{ route('profile.edit') }}" class="edit-button">
                    <i class="fas fa-edit"></i> Sửa thông tin
                </a>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
