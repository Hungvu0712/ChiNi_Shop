@extends('admin.layouts.master')
@section('title', 'Sửa sản phẩm')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
    <style>
        .note-icon-caret:before {
            content: "" !important;
        }

        .preview-container {
            position: relative;
            display: inline-block;
            margin-right: 10px;
        }

        .preview-container img {
            border: 1px solid #ddd;
            padding: 4px;
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .preview-container img:hover {
            transform: scale(1.02);
        }

        .remove-btn {
            position: absolute;
            top: -6px;
            right: -6px;
            background-color: #dc3545;
            /* Bootstrap red */
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 14px;
            font-weight: bold;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            transition: background-color 0.2s ease, transform 0.2s ease;
            z-index: 10;
        }

        .remove-btn:hover {
            background-color: #bb2d3b;
            transform: scale(1.1);
        }


        .remove-btn:hover {
            background-color: #c82333;
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">✏️ Chỉnh sửa sản phẩm</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Tên sản phẩm --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}"
                            required>
                    </div>

                    <div class="row">
                        {{-- Danh mục --}}
                        <div class="mb-3 col-md-6">
                            <label for="category_id" class="form-label fw-semibold">Danh mục</label>
                            <select name="category_id" class="form-select">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Thương hiệu --}}
                        <div class="mb-3 col-md-6">
                            <label for="brand_id" class="form-label fw-semibold">Thương hiệu</label>
                            <select name="brand_id" class="form-select">
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Giá --}}
                    <div class="mb-3">
                        <label for="price" class="form-label fw-semibold">Giá (VNĐ)</label>
                        <input type="number" name="price" class="form-control"
                            value="{{ old('price', $product->price) }}" required>
                    </div>

                    {{-- Mô tả --}}
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Mô tả</label>
                        <textarea id="summernote" name="description" class="form-control summernote">{{ old('description', $product->description) }}</textarea>
                    </div>

                    {{-- Ảnh sản phẩm --}}
                    <div class="row">
                        {{-- Ảnh chính --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ảnh chính</label>
                            <input type="file" name="product_image" id="product_image" class="form-control">
                            <div id="mainImageWrapper" class="mt-2"
                                style="{{ $product->product_image ? '' : 'display:none;' }}">
                                <div class="preview-container position-relative d-inline-block">
                                    <img id="mainImagePreview" src="{{ $product->product_image }}" class="img-thumbnail"
                                        style="max-height: 200px;">
                                    <button type="button" class="remove-btn" onclick="removeMainImage()">×</button>
                                </div>

                            </div>
                        </div>

                        {{-- Ảnh đính kèm --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ảnh đính kèm</label>
                            <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                            <div id="attachmentsPreview" class="mt-2 d-flex flex-wrap gap-2">
                                @if (!empty($product->attachments))
                                    @foreach ($product->attachments as $attachment)
                                        <div class="preview-container position-relative">
                                            <img src="{{ $attachment->attachment_image }}" class="img-thumbnail"
                                                style="max-height: 120px;">
                                            <form action="{{ route('product-attachments.destroy', $attachment->id) }}"
                                                method="POST" class="position-absolute top-0 end-0"
                                                onsubmit="return confirm('Bạn có chắc muốn xoá ảnh này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="remove-btn">×</button>
                                            </form>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        {{-- Trọng lượng --}}
                        <div class="mb-3 col-md-4">
                            <label for="weight" class="form-label fw-semibold">Trọng lượng</label>
                            <input type="text" name="weight" class="form-control"
                                value="{{ old('weight', $product->weight) }}">
                        </div>

                        {{-- Số lượng --}}
                        <div class="mb-3 col-md-4">
                            <label for="quantity" class="form-label fw-semibold">Số lượng</label>
                            <input type="number" name="quantity" class="form-control"
                                value="{{ old('quantity', $product->quantity) }}">
                        </div>

                        {{-- Cảnh báo tồn kho --}}
                        <div class="mb-3 col-md-4">
                            <label for="quantity_warning" class="form-label fw-semibold">Cảnh báo tồn kho</label>
                            <input type="number" name="quantity_warning" class="form-control"
                                value="{{ old('quantity_warning', $product->quantity_warning) }}">
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="mb-3">
                        <label for="tags" class="form-label fw-semibold">Từ khóa (tags, cách nhau bởi dấu
                            phẩy)</label>
                        <input type="text" name="tags" class="form-control"
                            value="{{ old('tags', $product->tags) }}">
                    </div>

                    {{-- SKU --}}
                    <div class="mb-3">
                        <label for="sku" class="form-label fw-semibold">SKU (Mã sản phẩm)</label>
                        <input type="text" name="sku" class="form-control"
                            value="{{ old('sku', $product->sku) }}">
                    </div>

                    {{-- Trạng thái --}}
                    <div class="form-check mb-3">
                        <input type="checkbox" name="active" class="form-check-input" id="active" value="1"
                            {{ old('active', $product->active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="active">Hiển thị sản phẩm</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">⬅ Quay lại</a>
                        <button type="submit" class="btn btn-success">💾 Cập nhật sản phẩm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const productImageInput = document.getElementById('product_image');
        const mainImagePreview = document.getElementById('mainImagePreview');
        const mainImageWrapper = document.getElementById('mainImageWrapper');

        productImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (mainImageWrapper && mainImagePreview && mainImagePreview.tagName === 'IMG') {
                    mainImagePreview.src = URL.createObjectURL(file);
                    mainImageWrapper.style.display = 'block';
                }
            } else {
                mainImageWrapper.style.display = 'none';
            }
        });

        function removeMainImage() {
            productImageInput.value = '';
            mainImagePreview.src = '#';
            mainImageWrapper.style.display = 'none';
        }

        const attachmentsInput = document.getElementById('attachments');
        const attachmentsPreview = document.getElementById('attachmentsPreview');

        attachmentsInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            attachmentsPreview.innerHTML = '';

            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.createElement('div');
                    container.classList.add('preview-container');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.maxHeight = '120px';

                    const btn = document.createElement('button');
                    btn.className = 'remove-btn';
                    btn.innerHTML = '&times;';
                    btn.onclick = function() {
                        const dt = new DataTransfer();
                        const oldFiles = Array.from(attachmentsInput.files);
                        oldFiles.forEach((f, i) => {
                            if (i !== index) dt.items.add(f);
                        });
                        attachmentsInput.files = dt.files;
                        container.remove();
                    };

                    container.appendChild(img);
                    container.appendChild(btn);
                    attachmentsPreview.appendChild(container);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 150
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    console.log('🟢 Form đã được submit!');
                    alert('Form đã gửi thành công!');
                });
            } else {
                console.warn('⚠️ Không tìm thấy form để bind submit!');
            }
        });
    </script>
@endsection
