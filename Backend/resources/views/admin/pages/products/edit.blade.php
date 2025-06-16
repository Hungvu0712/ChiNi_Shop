@extends('admin.layouts.master')
@section('title', 'Sửa sản phẩm')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoI+3U2HfQ7751dU7CkzGxW8rF8xCpiQm5Z5zr9EYk+L8Mb" crossorigin="anonymous">
    <style>
        .btn-close-custom {
            background-color: #dc3545;
            opacity: 0.8;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            color: white;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: 0.2s;
        }

        .btn-close-custom:hover {
            opacity: 1;
            transform: scale(1.1);
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Cập nhật sản phẩm</h5>
                </div>
                <div class="card-body">
                    {{-- Các trường input: name, price, category, brand, description, ảnh, số lượng, v.v. --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Giá</label>
                            <input type="number" step="0.01" name="price" class="form-control"
                                value="{{ $product->price }}" required>
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Danh mục (Category)</label>
                            <select name="category_id" class="form-select">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Thương hiệu (Brand)</label>
                            <select name="brand_id" class="form-select">
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" id="summernote" class="form-control" rows="4">{{ $product->description }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Ảnh chính</label>
                            <input type="file" name="product_image" id="product_image" class="form-control">
                            @error('product_image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            <div class="mt-2 position-relative d-inline-block" id="preview-thumbnail-wrapper">
                                <img id="preview-thumbnail" src="#" width="120" style="display:none;">
                                <button type="button" id="remove-preview-thumbnail"
                                    class="btn-close position-absolute top-0 end-0" style="display:none;"
                                    aria-label="Close"></button>
                            </div>

                            <div id="current-thumbnail-wrapper" class="position-relative d-inline-block mt-2">
                                @if ($product->product_image)
                                    <img src="{{ $product->product_image }}" width="120" id="current-thumbnail">
                                    <button type="button" id="remove-thumbnail"
                                        class="btn-close position-absolute top-0 end-0" aria-label="Close"></button>
                                @else
                                    <p>Chưa có ảnh đại diện</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ảnh đính kèm</label>
                            <input type="file" name="images[]" id="image-input" class="form-control mb-2" multiple>
                            @error('images')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            @error('images.*')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            <div id="preview-images" class="mt-2"></div>
                            <div id="current-images" class="mb-2">
                                @foreach ($product->attachments as $attachment)
                                    <div class="image-wrapper position-relative d-inline-block m-2">
                                        <img src="{{ $attachment->attachment_image }}" width="120">
                                        <button type="button" class="remove-image btn-close position-absolute top-0 end-0"
                                            aria-label="Close" data-id="{{ $attachment->id }}"></button>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="removed_attachments" id="removed_attachments">
                            @error('removed_attachments')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Trọng lượng</label>
                            <input type="text" name="weight" class="form-control" value="{{ $product->weight }}">
                            @error('weight')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Số lượng</label>
                            <input type="number" name="quantity" class="form-control"
                                value="{{ $product->quantity }}">
                            @error('quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cảnh báo tồn kho</label>
                            <input type="number" name="quantity_warning" class="form-control"
                                value="{{ $product->quantity_warning }}">
                            @error('quantity_warning')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tags (phân cách bởi dấu phẩy)</label>
                            <input type="text" name="tags" class="form-control" value="{{ $product->tags }}">
                            @error('tags')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control" value="{{ $product->sku }}">
                            @error('sku')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="hidden" name="active" value="0">
                        <input type="checkbox" class="form-check-input" name="active" id="active" value="1"
                            {{ $product->active ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Kích hoạt sản phẩm</label>
                        @error('active')
                            <br><small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">⬅ Quay lại</a>
                        <button type="submit" class="btn btn-success">Cập nhật sản phẩm</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        let removedIds = [];

        document.querySelectorAll('.remove-image').forEach(btn => {
            btn.addEventListener('click', () => {
                let id = btn.getAttribute('data-id');
                removedIds.push(id);
                document.getElementById('removed_attachments').value = removedIds.join(',');
                btn.parentElement.remove();
            });
        });

        document.getElementById('image-input').addEventListener('change', function() {
            let preview = document.getElementById('preview-images');
            preview.innerHTML = '';
            Array.from(this.files).forEach((file, index) => {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let wrapper = document.createElement('div');
                    wrapper.className = "position-relative d-inline-block m-2";
                    wrapper.innerHTML =
                        `<img src="${e.target.result}" width="120">
                        <button type="button" class="btn-close position-absolute top-0 end-0" aria-label="Close"></button>`;
                    preview.appendChild(wrapper);

                    wrapper.querySelector('.btn-close').onclick = () => {
                        wrapper.remove();
                        let dt = new DataTransfer();
                        Array.from(document.getElementById('image-input').files)
                            .filter((_, i) => i !== index)
                            .forEach(file => dt.items.add(file));
                        document.getElementById('image-input').files = dt.files;
                    };
                };
                reader.readAsDataURL(file);
            });
        });

        document.getElementById('product_image').addEventListener('change', function() {
            let input = this;
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.getElementById('preview-thumbnail');
                    preview.src = e.target.result;
                    preview.style.display = 'inline';
                    document.getElementById('remove-preview-thumbnail').style.display = 'inline';
                }
                reader.readAsDataURL(input.files[0]);
            }
        });

        document.getElementById('remove-preview-thumbnail').addEventListener('click', function() {
            document.getElementById('preview-thumbnail').src = '#';
            document.getElementById('preview-thumbnail').style.display = 'none';
            document.getElementById('product_image').value = '';
            this.style.display = 'none';
        });

        const removeCurrentThumb = document.getElementById('remove-thumbnail');
        if (removeCurrentThumb) {
            removeCurrentThumb.addEventListener('click', function() {
                const wrapper = document.getElementById('current-thumbnail-wrapper');
                wrapper.remove();
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 150
            });
        });
    </script>
@endsection
