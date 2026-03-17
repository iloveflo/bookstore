<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Bill;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Capsule\Manager as DB;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang Tổng quan (Dashboard) cho Admin
     */
    public function index()
    {
        // Nhận tham số lọc từ Request (GET), ví dụ: 'today', 'month', 'quarter', 'year'
        // Mặc định là 'month' (tháng này)
        $filterType = isset($_GET['filter_type']) ? $_GET['filter_type'] : 'month';

        // Xây dựng Query cơ bản cho Hóa đơn (đã hoàn thành/thanh toán)
        $billQuery = Bill::query();
        $billQueryForRevenue = Bill::query();

        $today = date('Y-m-d');
        $currentMonth = date('m');
        $currentYear = date('Y');

        if ($filterType === 'today') {
            $billQuery->whereDate('ngay_lap', $today);
            $billQueryForRevenue->whereDate('ngay_lap', $today);
        } elseif ($filterType === 'month') {
            $billQuery->whereMonth('ngay_lap', $currentMonth)->whereYear('ngay_lap', $currentYear);
            $billQueryForRevenue->whereMonth('ngay_lap', $currentMonth)->whereYear('ngay_lap', $currentYear);
        } elseif ($filterType === 'quarter') {
            $currentQuarter = ceil($currentMonth / 3);
            $billQuery->whereRaw('QUARTER(ngay_lap) = ?', [$currentQuarter])->whereYear('ngay_lap', $currentYear);
            $billQueryForRevenue->whereRaw('QUARTER(ngay_lap) = ?', [$currentQuarter])->whereYear('ngay_lap', $currentYear);
        } elseif ($filterType === 'year') {
            $billQuery->whereYear('ngay_lap', $currentYear);
            $billQueryForRevenue->whereYear('ngay_lap', $currentYear);
        }

        // 1. THỐNG KÊ TỔNG QUAN (Cards)
        $totalRevenue = (clone $billQuery)->sum('tong_tien') ?? 0;
        $totalOrders = (clone $billQuery)->count();
        $totalProducts = Product::count();
        $totalUsers = User::count();

        // 2. BIỂU ĐỒ DOANH THU THEO THỜI GIAN
        $chartLabels = [];
        $chartData = [];

        if ($filterType === 'today') {
            // Hôm nay: Hiển thị doanh thu theo các khung giờ hoặc chỉ 1 cột
            $revenue = (clone $billQueryForRevenue)->sum('tong_tien') ?? 0;
            $chartLabels = ['Hôm nay (' . date('d/m') . ')'];
            $chartData = [$revenue];
        } elseif ($filterType === 'month') {
            // Tháng này: Hiển thị theo từng ngày trong tháng
            $daysInMonth = date('t', strtotime("$currentYear-$currentMonth-01"));
            $dailyRevenue = (clone $billQueryForRevenue)
                ->selectRaw('DAY(ngay_lap) as day, SUM(tong_tien) as total')
                ->groupBy('day')
                ->pluck('total', 'day')
                ->toArray();
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $chartLabels[] = 'Ngày ' . $i;
                $chartData[] = isset($dailyRevenue[$i]) ? (float)$dailyRevenue[$i] : 0;
            }
        } elseif ($filterType === 'quarter') {
            // Quý này: Hiển thị theo 3 tháng trong quý
            $startMonth = ($currentQuarter - 1) * 3 + 1;
            $monthlyRevenue = (clone $billQueryForRevenue)
                ->selectRaw('MONTH(ngay_lap) as month, SUM(tong_tien) as total')
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();
            for ($i = $startMonth; $i < $startMonth + 3; $i++) {
                $chartLabels[] = 'Tháng ' . $i;
                $chartData[] = isset($monthlyRevenue[$i]) ? (float)$monthlyRevenue[$i] : 0;
            }
        } elseif ($filterType === 'year') {
            // Năm nay: Hiển thị 12 tháng
            $monthlyRevenue = (clone $billQueryForRevenue)
                ->selectRaw('MONTH(ngay_lap) as month, SUM(tong_tien) as total')
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();
            for ($i = 1; $i <= 12; $i++) {
                $chartLabels[] = 'Tháng ' . $i;
                $chartData[] = isset($monthlyRevenue[$i]) ? (float)$monthlyRevenue[$i] : 0;
            }
        }

        // 3. TOP SẢN PHẨM BÁN CHẠY
        $topSellingBooks = Product::orderByDesc('sold')
            ->take(5)
            ->get();
            
        $topBookLabels = [];
        $topBookData = [];
        foreach ($topSellingBooks as $book) {
            $topBookLabels[] = $book->ten_sach;
            $topBookData[] = $book->sold;
        }

        // 4. DOANH SỐ THEO LOẠI SÁCH
        $categoryStats = Product::selectRaw('ma_loai_sach, COUNT(*) as tong_sach, SUM(sold) as tong_ban')
            ->groupBy('ma_loai_sach')
            ->take(5)
            ->get();
            
        $categoryLabels = [];
        $categoryData = [];
        foreach ($categoryStats as $stat) {
            $categoryLabels[] = 'Loại ' . $stat->ma_loai_sach;
            $categoryData[] = $stat->tong_ban ?? 0;
        }

        // 5. TRẠNG THÁI ĐƠN HÀNG (Áp dụng bộ lọc thời gian)
        $statusStats = (clone $billQuery)
            ->selectRaw('trang_thai, COUNT(*) as total')
            ->groupBy('trang_thai')
            ->pluck('total', 'trang_thai')
            ->toArray();
            
        // Đảm bảo mảng statusData luôn có giá trị để biểu đồ hiển thị được
        $statusData = [
            isset($statusStats[2]) ? $statusStats[2] : 0, // Hoàn thành
            isset($statusStats[1]) ? $statusStats[1] : 0, // Đang giao
            isset($statusStats[0]) ? $statusStats[0] : 0, // Chờ duyệt
            isset($statusStats[3]) ? $statusStats[3] : 0, // Đã hủy
            (isset($statusStats[4]) ? $statusStats[4] : 0) + (isset($statusStats[-1]) ? $statusStats[-1] : 0) // Trạng thái khác
        ];
        
        // Nếu tất cả là 0, thì mảng vẫn hợp lệ nhưng chart.js có thể không render. Ta có thể xét logic phụ nếu cần thiết ở front-end.

        // 6. PHƯƠNG THỨC THANH TOÁN (Áp dụng bộ lọc)
        $paymentCOD = (clone $billQuery)->where('trang_thai_thanh_toan', 0)->count(); // 0 là COD chưa thanh toán
        $paymentBank = (clone $billQuery)->where('trang_thai_thanh_toan', 1)->count(); // 1 là Bank đã thanh toán

        $paymentData = [$paymentCOD, $paymentBank];

        // 7. TRẢ VỀ GIAO DIỆN
        $this->sendPage('manage/Dashboard', [
            'totalRevenue'    => $totalRevenue,
            'totalOrders'     => $totalOrders,
            'totalProducts'   => $totalProducts,
            'totalUsers'      => $totalUsers,

            'chartLabels'     => json_encode($chartLabels),
            'chartData'       => json_encode($chartData),
            
            'topBookLabels'   => json_encode($topBookLabels),
            'topBookData'     => json_encode($topBookData),

            'categoryLabels'  => json_encode($categoryLabels),
            'categoryData'    => json_encode($categoryData),

            'statusData'      => json_encode($statusData),
            'paymentData'     => json_encode($paymentData),
            
            'filterType'      => $filterType
        ]);
    }
}