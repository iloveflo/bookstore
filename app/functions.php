<?php
use App\Models\User;
use App\SessionGuard as Guard;

if (!function_exists('http_accept_json')) {
	function http_accept_json()
	{
		return isset($_SERVER['HTTP_ACCEPT']) &&
			(strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/json') !== false);
	}
}

if (!function_exists('redirect')) {
	// Chuyển hướng đến một trang khác
	function redirect($location, array $data = [])
	{
		foreach ($data as $key => $value) {
			$_SESSION[$key] = $value;
		}

		header('Location: ' . $location);
		exit();
	}
}

if (!function_exists('session_get_once')) {
	// Đọc và xóa một biến trong $_SESSION
	function session_get_once($name, $default = null)
	{
		$value = $default;
		if (isset($_SESSION[$name])) {
			$value = $_SESSION[$name];
			unset($_SESSION[$name]);
		}
		return $value;
	}

}

if (!function_exists('checkRememberedLogin')) {
    function checkRememberedLogin() {
        // Chỉ check nếu chưa đăng nhập và có cookie
        if (!Guard::user() && isset($_COOKIE['remember_token'])) {
            
            $token = $_COOKIE['remember_token'];
            
            // Tìm user trong DB
            $user = User::where('remember_token', $token)->first();
            
            if ($user) {
                // Đăng nhập
                Guard::login($user);
                
                // (Tùy chọn) Đổi token mới để bảo mật hơn
                $newToken = bin2hex(random_bytes(32));
                $user->update(['remember_token' => $newToken]);
                setcookie('remember_token', $newToken, time() + (86400 * 30), "/");
            } else {
                // Nếu cookie rác (không tìm thấy user), xóa cookie đi để khỏi check lại
                setcookie('remember_token', '', time() - 3600, "/");
            }
        }
    }
}


