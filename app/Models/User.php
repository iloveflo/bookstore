<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    // ─────────────────────────────────────────────────────────
    // ROLE CONSTANTS
    // ─────────────────────────────────────────────────────────
    const ROLE_CUSTOMER    = 'CUSTOMER';
    const ROLE_ORDER_STAFF = 'ORDER_STAFF';
    const ROLE_STORE_OWNER = 'STORE_OWNER';
    const ROLE_ADMIN       = 'ADMIN';

    /**
     * Tên hiển thị tiếng Việt cho từng role (dùng trong UI)
     */
    public static array $roleLabels = [
        self::ROLE_CUSTOMER    => 'Khách hàng',
        self::ROLE_ORDER_STAFF => 'Nhân viên bán hàng',
        self::ROLE_STORE_OWNER => 'Chủ cửa hàng',
        self::ROLE_ADMIN       => 'Quản trị viên hệ thống',
    ];

    /**
     * Ma trận quyền hạn: role => danh sách permission được phép
     * Đây là nguồn sự thật duy nhất cho việc kiểm tra quyền ở tầng PHP.
     */
    private static array $rolePermissions = [
        self::ROLE_ORDER_STAFF => [
            'bill.view', 'bill.update_status', 'bill.update_payment',
            'article.view', 'article.create', 'article.update', 'article.delete', 'article.publish',
            'user.view_customers', 'user.view_bill_history',
        ],
        self::ROLE_STORE_OWNER => [
            'product.view', 'product.create', 'product.update', 'product.delete',
            'category.manage', 'author.manage', 'publisher.manage',
            'report.revenue', 'report.order_detail', 'report.product_stats', 'dashboard.view',
            'user.view_all', 'user.view_customers', 'user.view_bill_history', 'user.assign_role',
            'user.create', 'user.update', 'user.delete',
            'bill.view', 'bill.update_status', 'bill.update_payment', 'bill.delete',
        ],
        self::ROLE_ADMIN => [
            'user.view_all', 'user.view_customers', 'user.view_bill_history',
            'user.reset_token', 'user.assign_role',
            'user.create', 'user.update', 'user.delete',
            'system.manage', 'log.view'
        ],
        self::ROLE_CUSTOMER => [],
    ];

    protected $table = 'users';
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address',
        'google_id', 'facebook_id', 'remember_token', 'role',
    ];

    // ─────────────────────────────────────────────────────────
    // ROLE & PERMISSION HELPERS
    // ─────────────────────────────────────────────────────────

    /**
     * Kiểm tra user có role được chỉ định không.
     */
    public function hasRole($role): bool
    {
        $roles = is_array($role) ? $role : [$role];
        return in_array($this->role, $roles, true);
    }

    /**
     * Kiểm tra user có quyền (permission) cụ thể không.
     */
    public function can(string $permission): bool
    {
        $allowed = self::$rolePermissions[$this->role] ?? [];
        return in_array($permission, $allowed, true);
    }

    /** Kiểm tra nhanh: có phải Admin không */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Kiểm tra nhanh: có phải nhân viên (staff) không
     * (tức là không phải CUSTOMER)
     */
    public function isStaff(): bool
    {
        return $this->role !== self::ROLE_CUSTOMER;
    }

    /** Trả về nhãn tiếng Việt của role hiện tại */
    public function getRoleLabel(): string
    {
        return self::$roleLabels[$this->role] ?? $this->role;
    }

    // ─────────────────────────────────────────────────────────
    // ELOQUENT SCOPES
    // ─────────────────────────────────────────────────────────

    /**
     * Scope: Chỉ lấy CUSTOMER (dùng cho CUSTOMER_SUPPORT)
     * Ví dụ: User::customers()->get()
     */
    public function scopeCustomers($query)
    {
        return $query->where('role', self::ROLE_CUSTOMER);
    }

    /**
     * Scope: Chỉ lấy nhân viên (staff, không tính CUSTOMER)
     * Ví dụ: User::staff()->get()
     */
    public function scopeStaff($query)
    {
        return $query->whereNotIn('role', [self::ROLE_CUSTOMER]);
    }

    // ─────────────────────────────────────────────────────────
    // VALIDATION
    // ─────────────────────────────────────────────────────────

    public static function validate(array $data) {
        $errors = [];
        if (strlen($data['name']) < 2) {
            $errors['name'] = 'Tên đăng nhập phải có từ 2 ký tự trở lên';
        }
        if (! $data['email']) {
            $errors['email'] = 'Vui lòng nhập email';
        } else if (!preg_match("/^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*@[a-zA-Z0-9]+(\.[a-zA-Z0-9]{3,}+)*(\.[a-zA-Z]{2,})$/i", $data['email'])) {
            $errors['email'] = 'Định dạng email không đúng';
        } else if (static::where('email', $data['email'])->count() > 0) {
            $errors['email'] = 'Email này đã được sử dụng';
        }
        if (!preg_match("/^[0-9]{10,11}$/", $data['phone'])) {
            $errors['phone'] = 'Định dạng số điện thoại không đúng';
        }
        if (!strlen($data['address'])) {
            $errors['address'] = 'Vui lòng nhập địa chỉ';
        }
        if (strlen($data['password']) < 6) {
            $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
        } elseif ($data['password'] != $data['password_confirmation']) {
            $errors['password_confirmation'] = 'Mật khẩu nhập lại không trùng khớp';
        }

        return $errors;
    }

    public static function validateUpdate(array $data) {
        $errors = [];
        if (strlen($data['name']) < 2) {
            $errors['name'] = 'Tên đăng nhập phải có từ 2 ký tự trở lên';
        }
        if (! $data['email']) {
            $errors['email'] = 'Vui lòng nhập email';
        } else if (!preg_match("/^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)*@[a-zA-Z0-9]+(\.[a-zA-Z0-9]{3,}+)*(\.[a-zA-Z]{2,})$/i", $data['email'])) {
            $errors['email'] = 'Định dạng email không đúng';
        } else if (static::where('email', $data['email'])->count() > 0) {
            $errors['email'] = 'Email này đã được sử dụng';
        }
        if (!preg_match("/^[0-9]{10,11}$/", $data['phone'])) {
            $errors['phone'] = 'Định dạng số điện thoại không đúng';
        }
        if (!strlen($data['address'])) {
            $errors['address'] = 'Vui lòng nhập địa chỉ';
        }

        return $errors;
    }

    public static function validatePass(array $data) {
        $errors = [];
        if (strlen($data['new_password']) < 6) {
            $errors['new_password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
        } elseif ($data['new_password'] != $data['password_confirmation']) {
            $errors['password_confirmation'] = 'Mật khẩu nhập lại không trùng khớp';
        }

        return $errors;
    }

    // ─────────────────────────────────────────────────────────
    // RELATIONSHIPS
    // ─────────────────────────────────────────────────────────

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'admin_id', 'id');
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'id', 'id');
    }
}
