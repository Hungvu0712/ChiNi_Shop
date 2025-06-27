@extends('admin.layouts.master')
@section('title', 'Thêm mới sản phẩm')
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
    <div class="container my-4">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h4 class="mb-0 text-white"><i class="fas fa-plus-circle me-2"></i>Tạo sản phẩm mới</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input class="form-control" name="name" type="text" value="{{ old('name') }}"
                                placeholder="Nhập tên sản phẩm" />
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="price">Giá <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">vnđ</span>
                                <input class="form-control" name="price" step="0.01" type="number"
                                    value="{{ old('price') }}" placeholder="Nhập giá sản phẩm" />
                            </div>
                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="category_id">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select" name="category_id">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="brand_id">Thương hiệu</label>
                            <select class="form-select" name="brand_id">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Mô tả sản phẩm</label>
                        <textarea class="form-control" id="summernote" name="description" rows="10">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Ảnh chính <span class="text-danger">*</span></label>
                            <input class="form-control" id="product_image" name="product_image" type="file"
                                accept="image/*" />
                            <div class="text-muted small mt-1">Ảnh đại diện cho sản phẩm (tối đa 2MB)</div>
                            @error('product_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="mt-2" id="mainImageWrapper" style="display:none;">
                                <div class="preview-container">
                                    <img alt="Ảnh chính" class="img-thumbnail" id="mainImagePreview" src="#" />
                                    <button class="remove-btn" onclick="removeMainImage()" type="button"
                                        title="Xóa ảnh">×</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ảnh đính kèm</label>
                            <input class="form-control" id="attachments" name="attachments[]" type="file" multiple
                                accept="image/*" />
                            <div class="text-muted small mt-1">Có thể chọn nhiều ảnh (tối đa 2MB mỗi ảnh)</div>
                            @error('attachments.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="mt-2 d-flex flex-wrap gap-2" id="attachmentsPreview"></div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Trọng lượng</label>
                            <div class="input-group">
                                <input class="form-control" name="weight" type="text" value="{{ old('weight') }}"
                                    placeholder="VD: 500" />
                                <span class="input-group-text">gram</span>
                            </div>
                            @error('weight')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                            <input class="form-control" name="quantity" type="number" value="{{ old('quantity') }}"
                                placeholder="Nhập số lượng" />
                            @error('quantity')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Số lượng tồn kho cảnh báo</label>
                            <input class="form-control" name="quantity_warning" type="number"
                                value="{{ old('quantity_warning') }}" placeholder="Nhập số lượng cảnh báo" />
                            @error('quantity_warning')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Tags</label>
                            <input id="tag-input" name="tags" class="form-control" value="{{ old('tags') }}"
                                placeholder="Nhập tags">
                            @error('tags')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">SKU</label>
                            <input class="form-control" name="sku" type="text" value="{{ old('sku') }}"
                                placeholder="Mã SKU sản phẩm" />
                            @error('sku')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input name="active" type="hidden" value="0" />
                            <input checked class="form-check-input" id="activeCheck" name="active" type="checkbox"
                                value="1" />
                            <label class="form-check-label fw-bold" for="activeCheck">Kích hoạt sản phẩm</label>
                            @error('active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
                {{ in_array($color->id, $selectedValueIds ?? []) ? 'checked' : '' }}>
            <label class="form-check-label" for="color-{{ $color->id }}">{{ $color->value }}</label>
        </div>
    @endforeach
</div>

<!-- Size theo màu -->
<div id="size-selectors" class="mb-3"></div>

<!-- Biến thể sản phẩm -->
<div class="form-section">
    <h5 class="form-section-title"><i class="fas fa-random me-2"></i>Biến thể sản phẩm</h5>
    <div id="variant-forms"></div>
    <input type="hidden" name="variants_json" id="variants_json" value="">
</div>



                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a class="btn btn-secondary" href="{{ route('products.index') }}">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                        <button class="btn btn-success" type="submit">
                            <i class="fas fa-save me-2"></i>Tạo sản phẩm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
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
        // Xử lý hiển thị ảnh chính
        const productImageInput = document.getElementById('product_image');
        const mainImagePreview = document.getElementById('mainImagePreview');
        const mainImageWrapper = document.getElementById('mainImageWrapper');

        productImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ảnh không được vượt quá 2MB');
                    this.value = '';
                    return;
                }
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

        // Mảng lưu toàn bộ file hợp lệ (kể cả đã chọn trước)
        let allFiles = [];

        attachmentsInput.addEventListener('change', function(e) {
            const newFiles = Array.from(e.target.files);

            // Lọc ảnh không vượt quá 2MB
            const validNewFiles = newFiles.filter(file => {
                if (file.size > 2 * 1024 * 1024) {
                    alert(`File ${file.name} vượt quá 2MB và sẽ bị bỏ qua`);
                    return false;
                }
                return true;
            });

            // Gộp ảnh mới vào danh sách cũ (tránh trùng tên)
            validNewFiles.forEach(newFile => {
                if (!allFiles.some(file => file.name === newFile.name && file.size === newFile.size)) {
                    allFiles.push(newFile);
                }
            });

            updateAttachmentsPreview();
            updateInputFiles(); // Cập nhật lại input[type=file]
        });

        // Cập nhật giao diện ảnh preview
        function updateAttachmentsPreview() {
            attachmentsPreview.innerHTML = '';

            allFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.createElement('div');
                    container.classList.add('preview-container', 'position-relative');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.height = '120px';
                    img.style.width = '120px';

                    const btn = document.createElement('button');
                    btn.className = 'remove-btn btn btn-sm btn-danger position-absolute top-0 end-0';
                    btn.style.zIndex = 10;
                    btn.textContent = '×';
                    btn.type = 'button';
                    btn.onclick = function() {
                        allFiles.splice(index, 1); // Xoá ảnh khỏi danh sách
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

        // Cập nhật lại input `attachments` với danh sách `allFiles`
        function updateInputFiles() {
            const dt = new DataTransfer();
            allFiles.forEach(file => dt.items.add(file));
            attachmentsInput.files = dt.files;
        }
    </script>


    {{-- biến thể --}}
   <script>
document.addEventListener('DOMContentLoaded', function () {
    const sizes = @json($sizes);
    const initialVariants = @json($productVariants ?? []);
    const sizeSelectors = document.getElementById('size-selectors');
    const variantForms = document.getElementById('variant-forms');
    const variantsJsonInput = document.getElementById('variants_json');
    let selectedVariants = {};

    // Bước 1: Đổ dữ liệu ban đầu
    initialVariants.forEach(item => {
        const key = `${item.color}/${item.size}`;
        selectedVariants[key] = {
            id: item.id ?? null,
            color: item.color,
            size: item.size,
            sku: item.sku || '',
            price: item.price || '',
            quantity: item.quantity || '',
            weight: item.weight || '',
            variant_image: item.variant_image || ''
        };
    });

    // Bước 2: Render size selectors
    function renderSizeSelectors() {
        sizeSelectors.innerHTML = '';
        document.querySelectorAll('.color-checkbox:checked').forEach(colorEl => {
            const colorId = colorEl.value;
            const colorName = colorEl.dataset.colorName;

            const wrapper = document.createElement('div');
            wrapper.className = 'border rounded p-3 mb-2';
            wrapper.id = `size-block-${colorId}`;
            wrapper.innerHTML = `<label class="fw-bold">Chọn size cho màu <span class="text-primary">${colorName}</span>:</label><br>`;

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

    // Bước 3: Gắn sự kiện checkbox size
    function attachSizeEvents() {
        document.querySelectorAll('.size-checkbox').forEach(el => {
            el.addEventListener('change', function () {
                const key = `${this.dataset.colorName}/${this.dataset.sizeName}`;
                if (this.checked) {
                    if (!selectedVariants[key]) {
                        selectedVariants[key] = {
                            sku: '',
                            price: '',
                            quantity: '',
                            weight: '',
                            variant_image: ''
                        };
                    }
                } else {
                    delete selectedVariants[key];
                }
                renderVariantForms();
            });
        });
    }

    // Bước 4: Render form biến thể
    function renderVariantForms() {
        variantForms.innerHTML = '';
        const variantsJson = [];

        Object.entries(selectedVariants).forEach(([key, data], index) => {
            const [color, size] = key.split('/');
            const block = document.createElement('div');
            block.className = 'border p-3 mb-3 rounded bg-light';

            block.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6>${color} / ${size}</h6>
                    <button type="button" class="btn btn-sm btn-danger remove-variant" data-key="${key}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <input type="hidden" name="variant_keys[${index}]" value="${key}">
                ${data.id ? `<input type="hidden" name="variant_ids[${index}]" value="${data.id}">` : ''}

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
                            ${data.variant_image ? `<img src="${data.variant_image}" class="img-thumbnail" width="100">` : ''}
                        </div>
                        <input type="file" name="variants[${index}][variant_image]" class="form-control" accept="image/*">
                        <small class="text-muted">* Ảnh sẽ mất nếu bạn thay đổi biến thể</small>
                    </div>
                </div>
            `;

            variantForms.appendChild(block);
            variantsJson.push({
                id: data.id || null,
                key: key,
                color: color,
                size: size,
                sku: data.sku,
                price: data.price,
                quantity: data.quantity,
                weight: data.weight,
                variant_image: data.variant_image
            });
        });

        variantsJsonInput.value = JSON.stringify(variantsJson);

        // Bắt sự kiện xóa
        document.querySelectorAll('.remove-variant').forEach(btn => {
            btn.addEventListener('click', function () {
                const key = this.dataset.key;
                delete selectedVariants[key];
                renderVariantForms();
                renderSizeSelectors();
            });
        });
    }

    // Bước 5: Gắn sự kiện checkbox màu
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

    // Khởi tạo
    renderSizeSelectors();
    renderVariantForms();
});
</script>


@endsection
