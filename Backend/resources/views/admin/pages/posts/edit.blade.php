@extends('admin.layouts.master')
@section('title', 'Thêm mới')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .note-modal .note-image-input {
            z-index: 2051 !important;
            position: relative;
        }

        .modal-backdrop.show {
            z-index: 1049 !important;
        }

        .note-modal {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="card" style="width: 100%">
        <div class="card-header">
            <div class="card-title">Chỉnh sửa bài viết</div>
        </div>
        <div class="card-body">
            <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Chọn danh mục --}}
                <div class="mb-3">
                    <label for="post_category_id" class="form-label">Danh mục bài viết:</label>
                    <select name="post_category_id" class="form-select">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($postCategories as $category)
                            <option value="{{ $category->id }}" {{ old('post_category_id', $post->post_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('post_category_id')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tiêu đề --}}
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu đề:</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}"
                        placeholder="Nhập tiêu đề bài viết">
                    @error('title')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tóm tắt --}}
                <div class="mb-3">
                    <label for="excerpt" class="form-label">Tóm tắt:</label>
                    <input type="text" name="excerpt" class="form-control" value="{{ old('excerpt', $post->excerpt) }}"
                        placeholder="Nhập đoạn tóm tắt">
                    @error('excerpt')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nội dung --}}
                <div class="mb-3">
                    <label for="content" class="form-label">Nội dung:</label>
                    <textarea name="content" id="summernote" class="form-control" rows="6">
        {!! old('content', $post->content) !!}
    </textarea>

                    @error('content')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ảnh đại diện --}}
                <div class="mb-3">
                    <label for="featured_image" class="form-label">Ảnh đại diện:</label>
                    @if ($post->featured_image)
                        <div class="mb-2">
                            <img src="{{ $post->featured_image }}" alt="Ảnh hiện tại" width="200">
                        </div>
                    @endif
                    <input type="file" name="featured_image" class="form-control">
                    @error('featured_image')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Trạng thái --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái:</label>
                    <select name="status" class="form-select">
                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Nháp
                        </option>
                        <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Công
                            khai</option>
                    </select>
                    @error('status')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('posts.index') }}" onclick="return confirm('Bạn chắc chắn muốn quay lại?')"
                        class="btn btn-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#summernote').summernote({
                height: 300,
                placeholder: 'Nhập nội dung bài viết...',
                callbacks: {
                    onImageUpload: function (files) {
                        uploadImage(files[0]);
                    }
                }
            });

            function uploadImage(file) {
                let data = new FormData();
                data.append("file", file);

                $.ajax({
                    url: "{{ route('admin.summernote.upload') }}",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    method: "POST",
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function (url) {
                        $('#summernote').summernote('insertImage', url, function ($image) {
                            $image.css('width', '50%');
                        });
                    },
                    error: function (xhr) {
                        alert("Tải ảnh lên thất bại!");
                    }
                });
            }
        });
    </script>
@endsection