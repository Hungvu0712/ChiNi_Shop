@extends('admin.layouts.master')
@section('title', 'Danh sách sản phẩm')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Danh sách sản phẩm</div>
        </div>
        <div class="card-body">
            <table id="listproduct" class="table table-striped display" style="width:100%">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Ảnh</th>
                        <th>Tên</th>
                        <th>Giá</th>
                        <th>SL tồn</th>
                        <th>Ảnh đính kèm</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $index => $product)
                        <tr>
                            <td>{{ $products->firstItem() + $index }}</td>
                            <td>
                                @if ($product->product_image)
                                    <img src="{{ asset($product->product_image ?? 'images/default.jpg') }}" width="100px"
                                        alt="brand">
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} vnđ</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->attachments_count }}</td>
                            <td>
                                <span class="badge {{ $product->active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $product->active ? 'Hiện' : 'Ẩn' }}
                                </span>
                            </td>
                            <td style="background-color: #f9f9f9;" class="px-3 py-2">
                                <div class="d-flex gap-2 align-items-center h-100">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-info">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <form action="{{ route('products.destroy', $product->id) }}"
                                        id="delete-form-{{ $product->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" data-id="{{ $product->id }}"
                                            class="btn btn-danger delete-button">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    @if ($products->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center">Không có sản phẩm nào.</td>
                        </tr>
                    @endif
                </tbody>

            </table>
            {{ $products->links() }} <!-- Pagination -->
        </div>
    </div>
@endsection
@section('script')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        new DataTable('#listproduct');
    </script>
    <script>
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xoá?',
                    text: 'Thao tác này không thể hoàn tác!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Xoá',
                    cancelButtonText: 'Huỷ bỏ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            });
        });
    </script>
@endsection
