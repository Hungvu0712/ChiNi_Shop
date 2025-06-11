@extends('admin.layouts.master')
@section('title', 'Thêm mới')
@section('css')
<style>
    .note-icon-caret:before {
        content: "" !important;
    }

    .preview-container {
        position: relative;
        display: inline-block;
    }

    .remove-btn {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 10;
        background: rgba(255, 0, 0, 0.8);
        border: none;
        color: white;
        font-weight: bold;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        line-height: 20px;
        text-align: center;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="container my-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Tạo sản phẩm</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Danh mục</label>
                        <input type="number" name="category_id" class="form-control" value="{{ old('category_id') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Thương hiệu</label>
                        <input type="number" name="brand_id" class="form-control" value="{{ old('brand_id') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Giá</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Ảnh chính</label>
                        <input type="file" name="product_image" id="product_image" class="form-control" accept="image/*">
                        <div id="mainImageWrapper" class="mt-2" style="display:none;">
                            <div class="preview-container">
                                <img id="mainImagePreview" src="#" alt="Ảnh chính" class="img-thumbnail" style="max-height: 200px;">
                                <button type="button" class="remove-btn" onclick="removeMainImage()">&times;</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Ảnh đính kèm</label>
                        <input type="file" name="attachments[]" id="attachments" class="form-control" multiple accept="image/*">
                        <div id="attachmentsPreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Trọng lượng</label>
                        <input type="text" name="weight" class="form-control" value="{{ old('weight') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Số lượng</label>
                        <input type="number" name="quantity" class="form-control" value="{{ old('quantity') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Cảnh báo tồn kho</label>
                        <input type="number" name="quantity_warning" class="form-control" value="{{ old('quantity_warning') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tags (phân cách bởi dấu phẩy)</label>
                        <input type="text" name="tags" class="form-control" value="{{ old('tags') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
                    </div>

                    <div class="col-md-12 form-check">
                        <input type="checkbox" name="active" class="form-check-input" id="active" checked>
                        <label class="form-check-label" for="active">Hiển thị sản phẩm</label>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Tạo sản phẩm</button>
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

    productImageInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            mainImagePreview.src = URL.createObjectURL(file);
            mainImageWrapper.style.display = 'block';
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

    attachmentsInput.addEventListener('change', function (e) {
        const files = Array.from(e.target.files);
        attachmentsPreview.innerHTML = '';

        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const container = document.createElement('div');
                container.classList.add('preview-container');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('img-thumbnail');
                img.style.maxHeight = '120px';

                const btn = document.createElement('button');
                btn.className = 'remove-btn';
                btn.innerHTML = '&times;';
                btn.onclick = function () {
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
@endsection
