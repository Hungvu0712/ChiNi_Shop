@extends('admin.layouts.master')

@section('title', 'Quản lý bình luận')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
@endsection

@section('content')
@can('review')
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-comments text-primary"></i> Danh sách bình luận</h2>

    <table class="table table-bordered" id="listreview">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Người dùng</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reviews as $review)
            <tr>
                <td>{{ $review->id }}</td>
                <td>{{ $review->product->name ?? 'N/A' }}</td>
                <td>{{ $review->user->name ?? 'N/A' }}</td>
                <td>
                    @for ($i = 1; $i <= 5; $i++) <i
                        class="fa-star {{ $i <= $review->rating ? 'fas text-warning' : 'far text-muted' }}"></i>
                        @endfor
                </td>
                <td>{{ $review->review }}</td>
                <td>{{ $review->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <button class="btn btn-danger btn-sm delete-button" data-id="{{ $review->id }}">
                        <i class="fas fa-trash"></i>
                    </button>

                    @can('review')
                    <form id="delete-form-{{ $review->id }}" action="{{ route('admin.reviews.destroy', $review->id) }}"
                        method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endcan

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    new DataTable('#listreview');

         $('#listPosts').on('click', '.delete-button', function () {
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
