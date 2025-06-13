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
    @can('brand-create')
        <div class="card" style="width: 100%">
            <div class="card-header">
                <div class="card-title">Tạo Brand</div>
            </div>
            <div class="card-body">
                <form action="{{ route('brands.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên brand:</label><br>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control mb-2"
                            placeholder="Mời nhập tên brand">
                        @error('name')
                        <div style="color: red">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-lable">Ảnh đại diện: </label>
                        <input type="file" name="brand_image" id="" class="form-control">
                        @error('brand_image')
                        <div style="color: red">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <input type="submit" value="Submit" class="btn btn-primary mt-2">
                </form>
            </div>
        </div>
    @endcan
@endsection
