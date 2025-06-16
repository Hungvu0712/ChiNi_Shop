@extends('admin.layouts.master')
@section('title', 'Thêm mới')
@section('css')

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
    <style>
        .note-icon-caret:before {
            content: "" !important;
        }
    </style>
@endsection
@section('content')
    @can('brand-edit')
        <div class="card" style="width: 100%">
            <div class="card-header">
                <div class="card-title">Sửa Brand</div>
            </div>
            <div class="card-body">
                <form action="{{ route('brands.update', $brand->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên brand:</label><br>
                        <input type="text" name="name" value="{{ $brand->name }}" class="form-control mb-2"
                            placeholder="Mời nhập tên brand">
                            @error('name')
                        <div style="color: red">
                            {{ $message }}
                        </div>
                    @enderror
                    </div>

                    <div class="mb-3">
                        <label for="" class="form-lable">Ảnh đại diện: </label>
                        <img src="{{ $brand->brand_image }}" alt="" width="100px" height="100px">
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

@section('script')
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 300
            });
        });
    </script>
@endsection
