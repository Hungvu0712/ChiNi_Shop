@extends('admin.layouts.master')
@section('title', 'Danh sách')
@section('css')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

@endsection
@section('content')
@can('crudbaner')
<div class="card" style="width: 100%">
    <div class="card-header">
        <div class="card-title">Danh sách Banner</div>
    </div>

    <div class="card-body">
        <a href="{{ route('banners.create') }}" class="btn btn-success mb-5">Thêm Banners</a>
        <table id="listbanner" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>TITLE</th>
                    <th>IMAGE</th>
                    <th>STATUS</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($banners as $banner)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $banner->title }}</td>
                    <td>
                        <img src="{{ $banner->banner_image }}" alt="Banner Image" style="width: 200px; height: 100px;">
                    </td>
                    <td>
                        @if ($banner->active)
                        <span class="badge bg-success">Đang hiển thị</span>
                        @else
                        <span class="badge bg-secondary">Đang ẩn</span>
                        @endif
                    </td>
                    <td class="d-flex gap-2">
                        <form action="{{ route('banners.destroy', $banner->id) }}" id="delete-form-{{ $banner->id }}"
                            method="post">
                            @csrf
                            @method('delete')
                            <button type="button" data-id="{{ $banner->id }}" class="btn btn-danger delete-button"><i
                                    class="fa-solid fa-trash"></i>
                            </button>
                        </form>

                        <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-info">
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
    new DataTable('#listbanner');


         $('#listbanner').on('click', '.delete-button', function () {
    const id = $(this).data('id');

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
</script>
@endsection
