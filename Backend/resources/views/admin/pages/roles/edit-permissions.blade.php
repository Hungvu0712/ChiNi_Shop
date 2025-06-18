@extends('admin.layouts.master')
@section('title', 'Sửa đổi')
@section('content')
    @can('role-assign')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Gán quyền cho vai trò: {{ $role->name }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('roles.updatePermissions', $role->id) }}" method="POST">
                    @csrf
                    @method('put')

                    <div class="row">
                        @foreach ($permissions as $permission)
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                        value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                        {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endcan
@endsection
