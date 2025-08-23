@extends('admin.layouts.master')
@section('title', 'Danh sách menu')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


@endsection
@section('content')
@can('crudpostcategory')
<div class="card">
    <div class="card-header">
        <div class="card-title">Danh sách danh mục bài viết</div>
    </div>
    <div class="card-body">
        <a href="{{ route('post-categories.create') }}" class="btn btn-success mb-5">Thêm danh mục bài viết</a>
        <table id="listPostCategories" class="table table-striped display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NAME</th>
                    <th>SLUG</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($postCategories as $pc)
                <tr>
                    <td>{{ $pc->id }}</td>
                    <td>{{ $pc->name }}</td>
                    <td>{{ $pc->slug }}</td>
                    <td>{{ $pc->description }}</td>
                    <td class="d-flex gap-2">
                        <a href="{{ route('post-categories.edit', $pc->slug) }}" class="btn btn-info"><i
                                class="fa-solid fa-pen-to-square"></i></a>
                        <form action="{{ route('post-categories.destroy',$pc->slug) }}" id="delete-form-{{ $pc->slug }}"
                            method="post">
                            @csrf
                            @method('delete')
                            <button type="button" data-slug="{{ $pc->slug }}" class="btn btn-danger delete-button"><i
                                    class="fa-solid fa-trash"></i>
                            </button>
                        </form>
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
    new DataTable('#listPostCategories');

         $('#listPostCategories').on('click', '.delete-button', function () {
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
