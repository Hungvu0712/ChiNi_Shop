@extends('client.layouts.master')
@section('title', 'Cảm ơn')
@section('css')
    <style>
        .header01  {
                background-color: #ecf5f4;
                font-family: "Segoe UI", sans-serif;
        }

        .thank-you-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 40px 20px;
        }

        .check-icon {
            font-size: 64px;
            color: #28a745;
            border: 3px solid #28a745;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .thank-you-title {
            color: #28a745;
            font-weight: bold;
            font-size: 28px;
        }

        .thank-you-text {
            color: #555;
            font-size: 16px;
            max-width: 800px;
            margin: 10px auto;
        }

        .btn-order {
            background-color: #5b32f3;
            color: #fff;
            margin-top: 25px;
        }

        .btn-order:hover {
            background-color: #4728c9;
            color: #fff;
        }
        .container{
            margin-top: 100px;
        }
    </style>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection
@section('content')
    <div class="container mt-5">
        <div class="thank-you-container">
            <div class="check-icon">
                <i class="bi bi-check-lg"></i>
            </div>
            <h2 class="thank-you-title">Cảm ơn bạn đã mua hàng!</h2>
            <p class="thank-you-text">
                Chúng tôi rất vui mừng khi bạn đã chọn chúng tôi. Đơn hàng của bạn đang được xử lý và sẽ được giao trong
                thời
                gian sớm nhất.<br>
                Hãy kiểm tra email để biết thêm thông tin về đơn hàng. Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ
                với
                chúng tôi!<br>
                Đừng quên theo dõi chúng tôi trên các mạng xã hội để cập nhật những ưu đãi và sản phẩm mới nhất!
            </p>
            <a href="{{ route('order.index')}}" class="btn btn-order">
                ← Xem đơn hàng
            </a>
        </div>
    </div>
@endsection
@section('script')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection