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
                            <input class="form-control" name="name" type="text" value="{{ old('name') }}" placeholder="Nhập tên sản phẩm" />
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="price">Giá <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₫</span>
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
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
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
                            <input class="form-control" id="product_image" name="product_image" type="file" accept="image/*" />
                            <div class="text-muted small mt-1">Ảnh đại diện cho sản phẩm (tối đa 5MB)</div>
                            @error('product_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="mt-2" id="mainImageWrapper" style="display:none;">
                                <div class="preview-container">
                                    <img alt="Ảnh chính" class="img-thumbnail" id="mainImagePreview" src="#" />
                                    <button class="remove-btn" onclick="removeMainImage()" type="button" title="Xóa ảnh">×</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ảnh đính kèm</label>
                            <input class="form-control" id="attachments" name="attachments[]" type="file" multiple accept="image/*" />
                            <div class="text-muted small mt-1">Có thể chọn nhiều ảnh (tối đa 5MB mỗi ảnh)</div>
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
                                <input class="form-control" name="weight" type="text" value="{{ old('weight') }}" placeholder="VD: 500g" />
                                <span class="input-group-text">gram</span>
                            </div>
                            @error('weight')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                            <input class="form-control" name="quantity" type="number" value="{{ old('quantity') }}" placeholder="Nhập số lượng" />
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
                            <input checked class="form-check-input" id="activeCheck" name="active" type="checkbox" value="1" />
                            <label class="form-check-label fw-bold" for="activeCheck">Kích hoạt sản phẩm</label>
                            @error('active')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Hiển thị các thuộc tính sản phẩm --}}
                    <div class="attr-section mb-4">
                        <h5><i class="fas fa-tags me-2"></i>Thuộc tính sản phẩm</h5>
                        @foreach ($attributes as $attribute)
                            <div class="mb-3">
                                <label class="fw-bold d-block mb-2">{{ $attribute->name }}</label>
                                <div class="d-flex flex-wrap">
                                    @foreach ($attribute->attributeValues as $value)
                                        <div class="form-check me-3 mb-2">
                                            <input type="checkbox" class="form-check-input attr-checkbox"
                                                   name="attributes[{{ $attribute->id }}][]"
                                                   data-attr-name="{{ $attribute->name }}"
                                                   data-attr-id="{{ $attribute->id }}"
                                                   value="{{ $value->id }}"
                                                   id="attr-{{ $attribute->id }}-{{ $value->id }}">
                                            <label class="form-check-label" for="attr-{{ $attribute->id }}-{{ $value->id }}">
                                                {{ $value->value }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Khu vực hiển thị các biến thể sản phẩm --}}
                    @if ($errors->any())
                        @php
                            $variantErrors = collect($errors->getMessages())->filter(function ($_, $key) {
                                return Str::startsWith($key, 'variants');
                            });
                        @endphp

                        @if ($variantErrors->isNotEmpty())
                            <div class="alert alert-danger mb-4">
                                <strong class="d-block mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Có lỗi trong biến thể sản phẩm:</strong>
                                <ul class="mb-0 ps-3">
                                    @foreach ($variantErrors as $messages)
                                        @foreach ($messages as $message)
                                            <li>{{ $message }}</li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif

                    <div class="attr-section mt-4">
                        <h5><i class="fas fa-list-alt me-2"></i>Biến thể sản phẩm</h5>
                        <div id="variant-list" class="mt-3"></div>
                        <input type="hidden" name="variants_json" id="variants_json">
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
        // Xử lý hiển thị ảnh chính
        const productImageInput = document.getElementById('product_image');
        const mainImagePreview = document.getElementById('mainImagePreview');
        const mainImageWrapper = document.getElementById('mainImageWrapper');

        productImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ảnh không được vượt quá 5MB');
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

        // Xử lý hiển thị ảnh đính kèm
        const attachmentsInput = document.getElementById('attachments');
        const attachmentsPreview = document.getElementById('attachmentsPreview');

        attachmentsInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            attachmentsPreview.innerHTML = '';

            // Kiểm tra kích thước từng file
            const validFiles = files.filter(file => {
                if (file.size > 5 * 1024 * 1024) {
                    alert(`File ${file.name} vượt quá 5MB sẽ bị bỏ qua`);
                    return false;
                }
                return true;
            });

            // Cập nhật lại files input với chỉ các file hợp lệ
            const dt = new DataTransfer();
            validFiles.forEach(file => dt.items.add(file));
            this.files = dt.files;

            validFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.createElement('div');
                    container.classList.add('preview-container');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.height = '120px';
                    img.style.width = '120px';

                    const btn = document.createElement('button');
                    btn.className = 'remove-btn';
                    btn.innerHTML = '×';
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

        // Xử lý biến thể sản phẩm
        document.addEventListener('DOMContentLoaded', function() {
            const variantList = document.getElementById('variant-list');
            const variantsJsonInput = document.getElementById('variants_json');

            function generateCombinations(attributeMap) {
                const keys = Object.keys(attributeMap);
                if (keys.length === 0) return [];

                function cartesian(arr) {
                    return arr.reduce((a, b) => a.flatMap(d => b.map(e => [...d, e])), [
                        []
                    ]);
                }
                const valuesArray = keys.map(k => attributeMap[k]);
                const combinations = cartesian(valuesArray);
                return combinations.map(comb => {
                    const label = comb.map(v => v.name).join(' / ');
                    const ids = comb.map(v => v.id).join(',');
                    return {
                        label,
                        ids
                    };
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

                if (Object.keys(attrMap).length < 2) {
                    variantList.innerHTML = `
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Chọn ít nhất 2 thuộc tính (VD: Màu và Size) để tạo biến thể
                        </div>`;
                    variantsJsonInput.value = '';
                    return;
                }

                const combos = generateCombinations(attrMap);
                variantList.innerHTML = '';
                const variantsData = [];

                combos.forEach((combo, index) => {
                    const block = document.createElement('div');
                    block.className = 'border p-3 rounded mb-3 bg-white';
                    block.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 text-primary">${combo.label}</h6>
                            <span class="badge bg-secondary">#${index + 1}</span>
                        </div>
                        <input type="hidden" name="variant_keys[${index}]" value="${combo.ids}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">SKU</label>
                                <input type="text" name="variants[${index}][sku]" class="form-control" placeholder="Mã SKU biến thể">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Giá <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₫</span>
                                    <input type="number" step="0.01" name="variants[${index}][price]"
                                           class="form-control" placeholder="Giá biến thể" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" name="variants[${index}][quantity]"
                                       class="form-control" placeholder="Số lượng" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Trọng lượng</label>
                                <div class="input-group">
                                    <input type="text" name="variants[${index}][weight]"
                                           class="form-control" placeholder="VD: 500g">
                                    <span class="input-group-text">gram</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ảnh biến thể</label>
                                <input type="file" name="variants[${index}][variant_image]"
                                       class="form-control" accept="image/*">
                            </div>
                        </div>
                    `;
                    variantList.appendChild(block);
                    variantsData.push({
                        ids: combo.ids,
                        label: combo.label
                    });
                });
                variantsJsonInput.value = JSON.stringify(variantsData);
            }

            document.querySelectorAll('.attr-checkbox').forEach(cb => {
                cb.addEventListener('change', renderVariants);
            });
        });

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
@endsection
