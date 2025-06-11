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
                                    <img src="{{ asset('storage/' . $product->product_image) }}" width="60">
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ number_format($product->price, 0, ',', '.') }}₫</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->attachments_count }}</td>
                            <td>
                                @if ($product->active === '1')
                                    <span class="badge bg-success">Hiện</span>
                                @else
                                    <span class="badge bg-secondary">Ẩn</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Xóa</button>
                                </form>
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
@endsection
