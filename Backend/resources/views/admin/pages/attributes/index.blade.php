@extends('admin.layouts.master')
@section('title', 'Danh sách')
@section('css')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

@endsection
@section('content')
@can('attribute-list')
<div class="card" style="width: 100%">
    <div class="card-header">
        <div class="card-title">Danh sách Attribute</div>
    </div>

    <div class="card-body">
        @can('attribute-create')
        <a href="{{ route('attributes.create') }}" class="btn btn-success mb-5">Thêm Attribute</a>
        @endcan
        <table id="listattribute" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>NAME</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($attributes as $attribute)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $attribute->name }}</td>
                    <td class="d-flex gap-2">
                        @can('attribute-delete')
                        <form action="{{ route('attributes.destroy', $attribute->id) }}"
                            id="delete-form-{{ $attribute->id }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="button" data-id="{{ $attribute->id }}" class="btn btn-danger delete-button"><i
                                    class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @endcan

                        @can('attribute-edit')
                        <a href="{{ route('attributes.edit', $attribute->id) }}" class="btn btn-info">
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
    new DataTable('#listattribute');


         $('#listattribute').on('click', '.delete-button', function () {
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
