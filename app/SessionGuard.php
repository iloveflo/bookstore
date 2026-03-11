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

    public static function isAdminLoggedIn()
    {
        return isset($_SESSION['user_id']) && (static::user()->email == "admin@gmail.com");
    }
}
