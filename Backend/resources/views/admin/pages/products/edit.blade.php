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
                                <div class="row g-3 mb-4">
                                    <div class="col-md-12">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
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
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id)
            == $category->id ? 'selected' : '' }}>
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
                                                @foreach ($brands as $id => $name)
                                                <option value="{{ $id }}" {{ old('brand_id', $product->brand_id) == $id ?
            'selected' : '' }}>
                                                    {{ $name }}
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
                                            <textarea class="form-control" id="summernote" name="description"
                                                rows="10">{{ old('description', $product->description) }}</textarea>
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
                                            <input class="form-control" id="product_image" name="product_image" type="file" />

                                            @if ($product->product_image)
                                                <div class="mt-3">
                                                    <p class="text-muted small mb-2">Ảnh hiện tại:</p>
                                                    <div class="preview-container">
                                                        <img src="{{ $product->product_image }}" class="img-thumbnail current-image"
                                                            width="150" id="current-thumbnail">
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
                                                    <img alt="Ảnh chính" class="img-thumbnail" id="mainImagePreview" src="#"
                                                        width="150" />
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
                                                <input class="form-check-input" id="activeCheck" name="active" type="checkbox"
                                                    value="1" {{ old('active', $product->active) ? 'checked' : '' }}
                                                    style="width: 3em; height: 1.5em;">
                                                <label class="form-check-label" for="activeCheck">Kích hoạt</label>
                                            </div>
                                            @error('active')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Form chọn thuộc tính -->
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="attributeSelect">Chọn thuộc tính</label>
                                        <select class="form-control @error('attributeId') is-invalid @enderror"
                                            id="attributeSelect" multiple name="attributeId[]">
                                            @foreach ($attributes as $attribute)
                                                <option value="{{ $attribute->id }}" @if (in_array($attribute->id, $selectedAttributeIds)) selected @endif>
                                                    {{ $attribute->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('attributeId')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nơi hiển thị các select value -->
                                <div id="attributeForms" class="col-12"></div>
                               
                                @error('attributeValues')
                                            <p class="text-danger">{{ $message }}</p>
                                @enderror
                                <!-- Nút tạo biến thể -->
                                <div class="col-12">
                                    <button type="button" id="saveAttributes" class="btn btn-primary">Lưu thuộc tính</button>
                                </div>

                                <!-- Nơi hiển thị bảng biến thể -->
                                <div id="variantSection" class="col-12 mt-5"></div>
                                 @error('product_variants')
                                            <p class="text-danger">{{ $message }}</p>
                                @enderror
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
        document.addEventListener('DOMContentLoaded', function () {
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
        $(document).ready(function () {
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
    {{-- pr variant --}}
    <script>
        var attributeValues = @json($attributeValues);
        var attributeNames = @json($attributeNames);
        var selectedAttributeIds = @json($selectedAttributeIds); // [1, 2]
        var selectedAttributeValueIds = @json($selectedAttributeValueIds); // {1: [3,4], 2: [6,7]}
        var oldVariants = @json($product->variants);
        document.addEventListener('DOMContentLoaded', function () {
        // Gán selected thuộc tính
        const attributeSelect = document.getElementById('attributeSelect');
        Array.from(attributeSelect.options).forEach(option => {
            if (selectedAttributeIds.includes(parseInt(option.value))) {
                option.selected = true;
            }
        });

        // Tự động render form chọn giá trị thuộc tính
        renderSelectedAttributeForms();

        // Sau khi render xong, nếu có variants thì render bảng product_variant
        if (oldVariants.length > 0) {
            renderVariantsFromExisting(oldVariants);
        }

        // Khi chọn thuộc tính thủ công
        attributeSelect.addEventListener('change', function () {
            renderSelectedAttributeForms();
        });
        //save attribitues
        document.getElementById('saveAttributes').addEventListener('click', function () {
            let selectedAttributes = document.querySelectorAll('select[name^="attributeValues"]');
            let attributeCombinations = getCombinations(selectedAttributes);
            let variantSection = document.getElementById('variantSection');

            const isValid = attributeCombinations.length > 0 &&
                attributeCombinations.every(comb => comb.length > 0);

            if (!isValid) return;

            variantSection.innerHTML = '';
            renderVariantTable(attributeCombinations);
        });

        // Tự động render form chọn giá trị thuộc tính
        function renderSelectedAttributeForms() {
            let selectedAttributes = Array.from(attributeSelect.selectedOptions).map(option => option.value);
            let attributeFormsContainer = document.getElementById('attributeForms');
            attributeFormsContainer.innerHTML = '';

            selectedAttributes.forEach(attributeId => {
                if (attributeValues[attributeId]) {
                    let options = '';
                    for (const [id, name] of Object.entries(attributeValues[attributeId])) {
                        let selected = selectedAttributeValueIds[attributeId] && selectedAttributeValueIds[attributeId].includes(parseInt(id)) ? 'selected' : '';
                        options += `<option value="${id}" ${selected}>${name}</option>`;
                    }
                    let attributeName = attributeNames[attributeId] || '---';
                    let formGroup = document.createElement('div');
                    formGroup.classList.add('mb-3');
                    formGroup.innerHTML = `
                        <label for="attributeValue_${attributeId}">Chọn giá trị cho <strong>${attributeName}</strong></label>
                        <select class="form-control" id="attributeValue_${attributeId}" name="attributeValues[${attributeId}][]" multiple>
                            ${options}
                        </select>
                    `;
                    attributeFormsContainer.appendChild(formGroup);
                }
            });
        }

        function getCombinations(selectElements) {
            let attributeValues = Array.from(selectElements).map(select => {
                let match = select.name.match(/attributeValues\[(\d+)\]/);
                let attributeId = match ? match[1] : null;

                return Array.from(select.selectedOptions).map(option => ({
                    id: option.value,
                    name: option.text,
                    attributeId: attributeId
                }));
            });

            if (attributeValues.length === 1) {
                return attributeValues[0].map(item => [item]);
            }

            function combine(arr) {
                if (arr.length === 0) return [[]];
                let result = [];
                let restCombinations = combine(arr.slice(1));
                arr[0].forEach(item => {
                    restCombinations.forEach(combination => {
                        result.push([item].concat(combination));
                    });
                });
                return result;
            }

            return combine(attributeValues);
        }

        //render variant table(hiển thị các biến thể khi bấm lưu thuộc tính)
       function renderVariantTable(attributeCombinations) {
    let variantSection = document.getElementById('variantSection');
    let tableHtml = `
        <h3>Biến thể sản phẩm</h3>
        <table class="table table-hover" id="variantTable">
            <thead>
                <tr>
                    ${attributeCombinations[0].map(attr => `<th>${attributeNames[attr.attributeId]}</th>`).join('')}
                    <th>SKU</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Cân nặng</th>
                    <th>Ảnh</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
    `;

    attributeCombinations.forEach((combination, rowIndex) => {
        // Kiểm tra xem combination hiện tại có trong oldVariants không
        let matchedVariant = oldVariants.find(variant => {
            if (!variant.attributes || variant.attributes.length !== combination.length) return false;

            return combination.every((attr, i) => {
                return variant.attributes.some(va =>
                    parseInt(va.id) === parseInt(attr.attributeId) &&
                    parseInt(va.pivot.attribute_value_id) === parseInt(attr.id)
                );
            });
        });

        const sku = matchedVariant?.sku ?? '';
        const price = matchedVariant?.price ?? '';
        const quantity = matchedVariant?.quantity ?? '';
        const weight = matchedVariant?.weight ?? '';
        const imageUrl = matchedVariant?.variant_image ?? '';

        let attributeCells = combination.map((attr, index) => `
            <td data-attribute-id="${attr.attributeId}" data-value-id="${attr.id}">
                ${attr.name}
                <input type="hidden" name="product_variants[${rowIndex}][attribute_item_id][${index}][id]" value="${attr.id}">
                <input type="hidden" name="product_variants[${rowIndex}][attribute_item_id][${index}][value]" value="${attr.name}">
            </td>`).join('');

        tableHtml += `
            <tr>
                ${attributeCells}
                <td><input type="text" class="form-control" name="product_variants[${rowIndex}][sku]" value="${sku}" placeholder="SKU"></td>
                <td><input type="text" class="form-control" name="product_variants[${rowIndex}][price]" value="${price}" placeholder="Giá"></td>
                <td><input type="text" class="form-control" name="product_variants[${rowIndex}][quantity]" value="${quantity}" placeholder="Số lượng"></td>
                <td><input type="text" class="form-control" name="product_variants[${rowIndex}][weight]" value="${weight}" placeholder="Cân nặng"></td>
                <td>
                    <input type="file" class="form-control" name="product_variants[${rowIndex}][variant_image]">
                    ${imageUrl ? `<img src="${imageUrl}" class="img-thumbnail current-image" width="60">` : ''}
                </td>
                <td><button type="button" class="btn btn-danger btn-sm delete-variant">Xóa</button></td>
            </tr>`;
    });

    tableHtml += '</tbody></table>';
    variantSection.innerHTML = tableHtml;

    document.querySelectorAll('.delete-variant').forEach(button => {
        button.addEventListener('click', function () {
            handleDeleteRow(this);
        });
    });
}


        //render variant.
        function renderVariantsFromExisting(variants) {
            let variantSection = document.getElementById('variantSection');
            variantSection.innerHTML = '';

            if (!variants || variants.length === 0) return;

            let headers = variants[0].attributes.map(attr => attr.name);
            let tableHtml = `
                <h3>Biến thể sản phẩm</h3>
                <table class="table table-hover" id="variantTable">
                    <thead>
                        <tr>
                            ${headers.map(h => `<th>${h}</th>`).join('')}
                            <th>SKU</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Cân nặng</th>
                            <th>Ảnh</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            variants.forEach((variant, rowIndex) => {
                let attributeCells = variant.attributes.map((attr, index) => `
                    <td data-attribute-id="${attr.id}" data-value-id="${attr.pivot.attribute_value_id}">
                        ${attr.pivot.value}
                        <input type="hidden" name="product_variants[${rowIndex}][attribute_item_id][${index}][id]" value="${attr.pivot.attribute_value_id}">
                        <input type="hidden" name="product_variants[${rowIndex}][attribute_item_id][${index}][value]" value="${attr.pivot.value}">
                    </td>`).join('');

                tableHtml += `
                    <tr>
                        ${attributeCells}
                        <td><input type="text" class="form-control" name="product_variants[${rowIndex}][sku]" value="${variant.sku ?? ''}" placeholder="SKU"></td>
                        <td><input type="text" class="form-control" name="product_variants[${rowIndex}][price]" value="${variant.price ?? ''}" placeholder="Giá"></td>
                        <td><input type="text" class="form-control" name="product_variants[${rowIndex}][quantity]" value="${variant.quantity ?? ''}" placeholder="Số lượng"></td>
                        <td><input type="text" class="form-control" name="product_variants[${rowIndex}][weight]" value="${variant.weight ?? ''}" placeholder="Cân nặng"></td>
                        <td><input type="file" class="form-control" name="product_variants[${rowIndex}][variant_image]">
                            <img src="${variant.variant_image}" class="img-thumbnail current-image"
                                                        width="60" id="current-thumbnail">
                        </td>
                        <td><button type="button" class="btn btn-danger btn-sm delete-variant">Xóa</button></td>
                    </tr>`;
            });

            tableHtml += '</tbody></table>';
            variantSection.innerHTML = tableHtml;
             // Gọi gắn sự kiện nút Xóa
            document.querySelectorAll('.delete-variant').forEach(button => {
                button.addEventListener('click', function () {
                    handleDeleteRow(this);
                });
            });
        }

        function handleDeleteRow(button) {
                // Tìm hàng chứa nút xóa
                let row = button.closest('tr');
                if (!row) return;

                // Lấy tất cả các <td> có data-attribute-id và data-value-id trong hàng đó
                let attributeCells = row.querySelectorAll('td[data-attribute-id][data-value-id]');

                // Tạo danh sách các giá trị sẽ bị xóa
                let valuesToRemove = Array.from(attributeCells).map(cell => ({
                    attributeId: cell.getAttribute('data-attribute-id'),
                    valueId: cell.getAttribute('data-value-id')
                }));

                // Xóa hàng khỏi bảng
                row.remove();

                // Sau khi xóa, kiểm tra nếu không còn dòng nào trong bảng thì reset giao diện
                const remainingRows = document.querySelectorAll('#variantTable tbody tr');
                if (remainingRows.length === 0) {
                    resetVariantSection();
                }

                // Với mỗi giá trị bị xóa, kiểm tra còn dòng nào đang dùng không
                valuesToRemove.forEach(({ attributeId, valueId }) => {
                    let stillUsed = document.querySelector(
                        `#variantTable td[data-attribute-id="${attributeId}"][data-value-id="${valueId}"]`
                    );

                    if (!stillUsed) {
                        // Nếu không còn ai dùng, thì bỏ chọn trong select tương ứng
                        let select = document.querySelector(`#attributeValue_${attributeId}`);
                        if (select) {
                            Array.from(select.options).forEach(option => {
                                if (option.value == valueId) {
                                    option.selected = false;
                                }
                            });
                        }
                    }
                });
            }
        function resetVariantSection() {
                // Xóa bảng biến thể
                document.getElementById('variantSection').innerHTML = '';

                // Reset select thuộc tính (clear selected)
                const attributeSelect = document.getElementById('attributeSelect');
                Array.from(attributeSelect.options).forEach(option => option.selected = false);

                // Xóa các form nhập giá trị thuộc tính
                document.getElementById('attributeForms').innerHTML = '';
            }
    });
    </script>
@endsection