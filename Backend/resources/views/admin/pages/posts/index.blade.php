@extends('admin.layouts.master')
@section('title', 'Danh sách menu')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">


@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Danh sách bài viết</div>
        </div>
        <div class="card-body">
            <a href="{{ route('posts.create') }}" class="btn btn-success mb-5">Thêm bài viết</a>
            <table id="listPosts" class="table table-striped display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Slug</th>
                        <th>Danh mục</th>
                        <th>Tóm tắt</th>
                        <th>Trạng thái</th>
                        <th>Ảnh đại diện</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->slug }}</td>
                            <td>{{ $post->postCategory?->name }}</td>
                            <td>{{ $post->excerpt }}</td>
                            <td>
                                <span class="badge bg-{{ $post->status === 'published' ? 'success' : 'secondary' }}">
                                    {{ $post->status }}
                                </span>
                            </td>
                            <td>
                                <img src="{{ $post->featured_image }}" alt="" width="60">
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-info"><i
                                        class="fa-solid fa-pen-to-square"></i></a>
                                <form action="{{ route('posts.destroy', $post->id) }}" id="delete-form-{{ $post->id }}"
                                    method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" data-slug="{{ $post->id }}"
                                        class="btn btn-danger delete-button"><i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
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
        new DataTable('#listPosts');

        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-post');
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
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            });
        });
    </script>
@endsection
