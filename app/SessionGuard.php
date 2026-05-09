<?php

namespace App;

use App\Models\User;

class SessionGuard
{
    protected static $user;

    // Thêm " = []" để tham số thứ 2 là tùy chọn (không bắt buộc)
    public static function login(User $user, array $credentials = [])
    {
        // TRƯỜNG HỢP 1: Đăng nhập thường (Có truyền mật khẩu)
        if (isset($credentials['password'])) {
            $verified = password_verify($credentials['password'], $user->password);
            if (!$verified) {
                return false; // Mật khẩu sai -> Trả về false và không lưu session
            }
        }

        // TRƯỜNG HỢP 2: Auto Login (Không truyền credentials)
        // Hoặc trường hợp 1 đã qua ải mật khẩu thành công.

        // Lưu session để đăng nhập
        $_SESSION['user_id'] = $user->id;

        return true;
    }

    public static function user()
    {
        if (!static::$user && static::isUserLoggedIn()) {
            static::$user = User::find($_SESSION['user_id']);
        }
        return static::$user;
    }

    public static function logout()
    {
        static::$user = null;
        session_unset();
        session_destroy();
    }

    public static function isUserLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Kiểm tra người đang đăng nhập có phải ADMIN không.
     * Thay thế cách check email cũ bằng cột role.
     */
    public static function isAdminLoggedIn(): bool
    {
        return static::isUserLoggedIn() && static::user()?->isAdmin();
    }

    /**
     * Kiểm tra người đang đăng nhập có phải staff (nhân viên) không.
     * Staff = bất kỳ role nào không phải CUSTOMER.
     */
    public static function isStaffLoggedIn(): bool
    {
        if (!static::isUserLoggedIn()) return false;
        $user = static::user();
        return $user && $user->role !== User::ROLE_CUSTOMER;
    }

    /**
     * Kiểm tra người đang đăng nhập có role được chỉ định không.
     * Hỗ trợ truyền một role hoặc mảng role.
     *
     * @param string|string[] $role
     */
    public static function hasRole($role): bool
    {
        if (!static::isUserLoggedIn()) return false;
        $user = static::user();
        return $user && $user->hasRole($role);
    }

    /**
     * Kiểm tra người đang đăng nhập có quyền (permission) không.
     * Ủy thác toàn bộ logic xuống User::can().
     */
    public static function can(string $permission): bool
    {
        if (!static::isUserLoggedIn()) return false;
        $user = static::user();
        return $user && $user->can($permission);
    }
}

