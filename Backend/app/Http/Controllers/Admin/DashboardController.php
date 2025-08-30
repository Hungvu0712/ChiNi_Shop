<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.404:dashboard')->only('index', 'show');
    }

    public function index(Request $request)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $today = $now->toDateString();

        $year = $request->input('year', $now->year);
        $month = $request->input('month', $now->month);

        // Lọc theo ngày (nếu có)
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $orderQuery = Order::query();

        if ($startDate && $endDate) {
            $orderQuery->whereBetween('created_at', [
                $startDate . " 00:00:00",
                $endDate . " 23:59:59"
            ]);
        } else {
            $orderQuery->where('created_at', '>=', $startOfMonth);
        }

        $orderIdsThisMonth = $orderQuery->pluck('id');

        // Doanh thu tháng hiện tại hoặc theo khoảng ngày
        $monthlyRevenue = OrderItem::whereIn('order_id', $orderIdsThisMonth)->sum('total_price');

        // ✅ Tổng doanh thu (không phụ thuộc lọc)
        $totalRevenue = OrderItem::sum('total_price');

        $ordersToday = Order::whereDate('created_at', $today)->count();
        $activeProducts = Product::where('active', 1)->count();
        $userCount = User::count();

        // --- Biểu đồ doanh thu theo ngày hoặc theo tháng ---
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $period = \Carbon\CarbonPeriod::create($start, $end);

            $revenuePerDayChart = OrderItem::selectRaw("DATE(orders.created_at) as date, SUM(order_items.total_price) as total")
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [
                    $startDate . " 00:00:00",
                    $endDate . " 23:59:59"
                ])
                ->groupBy(DB::raw('DATE(orders.created_at)'))
                ->pluck('total', 'date')
                ->toArray();

            $chartLabels = [];
            $revenuePerDay = [];
            foreach ($period as $date) {
                $key = $date->toDateString();
                $chartLabels[] = 'Ngày ' . $date->day;
                $revenuePerDay[] = $revenuePerDayChart[$key] ?? 0;
            }

            $chartData = $revenuePerDay;
            $chartTitle = "Ngày";
        } else {
            $chartLabels = [];
            $chartData = [];
            for ($m = 1; $m <= 12; $m++) {
                $chartLabels[] = "Tháng " . $m;
                $total = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereYear('orders.created_at', $year)
                    ->whereMonth('orders.created_at', $m)
                    ->sum('order_items.total_price');
                $chartData[] = $total;
            }
            $chartTitle = "Tháng";

            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            $revenuePerDayChart = OrderItem::selectRaw('DAY(orders.created_at) as day, SUM(order_items.total_price) as total')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereYear('orders.created_at', $year)
                ->whereMonth('orders.created_at', $month)
                ->groupBy(DB::raw('DAY(orders.created_at)'))
                ->pluck('total', 'day')
                ->toArray();

            $revenuePerDay = [];
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $revenuePerDay[] = $revenuePerDayChart[$i] ?? 0;
            }
        }

        // --- Biểu đồ đơn hàng theo ngày ---
        if ($startDate && $endDate) {
            $ordersPerDayChart = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->whereBetween('created_at', [
                    $startDate . " 00:00:00",
                    $endDate . " 23:59:59"
                ])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->pluck('total', 'date')
                ->toArray();

            $ordersPerDay = [];
            foreach (\Carbon\CarbonPeriod::create(Carbon::parse($startDate), Carbon::parse($endDate)) as $date) {
                $ordersPerDay[] = $ordersPerDayChart[$date->toDateString()] ?? 0;
            }
        } else {
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            $ordersPerDayChart = Order::selectRaw('DAY(created_at) as day, COUNT(*) as total')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->groupBy(DB::raw('DAY(created_at)'))
                ->pluck('total', 'day')
                ->toArray();

            $ordersPerDay = [];
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $ordersPerDay[] = $ordersPerDayChart[$i] ?? 0;
            }
        }

        // --- Trạng thái đơn hàng ---
        $statusMap = [
            'delivered'  => 'Đã giao',
            'processing' => 'Đang xử lý',
            'cancelled'  => 'Đã huỷ',
            'pending'    => 'Chờ thanh toán',
        ];

        $orderStatusRaw = Order::select('order_status', DB::raw('count(*) as total'))
            ->groupBy('order_status')
            ->get();

        $orderStatusData = $orderStatusRaw->mapWithKeys(function ($item) use ($statusMap) {
            $label = $statusMap[$item->order_status] ?? ucfirst($item->order_status);
            return [$label => $item->total];
        });

        $orderStatusLabels = $orderStatusData->keys();
        $orderStatusCounts = $orderStatusData->values();

        // --- Top sản phẩm ---
        $topSellingProducts = OrderItem::select('products.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->groupBy('order_items.product_id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        $topProductLabels = $topSellingProducts->pluck('name');
        $topProductQuantities = $topSellingProducts->pluck('total_quantity');

        // --- Thống kê theo thành phố ---
        $orderAddresses = DB::table('orders')
            ->join('addresses', function ($join) {
                $join->on('orders.user_id', '=', 'addresses.user_id')
                    ->where('addresses.is_default', 1);
            })
            ->select('addresses.address')
            ->get();

        $cityList = ['Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng', 'Cần Thơ', 'Hải Phòng'];
        $cityStats = [];
        foreach ($orderAddresses as $item) {
            foreach ($cityList as $city) {
                if (stripos($item->address, $city) !== false) {
                    $cityStats[$city] = ($cityStats[$city] ?? 0) + 1;
                    break;
                }
            }
        }

        $totalOrdersCity = array_sum($cityStats) ?: 0;
        $orderPerCityLabels = array_keys($cityStats);
        $orderPerCityCounts = array_values($cityStats);
        $orderPerCityPercentages = $totalOrdersCity ? array_map(function ($count) use ($totalOrdersCity) {
            return round(($count / $totalOrdersCity) * 100, 2);
        }, $orderPerCityCounts) : [];

        // --- Thống kê sản phẩm theo danh mục ---
        $productPerCategory = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name as category_name', DB::raw('COUNT(products.id) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        $productCategoryLabels = $productPerCategory->pluck('category_name');
        $productCategoryCounts = $productPerCategory->pluck('total');

        return view('admin.pages.dashboard', compact(
            'monthlyRevenue',
            'totalRevenue', // ✅ thêm biến mới
            'ordersToday',
            'activeProducts',
            'userCount',
            'revenuePerDay',
            'ordersPerDay',
            'year',
            'month',
            'orderStatusLabels',
            'orderStatusCounts',
            'topProductLabels',
            'topProductQuantities',
            'orderPerCityLabels',
            'orderPerCityCounts',
            'orderPerCityPercentages',
            'productCategoryLabels',
            'productCategoryCounts',
            'startDate',
            'endDate',
            'chartLabels',
            'chartData',
            'chartTitle'
        ));
    }
}
