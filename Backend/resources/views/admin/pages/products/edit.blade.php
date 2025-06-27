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

        .preview-container {
            position: relative;
            display: inline-block;
        }

        .remove-btn {
            position: absolute;
            top: 2px;
            right: 2px;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 14px;
            background: red;
            color: white;
            border: none;
            cursor: pointer;
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

                        <!-- Màu sắc -->
<div class="form-group">
    <label class="fw-bold"><i class="fas fa-palette me-2"></i>Chọn màu</label><br>
    @foreach ($colors as $color)
        <div class="form-check form-check-inline">
            <input type="checkbox" class="form-check-input color-checkbox" name="colors[]"
                value="{{ $color->id }}" data-color-name="{{ $color->value }}"
                id="color-{{ $color->id }}"
                {{ in_array($color->id, $selectedValueIds) ? 'checked' : '' }}>
            <label class="form-check-label" for="color-{{ $color->id }}">{{ $color->value }}</label>
        </div>
    @endforeach
</div>

<!-- Size theo màu -->
<div id="size-selectors"></div>

<!-- Biến thể sản phẩm -->
<div class="form-section mt-4">
    <h5 class="form-section-title"><i class="fas fa-random me-2"></i>Biến thể sản phẩm</h5>
    <div id="variant-forms"></div>
    <input type="hidden" name="variants_json" id="variants_json" value="">
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
        // Khởi tạo Tagify
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.querySelector('#tag-input');
            new Tagify(input, {
                enforceWhitelist: false,
                dropdown: {
                    enabled: 0
                },
                originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
            });
        });
    </script>

    <script>
        const productImageInput = document.getElementById('product_image');
        const mainImagePreview = document.getElementById('mainImagePreview');
        const mainImageWrapper = document.getElementById('mainImageWrapper');

        productImageInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ảnh không được vượt quá 5MB');
                    this.value = '';
                    mainImageWrapper.style.display = 'none';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (e) {
                    mainImagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
                mainImageWrapper.style.display = 'block';
            } else {
                mainImageWrapper.style.display = 'none';
            }
        });

        window.removeMainImage = function () {
            productImageInput.value = '';
            mainImagePreview.src = '#';
            mainImageWrapper.style.display = 'none';
        };

        // ==== ẢNH ĐÍNH KÈM ====
        const attachmentsInput = document.getElementById('attachments');
        const attachmentsPreview = document.getElementById('attachmentsPreview');
        const removedInput = document.getElementById('removed_attachments');
        let allFiles = [];

        attachmentsInput.addEventListener('change', function (e) {
            const newFiles = Array.from(e.target.files);

            const validFiles = newFiles.filter(file => {
                if (file.size > 2 * 1024 * 1024) {
                    alert(`File "${file.name}" vượt quá 2MB và sẽ bị bỏ qua`);
                    return false;
                }
                return true;
            });

            validFiles.forEach(file => {
                if (!allFiles.some(f => f.name === file.name && f.size === file.size)) {
                    allFiles.push(file);
                }
            });

            updateAttachmentsPreview();
            updateInputFiles();
        });

        function updateAttachmentsPreview() {
            attachmentsPreview.innerHTML = '';

            allFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const container = document.createElement('div');
                    container.classList.add('preview-container', 'position-relative');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.width = '120px';
                    img.style.height = '120px';

                    const btn = document.createElement('button');
                    btn.className = 'remove-btn btn btn-sm btn-danger position-absolute top-0 end-0';
                    btn.textContent = '×';
                    btn.type = 'button';
                    btn.style.zIndex = 10;
                    btn.onclick = function () {
                        allFiles.splice(index, 1);
                        updateAttachmentsPreview();
                        updateInputFiles();
                    };

                    container.appendChild(img);
                    container.appendChild(btn);
                    attachmentsPreview.appendChild(container);
                };
                reader.readAsDataURL(file);
            });
        }

        function updateInputFiles() {
            const dt = new DataTransfer();
            allFiles.forEach(file => dt.items.add(file));
            attachmentsInput.files = dt.files;
        }

        // ==== XÓA ẢNH ĐÍNH KÈM CŨ (hiển thị sẵn từ DB) ====
        document.querySelectorAll('.remove-image').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const container = this.closest('.preview-container');
                if (container) container.remove();

                let current = removedInput.value ? removedInput.value.split(',') : [];
                if (!current.includes(id)) {
                    current.push(id);
                }
                removedInput.value = current.join(',');
            });
        });
    </script>

    <script>
         // Khởi tạo Summernote
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
    </script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sizes = @json($sizes);
    const sizeSelectors = document.getElementById('size-selectors');
    const variantForms = document.getElementById('variant-forms');
    const variantsJsonInput = document.getElementById('variants_json');
    const initialVariants = @json($productVariants);

    let selectedVariants = {};

    // 1. Load biến thể ban đầu
    initialVariants.forEach(item => {
        const key = `${item.color}/${item.size}`;
        selectedVariants[key] = {
            id: item.id,
            color: item.color,
            size: item.size,
            sku: item.sku,
            price: item.price,
            quantity: item.quantity,
            weight: item.weight,
            variant_image: item.variant_image
        };
    });

    // 2. Render checkbox size theo màu đã chọn
    function renderSizeSelectors() {
        sizeSelectors.innerHTML = '';

        document.querySelectorAll('.color-checkbox:checked').forEach(colorEl => {
            const colorId = colorEl.value;
            const colorName = colorEl.dataset.colorName;

            const wrapper = document.createElement('div');
            wrapper.className = 'mb-3 border rounded p-3';
            wrapper.id = `size-block-${colorId}`;
            wrapper.innerHTML = `<label class="fw-bold d-block mb-2">Chọn size cho màu <span class="text-primary">${colorName}</span>:</label>`;

            sizes.forEach(size => {
                const key = `${colorName}/${size.value}`;
                const isChecked = selectedVariants[key] ? 'checked' : '';
                wrapper.innerHTML += `
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input size-checkbox"
                               data-color-name="${colorName}" data-size-name="${size.value}"
                               id="size-${colorId}-${size.id}" ${isChecked}>
                        <label class="form-check-label" for="size-${colorId}-${size.id}">${size.value}</label>
                    </div>
                `;
            });

            sizeSelectors.appendChild(wrapper);
        });

        attachSizeEvents();
    }

    // 3. Gắn sự kiện checkbox size
    function attachSizeEvents() {
        document.querySelectorAll('.size-checkbox').forEach(el => {
            el.addEventListener('change', function () {
                const key = `${this.dataset.colorName}/${this.dataset.sizeName}`;
                if (this.checked) {
                    if (!selectedVariants[key]) {
                        selectedVariants[key] = {
                            sku: '', price: '', quantity: '', weight: '', variant_image: ''
                        };
                    }
                } else {
                    delete selectedVariants[key];
                }
                renderVariantForms();
            });
        });
    }

    // 4. Render form biến thể
    function renderVariantForms() {
        variantForms.innerHTML = '';
        const variantsArray = [];

        Object.entries(selectedVariants).forEach(([key, data], index) => {
            const [color, size] = key.split('/');
            const block = document.createElement('div');
            block.className = 'border p-3 mb-3 rounded bg-light';

            block.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">${color} / ${size}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-variant" data-key="${key}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <input type="hidden" name="variant_keys[${index}]" value="${key}">
                ${data.id ? `<input type="hidden" name="variant_ids[${index}]" value="${data.id}">` : ''}
                ${data.id ? `<input type="hidden" name="variants[${index}][id]" value="${data.id}">` : ''}
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>SKU</label>
                        <input type="text" name="variants[${index}][sku]" class="form-control" value="${data.sku || ''}">
                    </div>
                    <div class="col-md-4">
                        <label>Giá</label>
                        <input type="number" name="variants[${index}][price]" class="form-control" value="${data.price || ''}" required>
                    </div>
                    <div class="col-md-4">
                        <label>Số lượng</label>
                        <input type="number" name="variants[${index}][quantity]" class="form-control" value="${data.quantity || ''}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Trọng lượng</label>
                        <input type="text" name="variants[${index}][weight]" class="form-control" value="${data.weight || ''}">
                    </div>
                    <div class="col-md-6">
                        <label>Ảnh biến thể</label>
                        <div class="mb-2">
                            ${data.variant_image ? `<img src="${data.variant_image}" class="img-thumbnail mb-2" width="120">` : ''}
                        </div>
                        <input type="file" name="variants[${index}][variant_image]" class="form-control" accept="image/*">
                    </div>
                </div>
            `;

            variantForms.appendChild(block);
            variantsArray.push({ id: data.id ?? null, key, color, size, ...data });
        });

        variantsJsonInput.value = JSON.stringify(variantsArray);

        document.querySelectorAll('.remove-variant').forEach(btn => {
            btn.addEventListener('click', function () {
                delete selectedVariants[this.dataset.key];
                renderVariantForms();
                renderSizeSelectors();
            });
        });
    }

    // 5. Sự kiện checkbox màu
    document.querySelectorAll('.color-checkbox').forEach(el => {
        el.addEventListener('change', () => {
            const colorName = el.dataset.colorName;
            if (!el.checked) {
                document.getElementById(`size-block-${el.value}`)?.remove();
                Object.keys(selectedVariants).forEach(key => {
                    if (key.startsWith(`${colorName}/`)) {
                        delete selectedVariants[key];
                    }
                });
                renderVariantForms();
            }
            renderSizeSelectors();
        });
    });

    renderSizeSelectors();
    renderVariantForms();
});
</script>



@endsection
