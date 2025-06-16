@extends('admin.layouts.master')
@section('title', 'Sửa đổi')
@section('content')
    @can('role-assign')
        <div class="container py-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h2 class="h5 mb-0">Gán vai trò cho: <strong>{{ $user->name }}</strong></h2>
                </div>

                <div class="card-body">
                    <form action="{{ route('users.roles.update', $user) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="mb-4">
                            <h3 class="h6 mb-3 text-muted">Chọn vai trò:</h3>
                            <div class="row row-cols-1 row-cols-md-2 g-3">
                                @foreach ($roles as $role)
                                    <div class="col">
                                        <div class="form-check card p-3 border">
                                            <input class="form-check-input" type="checkbox" name="roles[]"
                                                value="{{ $role->name }}"
                                                {{ in_array($role->name, $userRoles) ? 'checked' : '' }}
                                                id="role_{{ $role->id }}">
                                            <label class="form-check-label fw-medium d-flex align-items-center"
                                                for="role_{{ $role->id }}">
                                                <span class="badge bg-primary me-2">{{ $loop->iteration }}</span>
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Quay lại
                            </a>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-check-circle me-1"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection
