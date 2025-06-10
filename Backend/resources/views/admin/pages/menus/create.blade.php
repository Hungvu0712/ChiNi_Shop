@extends('admin.layouts.master')
@section('title', 'Thêm mới')
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
            <div class="card-title">Tạo menu</div>
        </div>
        <div class="card-body">
            <form action="{{ route('menus.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Tên menu:</label><br>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control mb-2" placeholder="Nhập tên menu">
                    @error('name')
                        <div style="color: red">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="prant_id" class="form-label">Menu cha (nếu có):</label>
                    <select name="prant_id" class="form-control">
                        <option value="">-- Không có --</option>
                        @foreach ($menus as $menu)
                            <option value="{{ $menu->id }}" {{ old('prant_id') == $menu->id ? 'selected' : '' }}>
                                {{ $menu->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('prant_id')
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
