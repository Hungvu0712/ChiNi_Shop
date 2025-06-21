@extends('admin.layouts.master')
@section('title', 'Chỉnh sửa sản phẩm')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
    <style>
        .form-section {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
        }

        .preview-container {
            position: relative;
            display: inline-block;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .remove-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            z-index: 10;
            background: #dc3545;
            border: 2px solid white;
            color: white;
            font-weight: bold;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            line-height: 24px;
            text-align: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.2s;
        }

        .remove-btn:hover {
            background: #c82333;
            transform: scale(1.1);
        }

        .variant-block {
            background-color: #f8f9fa;
            border-left: 4px solid #6c757d;
            transition: all 0.3s;
        }

        .variant-block:hover {
            background-color: #f1f1f1;
            border-left-color: #0d6efd;
        }

        .attribute-checkbox {
            margin-right: 15px;
            margin-bottom: 10px;
        }

        .attribute-checkbox .form-check-input {
            margin-top: 0.25rem;
        }

        .tagify {
            border-radius: 6px;
            padding: 8px;
        }

        .tagify--focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .note-editor.note-frame {
            border-radius: 6px;
        }

        .current-image {
            transition: all 0.3s;
        }

        .current-image:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary bg-gradient text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Chỉnh sửa sản phẩm</h4>
                            <a href="{{ route('products.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data"
                            method="POST">
                            @csrf
                            @method('put')

                            <!-- Basic Information Section -->
                            <div class="form-section">
                                <h5 class="form-section-title"><i class="fas fa-info-circle me-2"></i>Thông tin cơ bản</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tên sản phẩm <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" name="name" type="text"
                                            value="{{ old('name', $product->name) }}" />
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="price">Giá <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" name="price" step="0.01" type="number"
                                                value="{{ old('price', $product->price) }}" />
                                            <span class="input-group-text">VNĐ</span>
                                        </div>
                                        @error('price')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="category_id">Danh mục</label>
                                        <select class="form-select" name="category_id">
                                            <option value="">-- Chọn danh mục --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold" for="brand_id">Thương hiệu</label>
                                        <select class="form-select" name="brand_id">
                                            <option value="">-- Chọn thương hiệu --</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('brand_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Mô tả sản phẩm</label>
                                        <textarea class="form-control" id="summernote" name="description" rows="10">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Images Section -->
                            <div class="form-section">
                                <h5 class="form-section-title"><i class="fas fa-images me-2"></i>Hình ảnh sản phẩm</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Ảnh chính</label>
                                        <input class="form-control" id="product_image" name="product_image"
                                            type="file" />

                                        @if ($product->product_image)
                                            <div class="mt-3">
                                                <p class="text-muted small mb-2">Ảnh hiện tại:</p>
                                                <div class="preview-container">
                                                    <img src="{{ $product->product_image }}"
                                                        class="img-thumbnail current-image" width="150"
                                                        id="current-thumbnail">
                                                    <button type="button" class="remove-btn" id="remove-thumbnail"
                                                        title="Xóa ảnh">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="mt-3" id="mainImageWrapper" style="display:none;">
                                            <p class="text-muted small mb-2">Ảnh mới:</p>
                                            <div class="preview-container">
                                                <img alt="Ảnh chính" class="img-thumbnail" id="mainImagePreview"
                                                    src="#" width="150" />
                                                <button class="remove-btn" onclick="removeMainImage()" type="button"
                                                    title="Xóa ảnh">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>

                                        @error('product_image')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Ảnh đính kèm</label>
                                        <input class="form-control" id="attachments" name="attachments[]" type="file"
                                            multiple />

                                        <div class="mt-3">
                                            @if ($product->attachments->count() > 0)
                                                <p class="text-muted small mb-2">Ảnh hiện tại:</p>
                                                <div class="d-flex flex-wrap gap-3" id="current-images">
                                                    @foreach ($product->attachments as $attachment)
                                                        <div class="preview-container">
                                                            <img src="{{ $attachment->attachment_image }}"
                                                                class="img-thumbnail current-image" width="120">
                                                            <button type="button" class="remove-btn remove-image"
                                                                data-id="{{ $attachment->id }}" title="Xóa ảnh">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <div class="mt-3 d-flex flex-wrap gap-3" id="attachmentsPreview"></div>
                                        </div>

                                        <input type="hidden" name="removed_attachments" id="removed_attachments">
                                        @error('attachments.*')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Inventory Section -->
                            <div class="form-section">
                                <h5 class="form-section-title"><i class="fas fa-boxes me-2"></i>Thông tin kho hàng</h5>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Trọng lượng (gram)</label>
                                        <div class="input-group">
                                            <input class="form-control" name="weight" type="text"
                                                value="{{ old('weight', $product->weight) }}" />
                                            <span class="input-group-text">g</span>
                                        </div>
                                        @error('weight')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Số lượng</label>
                                        <input class="form-control" name="quantity" type="number"
                                            value="{{ old('quantity', $product->quantity) }}" />
                                        @error('quantity')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Cảnh báo tồn kho</label>
                                        <input class="form-control" name="quantity_warning" type="number"
                                            value="{{ old('quantity_warning', $product->quantity_warning) }}" />
                                        @error('quantity_warning')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">SKU</label>
                                        <input class="form-control" name="sku" type="text"
                                            value="{{ old('sku', $product->sku) }}" />
                                        @error('sku')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Tags & Status Section -->
                            <div class="form-section">
                                <h5 class="form-section-title"><i class="fas fa-tags me-2"></i>Tags & Trạng thái</h5>
                                <div class="row g-3">
                                    <div class="col-md-9">
                                        <label class="form-label fw-semibold">Tags (phân cách bằng dấu phẩy)</label>
                                        <input id="tag-input" name="tags" class="form-control"
                                            value="{{ old('tags', $product->tags) }}">
                                        @error('tags')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold d-block">Trạng thái</label>
                                        <div class="form-check form-switch">
                                            <input name="active" type="hidden" value="0" />
                                            <input class="form-check-input" id="activeCheck" name="active"
                                                type="checkbox" value="1"
                                                {{ old('active', $product->active) ? 'checked' : '' }}
                                                style="width: 3em; height: 1.5em;">
                                            <label class="form-check-label" for="activeCheck">Kích hoạt</label>
                                        </div>
                                        @error('active')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Attributes Section -->
                            <div class="form-section">
                                <h5 class="form-section-title"><i class="fas fa-list-ul me-2"></i>Thuộc tính sản phẩm</h5>
                                @foreach ($attributes as $attribute)
                                    <div class="mb-4">
                                        <label class="fw-bold d-block mb-2">{{ $attribute->name }}</label>
                                        <div class="d-flex flex-wrap">
                                            @foreach ($attribute->attributeValues as $value)
                                                <div class="form-check attribute-checkbox">
                                                    <input type="checkbox" class="form-check-input attr-checkbox"
                                                        name="attributes[{{ $attribute->id }}][]"
                                                        data-attr-name="{{ $attribute->name }}"
                                                        data-attr-id="{{ $attribute->id }}" value="{{ $value->id }}"
                                                        id="attr_{{ $attribute->id }}_{{ $value->id }}"
                                                        {{ in_array($value->id, $selectedValueIds ?? []) ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="attr_{{ $attribute->id }}_{{ $value->id }}">
                                                        {{ $value->value }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Variants Section -->
                            <div class="form-section">
                                <h5 class="form-section-title"><i class="fas fa-random me-2"></i>Biến thể sản phẩm</h5>
                                <div id="variant-list">


                                </div>
                                <input type="hidden" name="variants_json" id="variants_json"
                                    value="{{ $product->variants_json }}">
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between mt-4">
                                <a class="btn btn-secondary" href="{{ route('products.index') }}">
                                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                                </a>
                                <button class="btn btn-success" type="submit">
                                    <i class="fas fa-save me-1"></i> Cập nhật sản phẩm
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>

    <script>
        // Initialize Summernote
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 250,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });

        // Image Upload Handling
        const productImageInput = document.getElementById('product_image');
        const mainImagePreview = document.getElementById('mainImagePreview');
        const mainImageWrapper = document.getElementById('mainImageWrapper');
        const currentThumbnail = document.getElementById('current-thumbnail');
        const removeThumbnailBtn = document.getElementById('remove-thumbnail');

        productImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    mainImagePreview.src = e.target.result;
                    mainImageWrapper.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        function removeMainImage() {
            productImageInput.value = '';
            mainImagePreview.src = '#';
            mainImageWrapper.style.display = 'none';
        }

        if (removeThumbnailBtn) {
            removeThumbnailBtn.addEventListener('click', function() {
                if (currentThumbnail) {
                    currentThumbnail.style.display = 'none';
                }
                this.style.display = 'none';
                productImageInput.value = '';
            });
        }

        // Attachments Handling
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
                    img.style.width = '120px';
                    img.style.height = '120px';
                    img.style.objectFit = 'cover';

                    const btn = document.createElement('button');
                    btn.className = 'remove-btn';
                    btn.innerHTML = '<i class="fas fa-times"></i>';
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

        // Tagify Initialization
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.querySelector('#tag-input');
            if (input) {
                new Tagify(input, {
                    enforceWhitelist: false,
                    dropdown: {
                        enabled: 0
                    },
                    originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
                });
            }
        });



        // Handle removal of existing attachments
        let removedAttachmentIds = [];
        document.querySelectorAll('.remove-image').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                if (id) {
                    removedAttachmentIds.push(id);
                    document.getElementById('removed_attachments').value = removedAttachmentIds.join(',');
                    this.closest('.preview-container').remove();
                }
            });
        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const variantList = document.getElementById('variant-list');
        const variantsJsonInput = document.getElementById('variants_json');
        const oldVariants = JSON.parse(variantsJsonInput.value || '[]');

        function generateCombinations(attrMap) {
            const keys = Object.keys(attrMap);
            if (keys.length < 2) return [];

            const cartesian = arr => arr.reduce((a, b) =>
                a.flatMap(d => b.map(e => [...d, e])), [[]]);

            const valuesArray = keys.map(k => attrMap[k]);
            return cartesian(valuesArray).map(comb => {
                const label = comb.map(v => v.name).join(' / ');
                const ids = comb.map(v => v.id).sort().join(',');
                return { label, ids };
            });
        }

        function renderVariants() {
            const checked = document.querySelectorAll('.attr-checkbox:checked');
            const attrMap = {};

            checked.forEach(cb => {
                const attrName = cb.dataset.attrName;
                if (!attrMap[attrName]) attrMap[attrName] = [];
                attrMap[attrName].push({
                    id: cb.value,
                    name: cb.nextElementSibling.innerText.trim()
                });
            });

            const combos = generateCombinations(attrMap);
            const newKeys = combos.map(c => c.ids);

            // 1. XÓA CÁC BLOCK ĐƯỢC SINH RA BỞI JS mà KHÔNG CÒN TRONG COMBO MỚI
            document.querySelectorAll('.variant-block.variant-new').forEach(el => {
                const keyInput = el.querySelector('input[name="variant_keys[]"]');
                if (keyInput && !newKeys.includes(keyInput.value)) {
                    el.remove();
                }
            });

            // 2. Lấy tất cả các keys đang tồn tại (từ Blade và từ JS)
            const existingKeys = new Set(
                Array.from(document.querySelectorAll('input[name="variant_keys[]"]'))
                    .map(i => i.value)
            );

            // 3. Thêm các combo mới chưa tồn tại
            combos.forEach(combo => {
                if (existingKeys.has(combo.ids)) return;

                const index = document.querySelectorAll('.variant-block').length;
                const old = oldVariants.find(v => v.variant_key === combo.ids);

                const block = document.createElement('div');
                block.className = 'variant-block variant-new border p-3 rounded mb-3';
                block.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong class="text-primary">${combo.label}</strong>
                    </div>
                    <input type="hidden" name="variant_keys[]" value="${combo.ids}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label>SKU</label>
                            <input type="text" name="variants_new[${index}][sku]" class="form-control" value="${old?.sku || ''}" required>
                        </div>
                        <div class="col-md-3">
                            <label>Giá</label>
                            <input type="number" name="variants_new[${index}][price]" class="form-control" value="${old?.price || ''}" required>
                        </div>
                        <div class="col-md-3">
                            <label>Số lượng</label>
                            <input type="number" name="variants_new[${index}][quantity]" class="form-control" value="${old?.quantity || ''}" required>
                        </div>
                        <div class="col-md-3">
                            <label>Trọng lượng</label>
                            <input type="text" name="variants_new[${index}][weight]" class="form-control" value="${old?.weight || ''}">
                        </div>
                        <div class="col-md-12">
                            <label>Ảnh biến thể</label>
                            <input type="file" name="variants_new[${index}][variant_image]" class="form-control">
                            <img src="${old?.variant_image || ''}" class="img-thumbnail" style="max-width: 120px; max-height: 120px;">
                        </div>
                    </div>
                `;

                variantList.appendChild(block);
                existingKeys.add(combo.ids);
            });
        }

        // Gắn sự kiện change
        document.querySelectorAll('.attr-checkbox').forEach(cb => {
            cb.addEventListener('change', renderVariants);
        });

        // Gọi khi load trang
        renderVariants();
    });
</script>


@endsection
