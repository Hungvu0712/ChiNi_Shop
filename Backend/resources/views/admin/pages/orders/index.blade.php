@extends('admin.layouts.master')
@section('title', 'Danh sách đơn hàng')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        .order-table th,
        .order-table td {
            vertical-align: middle;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">Danh sách đơn hàng</div>
        </div>
        <!-- Bảng đơn hàng -->
        <!-- Bảng đơn hàng -->
        <div class="table-responsive">
            <table class="table table-bordered order-table align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th>STT</th>
                        <th>Tài khoản</th>
                        <th>Mã đơn hàng</th>
                        <th>Trạng thái</th>
                        <th>Phương thức thanh toán</th>
                        <th>Trạng thái đơn hàng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $index => $order)
                        {{-- @foreach ($order->orderDetails as $detailIndex => $detail) --}}
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->user_name }}</td>

                            <td>{{ $order->order_code }}</td>
                            <td>{{ $order->payment_status }}</td>

                            <td>
                                @switch($order->payment_method_id)
                                    @case(1)
                                        Thanh toán khi nhận hàng
                                    @break

                                    @case(2)
                                        Chuyển khoản ngân hàng
                                    @break

                                    @default
                                        Khác
                                @endswitch
                            </td>
                            <td>
                                <select class="form-select">
                                    <option {{ $order->order_status === 'Đang chờ xác nhận' ? 'selected' : '' }}>Đang chờ
                                        xác nhận</option>
                                    <option {{ $order->order_status === 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận
                                    </option>
                                    <option {{ $order->order_status === 'Đang vận chuyển' ? 'selected' : '' }}>Đang vận
                                        chuyển</option>
                                    <option {{ $order->order_status === 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành
                                    </option>
                                </select>
                            </td>

                            <td>
                                <a href="{{ route('orders.show', $order->id) }}"><button
                                        class="btn btn-success text-center"><i class="bi bi-eye"></i> 👁</button></a>

                            </td>
                        </tr>
                        {{-- @endforeach --}}
                    @endforeach
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>

    </div>

@endsection
@section('script')

@endsection
