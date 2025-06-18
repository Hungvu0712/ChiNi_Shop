@extends('admin.layouts.master')
@section('title', 'Danh sách menu')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


@endsection
@section('content')
     <div class="card">
        <div class="card-header">
            <div class="card-title">Danh sách Menu</div>
        </div>
        <div class="p-4 border rounded shadow-sm bg-light">
            <a href="{{ route('menus.create') }}" class="btn btn-primary mb-3">Create Menu</a>
            <ul class="list-group">
                @foreach ($menus as $menu)
                    <li class="list-group-item" style="display: block !important; border-width: 1px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">{{ $menu->name }}</span>
                            <div>
                                <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-info me-2"><i
                                        class="fas fa-pen-square"></i></a>

                                <form action="{{ route('menus.destroy', $menu->id) }}" method="POST"
                                    class="d-inline" id="delete-form-{{ $menu->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" data-id="{{ $menu->id }}" class="btn btn-danger delete-button-menu"><i
                                        class="fas fa-times-circle"></i></button>
                                </form>
                            </div>
                        </div>

                        @if ($menu->children->count())
                            @foreach ($menu->children as $child)
                    <li class="list-group-item" style="display: block !important; margin-left: 30px; border-width: 1px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>{{ $child->name }} <span class="sub-menu">(sub menu)</span></span>
                            <div>
                                <a href="{{ route('menus.edit', $child->id) }}" class="btn btn-info me-2"><i
                                        class="fas fa-pen-square"></i></a>
                                <form action="{{ route('menus.destroy', $child->id) }}" method="POST"
                                    class="d-inline" id="delete-form-{{ $child->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" data-id="{{ $child->id }}" class="btn btn-danger delete-button"><i
                                        class="fas fa-times-circle"></i></button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
                @endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
@section('script')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.querySelectorAll('.delete-button-menu').forEach(button => {
        button.addEventListener('click', function() {
            const menuId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Menu này sẽ bị xóa",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Có, xóa nó!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${menuId}`).submit();
                }
            });
        });
    });
</script>

<script>
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
            const childId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Menu con này sẽ bị xóa",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Có, xóa nó!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${childId}`).submit();
                }
            });
        });
    });
</script>
@endsection
