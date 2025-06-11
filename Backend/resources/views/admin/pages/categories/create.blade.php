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
    <div class="card" style="width: 100%">
        <div class="card-header">
            <div class="card-title">Tạo Category</div>
        </div>
        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Tên category:</label><br>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control mb-2" placeholder="Mời nhập tên category">
                    @error('name')
                        <div style="color: red">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="" class="form-lable">Mô tả: </label>
                    <textarea name="description" id="summernote" cols="30" rows="10" class="form-control"></textarea>
                </div>

                <input type="submit" value="Submit" class="btn btn-primary mt-2">
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#summernote').summernote(
                {
                    height: 300
                }
            );
        });
    </script>
@endsection
