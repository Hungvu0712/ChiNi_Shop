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
           <div class="card-header bg-primary bg-gradient text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Thêm mới sản phẩm</h4>
                            <a href="{{ route('products.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" enctype="multipart/form-data" method="POST">
                    @csrf
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
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : ''
                                            }}>
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
                        <textarea class="form-control" id="summernote" name="description"
                            rows="10">{{ old('description') }}</textarea>
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

                    <div class="col-12">
                        <div class="mb-3">
                            <label for="attributeSelect">Chọn thuộc tính</label>
                            <select class="form-control @error('attributeId') is-invalid @enderror" id="attributeSelect"
                                multiple name="attributeId[]">
                                @foreach ($attributes as $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                @endforeach
                            </select>
                            @error('attributeId')
                                <p class="invalid-feedback d-block">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div id="attributeForms" class="col-12"></div>
                    @error('attributeValues')
                            <p class="invalid-feedback d-block">{{ $message }}</p>
                    @enderror
                    <div class="col-12">
                        <button type="button" id="saveAttributes" class="btn btn-primary">Lưu thuộc tính</button>
                    </div>
                    <div id="variantSection" class="col-12 mt-5"></div>
                    @error('product_variants')
                        <p class="invalid-feedback d-block">{{ $message }}</p>
                    @enderror

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a class="btn btn-secondary" href="{{ route('products.index') }}">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                        <button  class="btn btn-success" type="submit">
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

    <script>
        // Xử lý hiển thị ảnh chính
        const productImageInput = document.getElementById('product_image');
        const mainImagePreview = document.getElementById('mainImagePreview');
        const mainImageWrapper = document.getElementById('mainImageWrapper');

        productImageInput.addEventListener('change', function (e) {
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

        attachmentsInput.addEventListener('change', function (e) {
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
                reader.onload = function (e) {
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
                    btn.onclick = function () {
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

    {{-- Product variant --}}
    <script> 
        var attributeValues = @json($attributeValues);
        var attributeNames = @json($attributeNames);
        document.addEventListener('DOMContentLoaded', function () {

            // Khi chọn thuộc tính, sẽ hiển thị các giá trị thuộc tính tương ứng
            document.getElementById('attributeSelect').addEventListener('change', function () {
                let selectedAttributes = Array.from(this.selectedOptions).map(option => option.value);
                let attributeFormsContainer = document.getElementById('attributeForms');

                // Xóa các form con trước khi tạo lại
                attributeFormsContainer.innerHTML = '';

                selectedAttributes.forEach(attributeId => {
                    if (attributeValues[attributeId] && Object.keys(attributeValues[attributeId]).length > 0) {
                        let options = '';
                        for (const [id, name] of Object.entries(attributeValues[attributeId])) {
                            options += `<option value="${id}">${name}</option>`;
                        }
                        let attributeName = attributeNames[attributeId] || '---';
                        let formGroup = document.createElement('div');
                        formGroup.classList.add('mb-3');
                        formGroup.innerHTML = `
                                                                                    <label for="attributeValue_${attributeId}">Chọn giá trị cho thuộc tính <strong>${attributeName}</strong></label>
                                                                                    <select class="form-control" id="attributeValue_${attributeId}" name="attributeValues[${attributeId}][]" multiple>
                                                                                        ${options}
                                                                                    </select>
                                                                                `;
                        attributeFormsContainer.appendChild(formGroup);
                    }
                });
            });

            // Khi người dùng nhấn "Lưu thuộc tính", tạo bảng biến thể sản phẩm
            document.getElementById('saveAttributes').addEventListener('click', function () {
                let selectedAttributes = document.querySelectorAll('select[name^="attributeValues"]');
                let attributeCombinations = getCombinations(selectedAttributes);
                let variantSection = document.getElementById('variantSection');
                const isValid = attributeCombinations.length > 0 &&
                    attributeCombinations.every(comb => comb.length > 0);

                if (!isValid) return; // không render gì cả nếu không có thuộc tính

                // Xóa bảng trước nếu có
                variantSection.innerHTML = '';

                if (attributeCombinations.length > 0) {
                    let tableHtml = `
                                                            <h3>Biến thể sản phẩm</h3>
                                                            <table class="table table-hover" id="variantTable">
                                                                <thead>
                                                                    <tr>
                                                                        ${Array.from(selectedAttributes).map((select) => {
                        let match = select.name.match(/attributeValues\[(\d+)\]/);
                        let attributeId = match ? match[1] : null;
                        let attributeName = attributeNames[attributeId] || 'Thuộc tính';
                        return `<th>${attributeName}</th>`;
                    }).join('')}
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

                    attributeCombinations.forEach((combination,rowIndex) => {

                        let attributeCells = combination.map((attr,index) => `
                                                               <td data-attribute-id="${attr.attributeId}" data-value-id="${attr.id}">
                                                ${attr.name}
                                                <input type="hidden" name="product_variants[${rowIndex}][attribute_item_id][${index}][id]" value="${attr.id}">
            <input type="hidden" name="product_variants[${rowIndex}][attribute_item_id][${index}][value]" value="${attr.name}">
                                            </td>
                                                            `).join('');

                        tableHtml += `
                                                                <tr>
                                                                    ${attributeCells}
                                                                    <td><input type="text" class="form-control" name="product_variants[${rowIndex}][sku]" placeholder="SKU"></td>
                                                                    <td><input type="text" class="form-control" name="product_variants[${rowIndex}][price]" placeholder="Giá"></td>
                                                                    <td><input type="text" class="form-control" name="product_variants[${rowIndex}][quantity]" placeholder="Số lượng"></td>
                                                                    <td><input type="text" class="form-control" name="product_variants[${rowIndex}][weight]" placeholder="Cân nặng"></td>
                                                                    <td><input type="file" class="form-control" name="product_variants[${rowIndex}][variant_image]"></td>
                                                                    <td><button type="button" class="btn btn-danger btn-sm delete-variant">Xóa</button></td>
                                                                </tr>
                                                            `;
                    });
                    tableHtml += `</tbody></table>`;
                    variantSection.insertAdjacentHTML('beforeend', tableHtml);
                }
                // Gọi gắn sự kiện nút Xóa
                document.querySelectorAll('.delete-variant').forEach(button => {
                    button.addEventListener('click', function () {
                        handleDeleteRow(this);
                    });
                });
            });

            // Hàm lấy các tổ hợp giá trị thuộc tính
            function getCombinations(selectElements) {
                let attributeValues = Array.from(selectElements).map(select => {
                    // Lấy attributeId từ name="attributeValues[<id>][]"
                    let match = select.name.match(/attributeValues\[(\d+)\]/);
                    let attributeId = match ? match[1] : null;

                    return Array.from(select.selectedOptions).map(option => ({
                        id: option.value,
                        name: option.text,
                        attributeId: attributeId // <- Bổ sung tại đây
                    }));
                });

                // Nếu chỉ chọn một thuộc tính, trả về giá trị thuộc tính đó
                if (attributeValues.length === 1) {
                    return attributeValues[0].map(item => [item]);
                }

                // Hàm đệ quy để kết hợp các giá trị thuộc tính
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