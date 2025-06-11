@extends('admin.layouts.master')
@section('title', 'Danh sách')
@section('css')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

@endsection
@section('content')
    <div class="card" style="width: 100%">
        <div class="card-header">
            <div class="card-title">Danh sách Vai trò</div>
        </div>

        <div class="card-body">
            <a href="{{ route('roles.create') }}" class="btn btn-success mb-5">Thêm Role</a>
            <table id="listrole" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>NAME</th>
                        <th>GUARD_NAME</th>
                        <th>ASGIN PERMSSION</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->guard_name }}</td>
                            <td>
                                {{ $role->permissions->pluck('name')->implode(', ') }}
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('roles.editPermissions', $role->id) }}"
                                    class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Assgin">
                                    <i class="fa-brands fa-atlassian"></i>
                                </a>
                                <form action="{{ route('roles.destroy', $role->id) }}" id="delete-form-{{ $role->id }}"
                                    method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="button" data-id="{{ $role->id }}"
                                        class="btn btn-danger delete-button"><i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>

                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-info">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
@section('script')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        new DataTable('#listrole');


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
