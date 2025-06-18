@extends('client.layouts.master')
@section('title', 'Thông tin tài khoản')
@section('css')
    <style>
        .profile-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .avatar-upload-btn {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: #fff;
            border-radius: 50%;
            padding: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .profile-card {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: none;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        @media (max-width: 768px) {
            .profile-avatar {
                width: 120px;
                height: 120px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="profile-container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card profile-card mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Thông tin tài khoản</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" enctype="multipart/form-data" method="post">
                            @csrf
                            @method('patch')

                            <!-- Avatar Section -->
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <img src="{{ Auth::user()->profile->avatar ?? 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}"
                                         class="profile-avatar rounded-circle"
                                         id="avatar-preview">
                                    <label for="avatar-upload" class="avatar-upload-btn">
                                        <i class="fas fa-camera text-primary"></i>
                                        <input type="file" id="avatar-upload" name="avatar" accept="image/*" class="d-none">
                                    </label>
                                </div>
                            </div>

                            <!-- Personal Information -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ $user->name }}" placeholder="Nhập họ tên">
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="{{ $user->email }}" placeholder="Email" disabled>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                           value="{{ optional($user->profile)->phone ?? '' }}"
                                           placeholder="{{ optional($user->profile)->phone ? '' : 'Nhập số điện thoại' }}" >
                                    @error('phone')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                           value="{{ optional($user->profile)->address ?? '' }}"
                                           placeholder="{{ optional($user->profile)->address ? '' : 'Nhập địa chỉ' }}">
                                    @error('address')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Giới tính</label>
                                    <input type="text" class="form-control" id="gender" name="gender" value="{{ optional($user->profile)->gender ?? '' }}"
                                    placeholder="{{ optional($user->profile)->gender ? '' : 'Nhập giới tính' }}">
                                    @error('gender')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="birthday" class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" id="birthday" name="birthday"
                                           value="{{ optional($user->profile)->birthday ? optional($user->profile)->birthday->format('Y-m-d') : '' }}"
                                           placeholder="{{ optional($user->profile)->birthday ? '' : 'Nhập ngày sinh' }}">
                                    @error('birthday')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i>Cập nhật
                                </button>
                            </div>
                        </form>
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
