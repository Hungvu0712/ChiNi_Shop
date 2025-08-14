@extends('admin.layouts.master')
@section('title', 'Danh sách đơn hàng')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<style>
    .order-table th,
    .order-table td {
        vertical-align: middle;
    }
</style>
@endsection
@section('content')
@can('crudorder')
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
                    <td class="payment-status-cell" data-order-id="{{ $order->id }}">
                        {{ $order->payment_status }}
                    </td>

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
                        <select class="form-select order-status-select" name="order_status"
                            data-order-id="{{ $order->id }}">
                            @foreach (\App\Models\Order::getOrderStatuses() as $item)
                            <option value="{{ $item }}" {{ $order->order_status === $item ? 'selected' : '' }}>{{ $item
                                }}
                            </option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <a href="{{ route('orders.show', $order->id) }}"><button class="btn btn-success text-center"><i
                                    class="bi bi-eye"></i> 👁</button></a>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>

</div>
@endcan

@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const selectElements = document.querySelectorAll('.order-status-select');

            selectElements.forEach(selectElement => {
                let oldValue = selectElement.value;

                selectElement.addEventListener('focus', function() {
                    oldValue = this.value;
                });

                selectElement.addEventListener('change', function() {
                    const orderId = this.dataset.orderId;
                    const orderStatus = this.value;

                    const apiUrl = `{{ route('orders.update', ['order' => '__ID__']) }}`.replace(
                        '__ID__', orderId);

                    fetch(apiUrl, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                order_status: orderStatus
                            })
                        })
                        .then(async response => {
                            const data = await response.json();

                            if (!response.ok) {
                                toastr.error(`❌ ${data.message || 'Có lỗi xảy ra!'}`);
                                selectElement.value = oldValue;
                                throw new Error(data.message || 'Request failed');
                            }

                            toastr.success(`✅ ${data.message || 'Cập nhật thành công!'}`);
                            if (data.order && data.order.payment_status) {
                                const td = document.querySelector(
                                    `.payment-status-cell[data-order-id="${data.order.id}"]`
                                    );
                                if (td) {
                                    td.textContent = data.order.payment_status;
                                }
                            }
                            oldValue = orderStatus;
                            return data;
                        })
                        .catch(error => {
                            selectElement.value = oldValue;
                            console.error('Error:', error);
                        });
                });
            });
        });
</script>
@endsection