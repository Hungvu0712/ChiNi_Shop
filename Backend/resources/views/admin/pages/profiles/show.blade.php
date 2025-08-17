@extends('admin.layouts.master')
@section('title', 'Danh sách')
@section('css')

<style>
    .profile-header {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: white;
    }

    .profile-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .stat-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .nav-pills .nav-link.active {
        background-color: #2575fc;
    }

    .detail-item {
        border-bottom: 1px solid #eee;
        padding: 15px 0;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .badge-role {
        font-size: 0.8rem;
        padding: 5px 10px;
        margin-right: 5px;
        margin-bottom: 5px;
    }
</style>

@endsection
@section('content')
@can('profile-show')
<div class="container-fluid">
    <!-- Header -->
    <div class="row profile-header py-4 mb-4">
        <div class="col-12">
            <div class="d-flex flex-column align-items-center text-center">
                <img src="{{ $user->profile->avatar ?? 'https://cdn-icons-png.flaticon.com/512/149/149071.png' }}"
                    alt="Profile Avatar" class="rounded-circle border border-3 border-primary p-1" id="avatar-preview"
                    style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;"
                    onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                <h1 class="h3 mb-1">{{ $user->name }}</h1>
                <div class="d-flex">
                    @if($user->is_active)
                    <span class="badge bg-success">
                        <i class="bi bi-shield-check me-1"></i> Hoạt động
                    </span>
                    @else
                    <span class="badge bg-danger">
                        <i class="bi bi-shield-x me-1"></i> Đã bị khóa
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card stat-card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Tổng đơn hàng</h5>
                    <h2 class="mb-0">{{ $totalOrders }}</h2>
                </div>
            </div>

            <div class="card stat-card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title text-muted">Tổng chi tiêu</h5>
                    <h2 class="mb-0">{{ number_format($totalSpent, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card mb-4">
                <div class="card-body">
                    <ul class="nav nav-pills mb-4" id="profileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="pill"
                                data-bs-target="#overview" type="button" role="tab">Tổng quan</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="activity-tab" data-bs-toggle="pill" data-bs-target="#activity"
                                type="button" role="tab">Hoạt động</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="profileTabContent">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            <div class="detail-item">
                                <h5 class="mb-3">Thông tin cá nhân</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Giới tính:</strong> {{ $user->profile?->gender ?? 'Chưa cập nhật' }}
                                        </p>
                                        <p><strong>Ngày sinh:</strong>
                                            {{ $user->profile?->birthday ?? 'Chưa cập nhật' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Địa chỉ:</strong> {{ $user->profile?->address ?? 'Chưa cập nhật' }}
                                        </p>
                                        <p><strong>Số điện thoại:</strong>
                                            {{ $user->profile?->phone ?? 'Chưa cập nhật' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-item">
                                <h5 class="mb-3">Vai trò & Quyền hạn</h5>
                                <div>
                                    @if ($user->hasRole('admin'))
                                    <span class="badge bg-danger badge-role">Admin</span>
                                    @endif
                                    @if ($user->hasRole('staff'))
                                    <span class="badge bg-info text-dark badge-role">Nhân viên</span>
                                    @endif
                                    @if ($user->hasRole('user'))
                                    <span class="badge bg-secondary badge-role">Khách hàng</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Activity Tab -->
                        <div class="tab-pane fade" id="activity" role="tabpanel">
                            <h5 class="mb-4">Lịch sử hoạt động</h5>
                            <div class="table-responsive">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection
@section('script')
@endsection
