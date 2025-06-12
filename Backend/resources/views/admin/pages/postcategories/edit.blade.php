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
            <div class="card-title">Thêm mới danh mục bài viết</div>
        </div>
        <div class="card-body">
            <form action="{{ route('post-categories.update', $category->slug) }}" method="post">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Tên danh mục:</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control"
                        placeholder="Nhập tên danh mục">
                    @error('name')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả:</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Nhập mô tả danh mục">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <a href="{{ route('post-categories.index') }}" class="btn btn-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>


        </div>
    </div>
@endsection
