@extends('admin.layouts.master')
@section('title', 'Thêm mới')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
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
                <form action="{{ route('products.store') }}" enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên sản phẩm</label>
                            <input class="form-control" name="name" type="text" value="{{ old('name') }}" />
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="price">Giá</label>
                            <input class="form-control" name="price" step="0.01" type="number"
                                value="{{ old('price') }}" />
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label" for="category_id">Danh mục (Category)</label>
                            <select class="form-control" name="category_id">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="brand_id">Thương hiệu (Brand)</label>
                            <select class="form-control" name="brand_id">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-12">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" id="summernote" name="description" rows="10">{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Ảnh chính</label>
                            <input class="form-control" id="product_image" name="product_image" type="file" />
                            @error('product_image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <div class="mt-2" id="mainImageWrapper" style="display:none;">
                                <div class="preview-container">
                                    <img alt="Ảnh chính" class="img-thumbnail" id="mainImagePreview" src="#"
                                        style="max-height: 200px;" />
                                    <button class="remove-btn" onclick="removeMainImage()" type="button">×</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ảnh đính kèm</label>
                            <input class="form-control" id="attachments" name="attachments[]" type="file" multiple />
                            @error('attachments.*')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <div class="mt-2 d-flex flex-wrap gap-2" id="attachmentsPreview"></div>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">Trọng lượng</label>
                            <input class="form-control" name="weight" type="text" value="{{ old('weight') }}" />
                            @error('weight')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Số lượng</label>
                            <input class="form-control" name="quantity" type="number" value="{{ old('quantity') }}" />
                            @error('quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Số lượng tồn kho</label>
                            <input class="form-control" name="quantity_warning" type="number"
                                value="{{ old('quantity_warning') }}" />
                            @error('quantity_warning')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Tags (phân cách bởi dấu phẩy)</label>
                            <input class="form-control" name="tags" type="text" value="{{ old('tags') }}" />
                            @error('tags')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SKU</label>
                            <input class="form-control" name="sku" type="text" value="{{ old('sku') }}" />
                            @error('sku')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input name="active" type="hidden" value="0" />
                                <input checked class="form-check-input" id="activeCheck" name="active" type="checkbox"
                                    value="1" />
                                <label class="form-check-label" for="activeCheck">Kích hoạt sản phẩm</label>
                                @error('active')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Hiển thị các thuộc tính sản phẩm --}}
                    @foreach ($attributes as $attribute)
                        <div class="mb-3">
                            <label class="fw-bold d-block">{{ $attribute->name }}</label>
                            @foreach ($attribute->attributeValues as $value)
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" class="form-check-input attr-checkbox"
                                        name="attributes[{{ $attribute->id }}][]"
                                        data-attr-name="{{ $attribute->name }}" data-attr-id="{{ $attribute->id }}"
                                        value="{{ $value->id }}">
                                    <label class="form-check-label">{{ $value->value }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    {{-- Khu vực hiển thị các biến thể sản phẩm --}}
                    <div class="mt-4">
                        <h5>Biến thể sản phẩm</h5>
                        <div id="variant-list"></div>
                    </div>
                    <input type="hidden" name="variants_json" id="variants_json">





                    <div class="d-flex justify-content-between mt-3">
                        <a class="btn btn-secondary" href="{{ route('products.index') }}">⬅ Quay lại</a>
                        <button class="btn btn-success" type="submit">Tạo sản phẩm</button>
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

        $(document).ready(function() {
            $('#summernote').summernote({
                height: 150
            });
        });
    </script>
    <script>
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
                    variantList.innerHTML =
                        '<div class="text-muted">Chọn ít nhất 2 thuộc tính (VD: Màu và Size)</div>';
                    variantsJsonInput.value = '';
                    return;
                }
                const combos = generateCombinations(attrMap);
                variantList.innerHTML = '';
                const variantsData = [];
                combos.forEach((combo, index) => {
                    const block = document.createElement('div');
                    block.className = 'border p-3 rounded mb-3';
                    block.innerHTML = `
                    <strong>${combo.label}</strong>
                    <input type="hidden" name="variant_keys[${index}]" value="${combo.ids}">
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label>SKU</label>
                            <input type="text" name="variants[${index}][sku]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>Giá</label>
                            <input type="number" step="0.01" name="variants[${index}][price]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>Số lượng</label>
                            <input type="number" name="variants[${index}][quantity]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label>Trọng lượng</label>
                            <input type="text" name="variants[${index}][weight]" class="form-control">
                        </div>
                        <div class="col-md-6 mt-2">
                            <label>Ảnh biến thể</label>
                            <input type="file" name="variants[${index}][variant_image]" class="form-control-file">
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
    </script>
@endsection
