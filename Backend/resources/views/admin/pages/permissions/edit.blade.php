@extends('admin.layouts.master')
@section('title', 'Sửa đổi')
@section('css')
    <style>
        .note-icon-caret:before {
            content: "" !important;
        }
    </style>
@endsection
@section('content')
    <div class="card" style="width: 100%">
        <div class="card-header">
            <div class="card-title">Sửa thông tin permission - <strong style="color: rgb(221, 110, 110)">{{ $permissionID->name }}</strong></div>
        </div>
        <div class="card-body">
            <form action="{{ route('permissions.update', $permissionID->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="mb-3">
                    <label for="name" class="form-label">Tên vai trò:</label><br>
                    <input type="text" name="name" value="{{ $permissionID->name }}" class="form-control mb-2" placeholder="Mời nhập tên vai trò">
                    @error('name')
                        <div style="color: red">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <input type="submit" value="Submit" class="btn btn-primary mt-2">
            </form>
        </div>
    </div>
@endsection

