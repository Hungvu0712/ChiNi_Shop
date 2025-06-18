@extends('admin.layouts.master')
@section('title', 'Danh sách')
@section('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

@endsection
@section('content')
    @can('brand-list')
        <div class="card" style="width: 100%">
            <div class="card-header">
                <div class="card-title">Danh sách Brands</div>
            </div>

            <div class="card-body">
                @can('brand-create')
                    <a href="{{ route('brands.create') }}" class="btn btn-success mb-5">Thêm Brands</a>
                @endcan

                <table id="listbrand" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>NAME</th>
                            <th>IMAGE</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $brand->name }}</td>
                                <td>
                                    <img src="{{ asset($brand->brand_image ?? 'images/default.jpg') }}" alt="brand"
                                        width="100px" height="100px">
                                </td>
                                <td class="d-flex gap-2">
                                    @can('brand-delete')
                                        <form action="{{ route('brands.destroy', $brand->id) }}"
                                            id="delete-form-{{ $brand->id }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="button" data-id="{{ $brand->id }}"
                                                class="btn btn-danger delete-button"><i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan

                                    @can('brand-edit')
                                        <a href="{{ route('brands.edit', $brand->id) }}" class="btn btn-info">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    @endcan
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
        new DataTable('#listbrand');


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
