@extends('client.layouts.master')
@section('title', 'Thông tin tài khoản')
@section('css')
    <style>

    </style>
@endsection
@section('content')
    <div class="container py-5" style="margin-top: 100px">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="mb-4">Thông tin tài khoản</h1>
            </div>
        </div>
        <div class="row">
            <!-- Profile Picture Column -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <div class="position-relative mb-3">
                            <img src="{{ Auth::user()->profile->avatar ?? 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}"
                                class="img-fluid rounded-circle" id="avatar-preview"
                                style="width: 250px; height: 250px; object-fit: cover; border: 5px solid #f8f9fa;">
                        </div>
                        <h3 class="mb-1">{{ $user->name }}</h3>
                        <p class="text-muted mb-3">{{ $user->email }}</p>

                        <div class="d-grid gap-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Cập nhật thông tin
                            </a>

                            <a href="{{ route('password.change') }}" class="btn btn-secondary">
                                <i class="fa-solid fa-key"></i> Đổi mật khẩu
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Information Column -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form id="profile-form" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                            method="post">
                            @csrf
                            @method('patch')

                            <h5 class="card-title border-bottom pb-3 mb-4">Thông tin cá nhân</h5>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label"><strong>Họ và tên</strong></label>
                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                                        disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><strong>Email</strong></label>
                                    <input type="text" name="email" class="form-control" value="{{ $user->email }}"
                                        disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><strong>Số điện thoại</strong></label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ optional($user->profile)->phone ?? 'Chưa cập nhật' }}" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><strong>Giới tính</strong></label>
                                    <input type="text" name="gender" class="form-control"
                                        value="{{ optional($user->profile)->gender ?? 'Chưa cập nhật' }}" disabled>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label"><strong>Ngày sinh</strong></label>
                                    <input type="date" name="birthday" class="form-control"
                                        value="{{ optional($user->profile)->birthday ? optional($user->profile)->birthday->format('Y-m-d') : 'Chưa cập nhật' }}"
                                        disabled>

                                </div>

                                <div class="col-12">
                                    <label class="form-label"><strong>Địa chỉ</strong></label>
                                    <input type="text" name="address" class="form-control"
                                        value="{{ optional($user->profile)->address ?? 'Chưa cập nhật' }}" disabled>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
