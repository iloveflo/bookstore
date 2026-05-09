<?php

namespace App\Controllers\Manage;

use App\Controllers\Controller;
use App\SessionGuard as Guard;
use App\Models\User;
use App\Models\Bill;

/**
 * ManageStaffController
 *
 * Xử lý các tính năng dành riêng cho từng Role:
 *  - ADMIN: Gán/hủy Role cho nhân sự
 *  - CUSTOMER_SUPPORT: Xem danh sách khách hàng + lịch sử mua hàng
 */
class ManageStaffController extends Controller
{
    public function __construct()
    {
        if (!Guard::isUserLoggedIn()) {
            redirect('home');
        }
        parent::__construct();
    }

    // =========================================================
    // ADMIN: Quản lý nhân sự (Gán/Hủy Role)
    // =========================================================

    /**
     * Hiển thị danh sách tất cả nhân sự + form gán role.
     * Chỉ ADMIN mới vào được.
     */
    public function staffList()
    {
        $this->requirePermission('user.assign_role');

        // Lấy tất cả user không phải CUSTOMER để hiển thị dạng "nhân sự"
        // Đồng thời lấy luôn CUSTOMER có thể tìm kiếm tên
        $search    = $_GET['search'] ?? '';
        $roleFilter = $_GET['role'] ?? '';

        $query = User::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($roleFilter)) {
            $query->where('role', $roleFilter);
        }

        // Không cho ADMIN xem chính mình trong danh sách để tránh tự gỡ role
        $query->where('id', '!=', Guard::user()->id);

        $users = $query->orderBy('role')->orderBy('name')->get();

        $this->sendPage('manage/manageStaff', [
            'users'      => $users,
            'roleLabels' => User::$roleLabels,
            'search'     => $search,
            'roleFilter' => $roleFilter,
            'success'    => session_get_once('success'),
            'error'      => session_get_once('error'),
        ]);
    }

    /**
     * Xử lý cập nhật Role cho một user.
     * POST: user_id, new_role
     */
    public function assignRole()
    {
        $this->requirePermission('user.assign_role');

        $userId  = (int) ($_POST['user_id'] ?? 0);
        $newRole = $_POST['new_role'] ?? '';

        // Validate role hợp lệ
        $allowedRoles = array_keys(User::$roleLabels);
        if (!in_array($newRole, $allowedRoles, true)) {
            redirect('staffList', ['error' => 'Role không hợp lệ.']);
            return;
        }

        $target = User::find($userId);
        if (!$target) {
            redirect('staffList', ['error' => 'Không tìm thấy người dùng.']);
            return;
        }

        // Ngăn ADMIN tự hạ quyền chính mình
        if ($target->id === Guard::user()->id) {
            redirect('staffList', ['error' => 'Bạn không thể thay đổi role của chính mình.']);
            return;
        }

        $target->update(['role' => $newRole]);

        redirect('staffList', [
            'success' => "Đã cập nhật role của \"{$target->name}\" thành " . User::$roleLabels[$newRole] . "."
        ]);
    }

    // =========================================================
    // CUSTOMER_SUPPORT: Xem khách hàng & lịch sử mua hàng
    // =========================================================

    /**
     * Danh sách khách hàng (chỉ role=CUSTOMER).
     * CUSTOMER_SUPPORT và ADMIN có thể xem.
     */
    public function customerList()
    {
        $this->requirePermission('user.view_customers');

        $search = $_GET['search'] ?? '';

        $query = User::customers(); // Scope: chỉ lấy role=CUSTOMER

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'DESC')->get();

        $this->sendPage('manage/customerList', [
            'customers' => $customers,
            'search'    => $search,
        ]);
    }

    /**
     * Xem lịch sử mua hàng của một khách cụ thể.
     * CUSTOMER_SUPPORT và ADMIN có thể xem.
     * GET: ?userId=3
     */
    public function customerBillHistory()
    {
        $this->requirePermission('user.view_bill_history');

        $userId = (int) ($_GET['userId'] ?? 0);

        // Chỉ cho phép xem lịch sử của CUSTOMER, không cho xem của staff
        $customer = User::customers()->where('id', $userId)->first();

        if (!$customer) {
            redirect('customerList');
            return;
        }

        $bills = Bill::where('id', $userId)
            ->orderBy('ma_hoa_don', 'DESC')
            ->get();

        $this->sendPage('manage/customerBillHistory', [
            'customer' => $customer,
            'bills'    => $bills,
        ]);
    }

    /**
     * Hỗ trợ reset mật khẩu cho khách (xóa reset_token để tạo lại).
     * POST: user_id
     */
    public function resetCustomerToken()
    {
        $this->requirePermission('user.reset_token');

        $userId = (int) ($_POST['user_id'] ?? 0);
        $customer = User::customers()->where('id', $userId)->first();

        if (!$customer) {
            redirect('customerList');
            return;
        }

        // Xóa token cũ → buộc khách tự yêu cầu gửi email reset lại
        $customer->update([
            'reset_token'        => null,
            'reset_token_expiry' => null,
        ]);

        redirect("customerBillHistory?userId={$userId}", [
            'success' => 'Đã xóa reset token. Khách hàng có thể yêu cầu gửi lại email đặt lại mật khẩu.'
        ]);
    }

    // =========================================================
    // HELPER RIÊNG: Kiểm tra quyền, nếu không có → 403
    // =========================================================

    /**
     * Kiểm tra quyền trước khi thực thi action.
     * Nếu không có quyền: trả về trang 403 hoặc redirect về home.
     */
    private function requirePermission(string $permission): void
    {
        if (!Guard::can($permission)) {
            // Có thể render trang lỗi đẹp hơn nếu muốn
            http_response_code(403);
            $this->sendPage('errors/403', [
                'message' => 'Bạn không có quyền truy cập tính năng này.'
            ]);
            exit;
        }
    }
}
