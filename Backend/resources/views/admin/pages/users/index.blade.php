@extends('admin.layouts.master')
@section('title', 'Danh sách người dùng')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


@endsection
@section('content')
    @can('user-list')
        <div class="card">
            <div class="card-header">
                <div class="card-title">Danh sách người dùng</div>
            </div>
            <div class="card-body">
                <table id="listuser" class="table table-striped display" style="width:100%">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>ROLES</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="p-2">{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                                <td>
                                    @can('user-assign')
                                        <a href="{{ route('users.roles.edit', $user->id) }}" class="btn btn-primary"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Assgin"><i
                                                class="fa-brands fa-atlassian"></i></a>
                                    @endcan

                                    <a href="" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Stop"><i class="fa-solid fa-stop"></i></a>
                                    @can('profile-show')
                                        <a href="{{ route('profiles.show', $user->id) }}" class="btn btn-secondary"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Profile"><i
                                                class="fa-solid fa-address-card"></i></a>
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
        new DataTable('#listuser');
    </script>
@endsection
