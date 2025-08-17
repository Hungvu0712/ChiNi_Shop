@extends('admin.layouts.master')
@section('title', 'Dashboard')
@section('css')
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset('admin/assets/css/fonts.min.css') }}"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>
@endsection
@section('content')
    @can('dashboard')
        <div class="container">
            <div class="page-inner">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                    <div>
                        <h3 class="fw-bold mb-3">Dashboard</h3>
                    </div>
                    <div class="ms-md-auto py-2 py-md-0">
                        <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                        <a href="#" class="btn btn-primary btn-round">Add Customer</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-lg-3 mb-4">
                        <div class="card shadow-sm border-0 bg-primary text-white">
                            <div class="card-body d-flex flex-column align-items-start">
                                <h6 class="mb-1">💰 Doanh thu tháng này</h6>
                                <h4 class="mb-0">{{ number_format($monthlyRevenue, 0, ',', '.') }} ₫</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3 mb-4">
                        <div class="card shadow-sm border-0 bg-success text-white">
                            <div class="card-body d-flex flex-column align-items-start">
                                <h6 class="mb-1">📦 Đơn hàng hôm nay</h6>
                                <h4 class="mb-0">{{ $ordersToday }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3 mb-4">
                        <div class="card shadow-sm border-0 bg-warning text-white">
                            <div class="card-body d-flex flex-column align-items-start">
                                <h6 class="mb-1">👕 Sản phẩm đang bán</h6>
                                <h4 class="mb-0">{{ $activeProducts }}</h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3 mb-4">
                        <div class="card shadow-sm border-0 bg-info text-white">
                            <div class="card-body d-flex flex-column align-items-start">
                                <h6 class="mb-1">👤 Người dùng</h6>
                                <h4 class="mb-0">{{ $userCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2 align-items-center mb-3">
                            <select name="year" class="form-select w-auto">
                                @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}
                                    </option>
                                @endfor
                            </select>
                            <select name="month" class="form-select w-auto">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>Tháng
                                        {{ $m }}</option>
                                @endfor
                            </select>
                            <button class="btn btn-sm btn-primary">Lọc</button>
                        </form>

                        <div class="card card-round mb-4">
                            <div class="card-header">
                                <div class="card-title">📊 Doanh thu theo tháng ({{ $year }})</div>
                            </div>
                            <div class="card-body">
                                <canvas id="monthlyRevenueChart" height="100"></canvas>
                            </div>
                        </div>

                        <div class="card card-round">
                            <div class="card-header">
                                <div class="card-title">📈 Đơn hàng theo ngày (Tháng {{ $month }}/{{ $year }})
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="ordersPerDayChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-round">
                            <div class="card-header">
                                <div class="card-title">📍 Đơn hàng theo thành phố</div>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Thành phố</th>
                                            <th>Số đơn hàng</th>
                                            <th>Tỷ lệ (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orderPerCityLabels as $index => $city)
                                            <tr>
                                                <td>{{ $city }}</td>
                                                <td>{{ $orderPerCityCounts[$index] }}</td>
                                                <td>{{ $orderPerCityPercentages[$index] }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-round">
                            <div class="card-header">
                                <div class="card-title">📦 Thống kê đơn hàng theo trạng thái</div>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Trạng thái đơn hàng</th>
                                            <th>Số lượng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orderStatusLabels as $index => $status)
                                            <tr>
                                                <td>{{ $status }}</td>
                                                <td>{{ $orderStatusCounts[$index] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-round">
                            <div class="card-header">
                                <div class="card-title">🔥 Top sản phẩm bán chạy nhất</div>
                            </div>
                            <div class="card-body">
                                <canvas id="topSellingChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-round">
                            <div class="card-header">
                                <div class="card-title">📊 Thống kê sản phẩm theo danh mục</div>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Danh mục</th>
                                            <th>Số sản phẩm</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productCategoryLabels as $index => $category)
                                            <tr>
                                                <td>{{ $category }}</td>
                                                <td>{{ $productCategoryCounts[$index] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endcan
@endsection
@section('script')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="{{ asset('admin/assets/js/demo.js') }}"></script>
    <script>
        $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#177dff",
            fillColor: "rgba(23, 125, 255, 0.14)",
        });

        $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#f3545d",
            fillColor: "rgba(243, 84, 93, .14)",
        });

        $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#ffa534",
            fillColor: "rgba(255, 165, 52, .14)",
        });
    </script>
    <script>
        const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(monthlyRevenueCtx, {
            type: 'bar',
            data: {
                labels: [
                    'Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6',
                    'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'
                ],
                datasets: [{
                    label: 'Doanh thu',
                    data: @json($revenuePerMonth),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ordersPerDayCtx = document.getElementById('ordersPerDayChart').getContext('2d');
        new Chart(ordersPerDayCtx, {
            type: 'bar',
            data: {
                labels: [...Array({{ \Carbon\Carbon::create($year, $month)->daysInMonth }}).keys()].map(i =>
                    `Ngày ${i+1}`),
                datasets: [{
                    label: 'Số đơn hàng',
                    data: @json($ordersPerDay),
                    backgroundColor: 'rgba(255, 99, 132, 0.7)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script>
        const ctxStatus = document.getElementById('orderStatusChart').getContext('2d');

        const orderStatusChart = new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: @json($orderStatusLabels),
                datasets: [{
                    data: @json($orderStatusCounts),
                    backgroundColor: [
                        '#28a745', // green - Đã giao
                        '#ffc107', // yellow - Đang xử lý
                        '#dc3545', // red - Đã huỷ
                        '#6c757d' // grey - Chờ thanh toán
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#000',
                            font: {
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                return `${label}: ${value} đơn hàng`;
                            }
                        }
                    }
                }
            }
        });
    </script>
    <script>
        const ctxTopSelling = document.getElementById('topSellingChart').getContext('2d');
        new Chart(ctxTopSelling, {
            type: 'bar',
            data: {
                labels: @json($topProductLabels),
                datasets: [{
                    label: 'Số lượng bán',
                    data: @json($topProductQuantities),
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderRadius: 5
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>
@endsection
