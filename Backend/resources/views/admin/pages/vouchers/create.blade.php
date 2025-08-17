@extends('admin.layouts.master')
@section('title', 'Thêm mới')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
    <style>
        .note-icon-caret:before {
            content: "" !important;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Tạo Voucher</div>
        </div>
        <div class="card-body">
            <form action="{{ route('vouchers.store') }}" method="post">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Mã voucher:</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}"
                        placeholder="Nhập mã voucher">
                    @error('code')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tiêu đề:</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                        placeholder="Nhập tiêu đề voucher">
                    @error('title')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Loại voucher:</label>
                    <select name="voucher_type" id="voucher_type" class="form-select">
                        <option value="discount" {{ old('voucher_type') == 'discount' ? 'selected' : '' }}>Discount</option>
                        <option value="freeship" {{ old('voucher_type') == 'freeship' ? 'selected' : '' }}>Freeship</option>
                    </select>
                    @error('voucher_type')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                <div id="discount_fields">
                    <div class="mb-3">
                        <label class="form-label">Kiểu giảm:</label>
                        <select name="discount_type" id="discount_type" class="form-select">
                            <option value="amount" {{ old('discount_type') == 'amount' ? 'selected' : '' }}>Số tiền</option>
                            <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Phần trăm
                            </option>
                        </select>

                        @error('discount_type')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giá trị giảm:</label>
                        <input type="number" step="0.01" name="value" class="form-control"
                            value="{{ old('value') }}" placeholder="Giá trị giảm">
                        @error('value')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giá trị đơn hàng tối thiểu:</label>
                        <input type="number" step="0.01" name="min_order_value" class="form-control"
                            value="{{ old('min_order_value') }}">
                        @error('min_order_value')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="max-discount-group">
                        <label class="form-label">Mức giảm tối đa:</label>
                        <input type="number" step="0.01" name="max_discount_value" id="max_discount_value"
                            class="form-control" value="{{ old('max_discount_value') }}">
                        @error('max_discount_value')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label">Ngày bắt đầu:</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                    @error('start_date')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Ngày kết thúc:</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                    @error('end_date')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Giới hạn lượt dùng:</label>
                    <input type="number" name="limit" class="form-control" value="{{ old('limit', 1) }}">
                    @error('limit')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                        {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label class="form-check-label">Kích hoạt</label>
                    @error('is_active')
                        <div style="color: red">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <a href="{{ route('vouchers.index') }}" onclick="return confirm('bạn chắc chắn muốn quay lại?')"
                        class="btn btn-secondary">
                        Quay lại
                    </a>

                    <input type="submit" value="Submit" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Summernote khởi tạo
            $('#summernote').summernote({
                height: 300
            });

            // Toggle nhóm field theo loại voucher
            function toggleVoucherFields() {
                var type = $('#voucher_type').val();
                if (type === 'discount') {
                    $('#discount_fields').show();
                } else {
                    $('#discount_fields').hide();
                }
            }

            // Toggle mức giảm tối đa theo discount_type
            function toggleMaxDiscount() {
                var discountType = $('#discount_type').val();
                var $maxDiscountGroup = $('#max-discount-group');
                var $maxDiscountInput = $('#max_discount_value');

                if (discountType === 'amount' || discountType === 'percent') {
                    $maxDiscountGroup.hide();
                    $maxDiscountInput.val(0);
                } else {
                    $maxDiscountGroup.show();
                }
            }

            // Gọi khi load trang
            toggleVoucherFields();
            toggleMaxDiscount();

            // Gọi khi thay đổi
            $('#voucher_type').change(function() {
                toggleVoucherFields();
            });

            $('#discount_type').change(function() {
                toggleMaxDiscount();
            });
        });
    </script>

@endsection
