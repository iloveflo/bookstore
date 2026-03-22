<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use App\SessionGuard as Guard;

class RegisterController extends Controller
{
    public function __construct()
    {
        // Kích hoạt lại logic Guest Middleware: 
        // Ngăn chặn các user đã có phiên đăng nhập (session active) thực hiện lại hành vi POST/GET đăng ký.
        if (Guard::isUserLoggedIn()) {
            redirect('/home');
        }

        parent::__construct();
    }

    public function showRegisterForm()
    {
        $data = [
            'old' => $this->getSavedFormValues(),
            'errors' => session_get_once('errors')
        ];

        $this->sendPage('auth/register', $data);
    }

    public function register()
    {
        // 1. Lưu lại input cũ (ngoại trừ password) để fill lại form nếu có lỗi
        $this->saveFormValues($_POST, ['password', 'password_confirmation']);

        // 2. Sanitization: Làm sạch dữ liệu đầu vào (Ngừa XSS)
        $safeData = $this->filterUserData($_POST);

        // 3. Validation: Kiểm tra logic và định dạng Regex
        $errors = $this->validateUserData($safeData);

        // (Tùy chọn) 4. Model Validation: Nếu Model User của bạn có hàm kiểm tra riêng 
        // (ví dụ: truy vấn DB xem email đã tồn tại chưa), hãy gộp chung vào mảng $errors.
        if (method_exists(User::class, 'validate')) {
            $model_errors = User::validate($safeData);
            if (!empty($model_errors)) {
                $errors = array_merge($errors, $model_errors);
            }
        }

        // 5. Quyết định luồng đi (Control Flow)
        if (empty($errors)) {
            // Không có lỗi -> Ghi vào CSDL
            $this->createUser($safeData);

            $messages = ['success' => 'Tài khoản đã được tạo thành công. Vui lòng đăng nhập.'];
            redirect('login', ['messages' => $messages]);
        }

        // Dữ liệu không hợp lệ -> Redirect về lại trang đăng ký kèm theo mảng lỗi
        redirect('register', ['errors' => $errors]);
    }

    /**
     * Sanitization: Chỉ làm sạch, không đánh giá đúng sai
     */
    protected function filterUserData(array $data)
    {
        return [
            'name' => isset($data['name']) ? trim(strip_tags($data['name'])) : null,
            'email' => isset($data['email']) ? filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL) : null,
            'phone' => isset($data['phone']) ? preg_replace('/[^0-9]/', '', $data['phone']) : null,
            'address' => isset($data['address']) ? trim(strip_tags($data['address'])) : null,
            'password' => $data['password'] ?? null,
            'password_confirmation' => $data['password_confirmation'] ?? null
        ];
    }

    /**
     * Validation: Áp dụng các Rule kiểm tra định dạng
     */
    protected function validateUserData(array $filteredData)
    {
        $errors = [];

        if (empty($filteredData['name']) || !preg_match('/^[a-zA-Z0-9_]{3,30}$/', $filteredData['name'])) {
            $errors['name'] = 'Tên đăng nhập từ 3-30 ký tự và không chứa ký tự đặc biệt.';
        }

        if (empty($filteredData['email']) || !filter_var($filteredData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Định dạng email không hợp lệ.';
        }

        if (empty($filteredData['phone']) || !preg_match('/^0[0-9]{9}$/', $filteredData['phone'])) {
            $errors['phone'] = 'Số điện thoại phải có đúng 10 số và bắt đầu bằng số 0.';
        }

        if (empty($filteredData['address']) || mb_strlen($filteredData['address'], 'UTF-8') < 5) {
            $errors['address'] = 'Vui lòng nhập địa chỉ cụ thể (tối thiểu 5 ký tự).';
        }

        if (empty($filteredData['password']) || strlen($filteredData['password']) < 6) {
            $errors['password'] = 'Mật khẩu phải chứa ít nhất 6 ký tự.';
        }

        if ($filteredData['password'] !== $filteredData['password_confirmation']) {
            $errors['password_confirmation'] = 'Mật khẩu nhập lại không khớp.';
        }

        return $errors;
    }

    protected function createUser($data)
    {
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone' => $data['phone'],
            'address' => $data['address']
        ]);
    }
}