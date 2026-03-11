<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use App\SessionGuard as Guard;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Guard::isUserLoggedIn()) {
            redirect('home');
        }

        $data = [
            'messages' => session_get_once('messages'),
            'old' => $this->getSavedFormValues(),
            'errors' => session_get_once('errors'),
            'error_wrong' => session_get_once('error_wrong')
        ];

        $this->sendPage('auth/login', $data);
    }

    public function login()
    {
        // Lấy thông tin người dùng từ form
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $errors = [];
        $error_wrong = [];

        // Validate đầu vào
        if (empty($email) && empty($password)) {
            $errors['email'] = 'Bạn chưa nhập email.';
            $errors['password'] = 'Bạn chưa nhập mật khẩu.';
        } elseif (empty($email)) {
            $errors['email'] = 'Bạn chưa nhập email.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không đúng định dạng.';
        } elseif (empty($password)) {
            $errors['password'] = 'Bạn chưa nhập mật khẩu.';
        }

        // Nếu có lỗi validate → quay lại form
        if (!empty($errors)) {
            $this->saveFormValues($_POST, ['password']);
            redirect('login', [
                'errors' => $errors
            ]);
            return;
        }

        // Tìm user
        $user = User::where('email', $email)->first();

        if (!$user) {
            $error_wrong['email_password'] = 'Email hoặc mật khẩu không đúng.';
        } elseif (Guard::login($user, ['email' => $email, 'password' => $password])) {

            // --- XỬ LÝ GHI NHỚ ĐĂNG NHẬP (AUTO LOGIN) ---
            if (isset($_POST['remember_me'])) {
                // 1. Sinh một chuỗi ngẫu nhiên bảo mật (Token)
                $token = bin2hex(random_bytes(32)); // Ra chuỗi dài 64 ký tự

                // 2. Lưu Token vào Database cho user này
                // (Giả sử bạn dùng Eloquent hoặc Model tương tự)
                $user->update(['remember_token' => $token]);
                // Hoặc nếu không dùng ORM: DB::table('users')->where('id', $user->id)->update(...)

                // 3. Lưu Token vào Cookie trình duyệt (Sống 30 ngày)
                setcookie('remember_token', $token, time() + (86400 * 30), "/");
            }
            // ---------------------------------------------

            redirect('home'); // Đăng nhập thành công
            return;
        } else {
            $error_wrong['email_password'] = 'Mật khẩu không đúng.';
        }

        // Đăng nhập thất bại
        $this->saveFormValues($_POST, ['password']);
        redirect('login', [
            'error_wrong' => $error_wrong
        ]);
    }


    public function logout()
    {
        // 1. Xóa token trong Database (Để cookie cũ không còn tác dụng)
        if (Guard::user()) {
            $user = Guard::user();
            $user->update(['remember_token' => null]);
        }

        // 2. Xóa Cookie trình duyệt
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, "/"); // Set thời gian về quá khứ để xóa
            unset($_COOKIE['remember_token']);
        }

        // 3. Xóa Session (Logic cũ của bạn)
        Guard::logout();

        redirect('login');
    }

    protected function filterUserCredentials(array $data)
    {
        return [
            'email' => filter_var($data['email'], FILTER_VALIDATE_EMAIL),
            'password' => $data['password'] ?? null
        ];
    }

    // ==========================================
    // 1. GOOGLE LOGIN
    // ==========================================
    public function loginGoogle()
    {
        $params = [
            'client_id'     => $_ENV['GOOGLE_CLIENT_ID'],
            'redirect_uri'  => $_ENV['GOOGLE_REDIRECT_URI'],
            'response_type' => 'code',
            'scope'         => 'email profile',
            'access_type'   => 'online'
        ];
        // Chuyển hướng người dùng sang Google
        header("Location: https://accounts.google.com/o/oauth2/auth?" . http_build_query($params));
        exit;
    }

    public function callbackGoogle()
    {
        if (!isset($_GET['code'])) die('Không tìm thấy mã xác thực từ Google');

        // 1. Đổi Code lấy Access Token
        $tokenParams = [
            'client_id'     => $_ENV['GOOGLE_CLIENT_ID'],
            'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'],
            'redirect_uri'  => $_ENV['GOOGLE_REDIRECT_URI'],
            'grant_type'    => 'authorization_code',
            'code'          => $_GET['code']
        ];

        $tokenData = $this->httpPost('https://oauth2.googleapis.com/token', $tokenParams);

        if (!isset($tokenData['access_token'])) die('Lỗi lấy Token Google');

        // 2. Dùng Token lấy thông tin User
        $userInfo = $this->httpGet('https://www.googleapis.com/oauth2/v1/userinfo', $tokenData['access_token']);

        // 3. Xử lý lưu vào DB bằng ELOQUENT
        $this->processUserLogin($userInfo['email'], $userInfo['name'], 'google_id', $userInfo['id']);
    }

    // ==========================================
    // 2. FACEBOOK LOGIN
    // ==========================================
    public function loginFacebook()
    {
        $params = [
            'client_id'     => $_ENV['FACEBOOK_CLIENT_ID'],
            'redirect_uri'  => $_ENV['FACEBOOK_REDIRECT_URI'],
            'response_type' => 'code',
            'scope'         => 'email,public_profile'
        ];
        header("Location: https://www.facebook.com/v18.0/dialog/oauth?" . http_build_query($params));
        exit;
    }

    public function callbackFacebook()
    {
        if (!isset($_GET['code'])) die('Không tìm thấy mã xác thực từ Facebook');

        // 1. Đổi Code lấy Token
        $urlToken = "https://graph.facebook.com/v18.0/oauth/access_token?" . http_build_query([
            'client_id'     => $_ENV['FACEBOOK_CLIENT_ID'],
            'client_secret' => $_ENV['FACEBOOK_CLIENT_SECRET'],
            'redirect_uri'  => $_ENV['FACEBOOK_REDIRECT_URI'],
            'code'          => $_GET['code']
        ]);

        $tokenData = $this->httpGet($urlToken);

        if (!isset($tokenData['access_token'])) die('Lỗi lấy Token Facebook');

        // 2. Lấy thông tin User
        $urlInfo = "https://graph.facebook.com/me?fields=id,name,email&access_token=" . $tokenData['access_token'];
        $userInfo = $this->httpGet($urlInfo);

        // 3. Xử lý lưu vào DB bằng ELOQUENT
        $this->processUserLogin($userInfo['email'], $userInfo['name'], 'facebook_id', $userInfo['id']);
    }

    // ==========================================
    // 3. HÀM XỬ LÝ CHUNG (QUAN TRỌNG NHẤT)
    // ==========================================
    private function processUserLogin($email, $name, $columnId, $socialId)
    {
        // Dùng Eloquent để tìm user (Thay thế PDO)
        $user = User::where($columnId, $socialId)
            ->orWhere('email', $email)
            ->first();

        if ($user) {
            // User cũ -> Cập nhật ID mạng xã hội nếu chưa có
            if (empty($user->$columnId)) {
                $user->$columnId = $socialId;
                $user->save();
            }
        } else {
            // User mới -> Tạo bằng Eloquent Create
            $user = User::create([
                'name'      => $name,
                'email'     => $email,
                'password'  => password_hash(uniqid(), PASSWORD_DEFAULT), // Pass ngẫu nhiên
                'phone'     => null, // DB phải allow NULL
                'address'   => null, // DB phải allow NULL
                $columnId   => $socialId
            ]);
        }

        // Lưu Session đăng nhập (Native PHP)
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;

        // Chuyển hướng về trang chủ
        header("Location: /");
        exit;
    }

    // ==========================================
    // 4. CÁC HÀM HỖ TRỢ HTTP (cURL)
    // ==========================================
    private function httpPost($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Localhost thì tắt check SSL
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    private function httpGet($url, $token = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($token) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
        }
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
}
