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

    .profile-container {
        max-width: 1200px;
        margin: 6rem auto 2rem;
        padding: 0 1rem;
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

    .btn-danger {
        background-color: var(--primary-color);
        color: white;
        border: none;
    }

    .btn-danger:hover {
        background-color: var(--primary-hover);
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <div class="container mt-4">
        <h5><strong>Địa chỉ của tôi</strong></h5>

        <!-- Nút Thêm địa chỉ mới -->
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addressModal">
            + Thêm địa chỉ mới
        </button>

        @if ($errors->any() && session('from') === 'add')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                    new bootstrap.Modal(document.getElementById('addressModal')).show();
                });
        </script>
        @endif

        @if ($errors->any() && session('from') === 'edit')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                    new bootstrap.Modal(document.getElementById('editModal')).show();
                });
        </script>
        @endif

        <!-- Modal thêm địa chỉ -->
        <div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('add-address') }}" method="POST" class="modal-content p-3">
                    <input type="hidden" name="to_district_id" id="to_district_id">
                    <input type="hidden" name="to_ward_code" id="to_ward_code">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Địa chỉ mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                name="fullname" placeholder="Họ và tên" value="{{ old('fullname') }}">
                            @error('fullname')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                                placeholder="Số điện thoại" value="{{ old('phone') }}">
                            @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <select id="province" class="form-select">
                                    <option disabled selected>Tỉnh/Thành phố</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="district" class="form-select" disabled>
                                    <option disabled selected>Quận/Huyện</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="ward" class="form-select" disabled>
                                    <option disabled selected>Phường/Xã</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="fullAddress" name="address"
                            class="form-control @error('address') is-invalid @enderror" readonly
                            value="{{ old('address') }}">
                        @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
                        <div class="mb-2 mt-2">
                            <input type="text" class="form-control @error('specific_address') is-invalid @enderror"
                                name="specific_address" placeholder="Địa chỉ cụ thể"
                                value="{{ old('specific_address') }}">
                            @error('specific_address')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-check mb-3 mt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="is_default" name="is_default">

                            <label class="form-check-label" for="s_default">Đặt làm địa chỉ mặc định</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button class="btn btn-danger" type="submit">Hoàn thành</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal sửa thông tin -->
        <div class="modal fade" id="editModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" id="editForm" class="modal-content p-3">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật địa chỉ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="fullname" id="edit-fullname"
                            class="form-control mb-2 @error('fullname') is-invalid @enderror" placeholder="Họ và tên"
                            value="{{ old('fullname') }}">
                        @error('fullname')<div class="text-danger small">{{ $message }}</div>@enderror
                        <input type="text" name="phone" id="edit-phone"
                            class="form-control mb-2 @error('phone') is-invalid @enderror" placeholder="SĐT"
                            value="{{ old('phone') }}">
                        @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
                        <input type="text" name="address" id="edit-address"
                            class="form-control mb-2 @error('address') is-invalid @enderror" placeholder="Địa chỉ"
                            value="{{ old('address') }}">
                        @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
                        <input type="text" name="specific_address" id="edit-specific"
                            class="form-control mb-2 @error('specific_address') is-invalid @enderror"
                            placeholder="Địa chỉ cụ thể" value="{{ old('specific_address') }}">
                        @error('specific_address')<div class="text-danger small">{{ $message }}</div>@enderror
                        <div class="form-check mb-3 mt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="edit-is-default"
                                name="is_default" {{ old('is_default', $item->is_default ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="edit-is-default">
                                Đặt làm địa chỉ mặc định
                            </label>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danh sách địa chỉ -->
        <div class="row mb-4">
            @foreach ($address as $item)
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">
                                <strong>{{ $item->fullname }}</strong>
                            </h5>
                            @if ($item->is_default)
                            <span class="badge bg-primary">Mặc định</span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone-alt text-muted me-2"></i>
                            <span>{{ $item->phone }}</span>
                        </div>

                        <div class="address-details mb-3">
                            <div class="d-flex mb-1">
                                <i class="fas fa-map-marker-alt text-muted me-2 mt-1"></i>
                                <div>
                                    <div>{{ $item->specific_address }}</div>
                                    <div class="text-muted">{{ $item->address }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-outline-primary btn-sm btn-edit-address" data-bs-toggle="modal"
                                data-bs-target="#editModal" data-id="{{ $item->id }}"
                                data-fullname="{{ $item->fullname }}" data-phone="{{ $item->phone }}"
                                data-address="{{ $item->address }}" data-specific="{{ $item->specific_address }}"
                                data-default="{{ $item->is_default }}">
                                <i class="fas fa-edit me-1"></i> Cập nhật
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script src="{{ asset('address/addressghn.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-edit-address').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const fullname = this.dataset.fullname;
                const phone = this.dataset.phone;
                const address = this.dataset.address;
                const specific = this.dataset.specific;
                const isDefault = this.dataset.default === "1";

                document.getElementById('edit-fullname').value = fullname;
                document.getElementById('edit-phone').value = phone;
                document.getElementById('edit-address').value = address;
                document.getElementById('edit-specific').value = specific;
                document.getElementById('edit-is-default').checked = isDefault;
                document.getElementById('editForm').action = `/update-address/${id}`;
            });
        });
    });
</script>
@endsection