@extends('admin.layouts.master')
@section('title', 'Danh sách')
@section('css')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

@endsection
@section('content')
@can('crudvoucher')
<div class="card" style="width: 100%">
    <div class="card-header">
        <div class="card-title">Danh sách Voucher</div>
    </div>

    <div class="card-body">
        <a href="{{ route('vouchers.create') }}" class="btn btn-success mb-5">Thêm Voucher</a>

        <table id="listvoucher" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã</th>
                    <th>Tiêu đề</th>
                    <th>Loại</th>
                    <th>Giá trị</th>
                    <th>Kiểu giảm</th>
                    <th>Đơn tối thiểu</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Lượt dùng</th>
                    <th>Trạng thái</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($vouchers as $voucher)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $voucher->code }}</td>
                    <td>{{ $voucher->title }}</td>
                    <td>{{ ucfirst($voucher->voucher_type) }}</td>
                    <td>
                        {{ $voucher->discount_type == 'percent' ? $voucher->value . '%' : number_format($voucher->value)
                        . ' đ' }}
                    </td>
                    <td>{{ $voucher->discount_type }}</td>
                    <td>{{ number_format($voucher->min_order_value) }} đ</td>
                    <td>{{ \Carbon\Carbon::parse($voucher->start_date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($voucher->end_date)->format('d/m/Y') }}</td>
                    <td>{{ $voucher->limit }}</td>
                    <td>
                        @if ($voucher->is_active)
                        <span class="badge bg-success">Đang hoạt động</span>
                        @else
                        <span class="badge bg-secondary">Tạm tắt</span>
                        @endif
                    </td>
                    <td class="d-flex gap-2">
                        <form action="{{ route('vouchers.destroy', $voucher->id) }}" id="delete-form-{{ $voucher->id }}"
                            method="post">
                            @csrf
                            @method('delete')
                            <button type="button" data-id="{{ $voucher->id }}" class="btn btn-danger delete-button">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>

                        <a href="{{ route('vouchers.edit', $voucher->id) }}" class="btn btn-info">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endcan



@endsection
@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    new DataTable('#listvoucher');


        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Thông tin này xã bị xóa!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Có, xóa nó!',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${userId}`).submit();
                    }
                });
            });
        });
</script>
@endsection