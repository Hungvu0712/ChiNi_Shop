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

        $orderIdsThisMonth = Order::where('created_at', '>=', $startOfMonth)->pluck('id');

        $monthlyRevenue = OrderItem::whereIn('order_id', $orderIdsThisMonth)->sum('total_price');

        $ordersToday = Order::whereDate('created_at', $today)->count();
        $activeProducts = Product::where('active', 1)->count();
        $userCount = User::count();

        $monthlyRevenueChart = OrderItem::selectRaw('MONTH(orders.created_at) as month, SUM(order_items.total_price) as total')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereYear('orders.created_at', $year)
            ->groupBy(DB::raw('MONTH(orders.created_at)'))
            ->pluck('total', 'month')
            ->toArray();

        $revenuePerMonth = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenuePerMonth[] = $monthlyRevenueChart[$i] ?? 0;
        }

        $ordersPerDayChart = Order::selectRaw('DAY(created_at) as day, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy(DB::raw('DAY(created_at)'))
            ->pluck('total', 'day')
            ->toArray();

        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $ordersPerDay = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $ordersPerDay[] = $ordersPerDayChart[$i] ?? 0;
        }

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

        $topSellingProducts = OrderItem::select('products.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->groupBy('order_items.product_id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        $topProductLabels = $topSellingProducts->pluck('name');
        $topProductQuantities = $topSellingProducts->pluck('total_quantity');

        // ✅ Sửa JOIN: lấy địa chỉ mặc định theo user_id
        $orderAddresses = DB::table('orders')
            ->join('addresses', function ($join) {
                $join->on('orders.user_id', '=', 'addresses.user_id')
                    ->where('addresses.is_default', 1);
            })
            ->select('addresses.address')
            ->get();

        $cityList = [
            'Hà Nội',
            'Hồ Chí Minh',
            'Đà Nẵng',
            'Cần Thơ',
            'Hải Phòng',
            'An Giang',
            'Bà Rịa - Vũng Tàu',
            'Bắc Giang',
            'Bắc Kạn',
            'Bạc Liêu',
            'Bắc Ninh',
            'Bến Tre',
            'Bình Định',
            'Bình Dương',
            'Bình Phước',
            'Bình Thuận',
            'Cà Mau',
            'Cao Bằng',
            'Đắk Lắk',
            'Đắk Nông',
            'Điện Biên',
            'Đồng Nai',
            'Đồng Tháp',
            'Gia Lai',
            'Hà Giang',
            'Hà Nam',
            'Hà Tĩnh',
            'Hải Dương',
            'Hậu Giang',
            'Hòa Bình',
            'Hưng Yên',
            'Khánh Hòa',
            'Kiên Giang',
            'Kon Tum',
            'Lai Châu',
            'Lâm Đồng',
            'Lạng Sơn',
            'Lào Cai',
            'Long An',
            'Nam Định',
            'Nghệ An',
            'Ninh Bình',
            'Ninh Thuận',
            'Phú Thọ',
            'Phú Yên',
            'Quảng Bình',
            'Quảng Nam',
            'Quảng Ngãi',
            'Quảng Ninh',
            'Quảng Trị',
            'Sóc Trăng',
            'Sơn La',
            'Tây Ninh',
            'Thái Bình',
            'Thái Nguyên',
            'Thanh Hóa',
            'Thừa Thiên Huế',
            'Tiền Giang',
            'Trà Vinh',
            'Tuyên Quang',
            'Vĩnh Long',
            'Vĩnh Phúc',
            'Yên Bái'
        ];

        $cityStats = [];
        foreach ($orderAddresses as $item) {
            foreach ($cityList as $city) {
                if (stripos($item->address, $city) !== false) {
                    $cityStats[$city] = ($cityStats[$city] ?? 0) + 1;
                    break;
                }
            }
        }

        $totalOrdersCity = array_sum($cityStats);
        $orderPerCityLabels = array_keys($cityStats);
        $orderPerCityCounts = array_values($cityStats);
        $orderPerCityPercentages = array_map(function ($count) use ($totalOrdersCity) {
            return round(($count / $totalOrdersCity) * 100, 2);
        }, $orderPerCityCounts);

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
            'ordersToday',
            'activeProducts',
            'userCount',
            'revenuePerMonth',
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
        ));
    }
}
