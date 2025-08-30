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
                                <h6 class="mb-1">💰 Tổng doanh thu</h6>
                                <h4 class="mb-0">{{ number_format($totalRevenue, 0, ',', '.') }} ₫</h4>
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
                        <form method="GET" action="{{ route('dashboard') }}" class="row g-3 align-items-end mb-3">
                            {{-- Từ ngày --}}
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Từ ngày</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                    class="form-control">
                            </div>

                            {{-- Đến ngày --}}
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">Đến ngày</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                    class="form-control">
                            </div>

                            {{-- Tháng --}}
                            <div class="col-md-2">
                                <label for="month" class="form-label">Tháng</label>
                                <select name="month" id="month" class="form-select">
                                    <option value="">-- Chọn tháng --</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                            Tháng {{ $m }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            {{-- Năm --}}
                            <div class="col-md-2">
                                <label for="year" class="form-label">Năm</label>
                                <select name="year" id="year" class="form-select">
                                    <option value="">-- Chọn năm --</option>
                                    @for ($y = date('Y'); $y >= 2000; $y--)
                                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            {{-- Nút lọc --}}
                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary">Lọc</button>
                            </div>
                        </form>


                        <div class="card card-round mb-4">
                            <div class="card-header">
                                <div class="card-title">
                                    📊 Doanh thu
                                    @if ($startDate && $endDate)
                                        theo ngày (
                                        {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}
                                        →
                                        {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
                                        )
                                    @else
                                        theo tháng ({{ $year }})
                                    @endif
                                </div>
                            </div>

                            <div class="card-body">
                                <canvas id="revenueChart" height="100"></canvas>
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

    <!-- Sparkline mini charts -->
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

    <!-- Doanh thu theo tháng hoặc theo khoảng ngày -->
    <script>
        const revenueCanvas = document.getElementById('revenueChart');
        if (revenueCanvas) {
            const monthlyRevenueCtx = revenueCanvas.getContext('2d');
            new Chart(monthlyRevenueCtx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: @json($chartData),
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                // Lấy lại đúng giá trị từ dataset
                                const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                return 'Doanh thu (VNĐ): ' + Number(value).toLocaleString('vi-VN');
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            scaleLabel: {
                                display: true,
                                labelString: @json($chartTitle)
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function(value) {
                                    return Number(value).toLocaleString('vi-VN') + ' ₫';
                                }
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Doanh thu (VNĐ)'
                            }
                        }]
                    }
                }
            });
        }
    </script>




    <!-- Số đơn hàng theo ngày trong tháng -->
    <script>
        const ordersPerDayCtx = document.getElementById('ordersPerDayChart').getContext('2d');
        new Chart(ordersPerDayCtx, {
            type: 'bar',
            data: {
                labels: [...Array({{ \Carbon\Carbon::create($year, $month)->daysInMonth }}).keys()].map(i =>
                    `Ngày ${i + 1}`),
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

    <!-- Trạng thái đơn hàng -->
    <script>
        const ctxStatus = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: @json($orderStatusLabels),
                datasets: [{
                    data: @json($orderStatusCounts),
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.parsed} đơn hàng`;
                            }
                        }
                    }
                }
            }
        });
    </script>


    <!-- Top sản phẩm bán chạy -->
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
                responsive: true,
                legend: {
                    display: false
                },
                tooltips: {
                    callbacks: {
                        title: function(tooltipItems, data) {
                            // Dòng trên: tên sản phẩm
                            return data.labels[tooltipItems[0].index];
                        },
                        label: function(tooltipItem, data) {
                            // Dòng dưới: số lượng bán
                            const value = data.datasets[0].data[tooltipItem.index];
                            return 'Số lượng bán: ' + value;
                        }
                    }
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            display: false // ❌ Ẩn tên sản phẩm dưới trục X
                        },
                        gridLines: {
                            display: false // Ẩn luôn vạch chia dọc
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>

@endsection
