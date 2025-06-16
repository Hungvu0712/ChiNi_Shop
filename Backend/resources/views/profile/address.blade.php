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
        <div class="container mt-4">
            <h5><strong>Địa chỉ của tôi</strong></h5>

            <!-- Nút Thêm địa chỉ mới -->
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addressModal">
                    + Thêm địa chỉ mới
                </button>
            <div class="d-flex justify-content-between align-items-center my-3">
                <h6><strong>Địa chỉ</strong></h6>
                <!-- Button trigger modal -->

                <form action="{{ route('add-address') }}" method="post">
                    @csrf
                    @method('post')
                    <!-- Modal địa chỉ -->
                    <div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content p-3">
                                <div class="modal-header">
                                    <h5 class="modal-title">Địa chỉ mới</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="fullname" placeholder="Họ và tên">
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="phone"
                                            placeholder="Số điện thoại">
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

                                    <div class="mb-2">
                                        <input type="hidden" id="fullAddress" name="address" class="form-control"
                                            placeholder="Tinh/Than Pho,Quan / Huyen, Phuong/ Xa" readonly>
                                    </div>

                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="specific_address"
                                            placeholder="Dia chi cu the">
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Trở lại</button>
                                    <button class="btn btn-danger" type="submit">Hoàn thành</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>



            </div>

            

            <!-- Địa chỉ 1 -->
            @foreach ($address as $item)
                <div class="border-bottom pb-3 mb-3">
                <div><strong>{{$item->fullname}}</strong> | {{$item->phone}}</div>
                <div>{{$item->specific_address}}<br>{{$item->address}}</div>
                <div class="mt-2 d-flex align-items-center gap-2">
                    <span class="badge bg-danger">Mặc định</span>
                    <a href="#" class="text-primary text-decoration-none ms-auto">Cập nhật</a>
                </div>
            </div>
            @endforeach
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

        <script src="{{ asset('address/address.js') }}"></script>

    @endsection
