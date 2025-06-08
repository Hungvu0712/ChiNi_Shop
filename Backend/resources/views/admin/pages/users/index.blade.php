@extends('admin.layouts.master')
@section('title', 'Danh sách người dùng')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Danh sách Rooms</div>
        </div>
        <div class="card-body">
            <table id="listuser" class="table table-striped display" style="width:100%">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="p-2">{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td>
                                <a href="" class="btn btn-danger">Chặn Tài Khoản</a>
                                <a href="" class="btn btn-danger">Xóa</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
@section('script')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

    <script>
        $('#example').dataTable({
            paging: false
        });
    </script>
@endsection
