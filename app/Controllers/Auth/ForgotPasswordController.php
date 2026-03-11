<?php

namespace App\Controllers\Auth;

use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use League\Plates\Engine;

class ForgotPasswordController
{
    private $templates;

    public function __construct() {
        // Đường dẫn tới thư mục views
        $this->templates = new Engine(__DIR__ . '/../../../views');
    }

    public function showForm() {
        echo $this->templates->render('auth/forgot_password', ['title' => 'Quên mật khẩu']);
    }

    public function sendResetLink() {
        $email = $_POST['email'] ?? '';
        
        // 1. Kiểm tra Email có tồn tại không
        $user = User::where('email', $email)->first();

        if (!$user) {
            echo "<script>alert('Email không tồn tại!'); window.history.back();</script>";
            return;
        }

        // 2. Tạo token và lưu vào DB
        $token = bin2hex(random_bytes(32));
        $user->reset_token = $token;
        $user->reset_token_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        $user->save();

        // 3. Tạo link reset
        // LƯU Ý: Sửa lại domain http://localhost/bookstore/public nếu cần thiết cho đúng với môi trường của bạn
        $domain = "http://localhost:8000/bookstore/public"; // Hoặc http://localhost:8000
        $link = "$domain/reset-password?token=" . $token . "&email=" . $email;

        // 4. Gửi Mail bằng PHPMailer
        $mail = new PHPMailer(true);

        try {
            // --- Cấu hình Server ---
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Bật dòng này nếu muốn xem lỗi chi tiết
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'];
            $mail->Password   = $_ENV['MAIL_PASSWORD']; // Password ứng dụng 16 ký tự
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Tương ứng với cổng 587
            $mail->Port       = $_ENV['MAIL_PORT'];
            $mail->CharSet    = 'UTF-8'; // Để gửi tiếng Việt không lỗi font

            // --- Người gửi & Người nhận ---
            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME'] ?? 'Bookstore');
            $mail->addAddress($email, $user->name);

            // --- Nội dung Email ---
            $mail->isHTML(true);
            $mail->Subject = 'Yêu cầu đặt lại mật khẩu - Bookworms Store';
            
            $mailBody = "
                <h3>Xin chào {$user->name},</h3>
                <p>Bạn vừa yêu cầu đặt lại mật khẩu tại Bookworms Store.</p>
                <p>Vui lòng nhấn vào link dưới đây để đổi mật khẩu (Link hết hạn sau 15 phút):</p>
                <p><a href='$link' style='background: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Đặt lại mật khẩu</a></p>
                <p>Hoặc copy đường dẫn này: $link</p>
                <p>Nếu bạn không yêu cầu, vui lòng bỏ qua email này.</p>
            ";
            
            $mail->Body    = $mailBody;
            $mail->AltBody = "Copy link này để reset pass: $link"; // Cho trình duyệt không hỗ trợ HTML

            $mail->send();
            
            echo "<script>alert('Link đặt lại mật khẩu đã được gửi vào email của bạn!'); window.location.href='/bookstore/public/login';</script>";

        } catch (Exception $e) {
            echo "Lỗi không thể gửi mail. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function showResetForm() {
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';

        // Validate token
        $user = User::where('email', $email)
                    ->where('reset_token', $token)
                    ->where('reset_token_expiry', '>', date('Y-m-d H:i:s'))
                    ->first();

        if (!$user) {
            die("Link không hợp lệ hoặc đã hết hạn.");
        }

        echo $this->templates->render('auth/reset_password', [
            'title' => 'Đặt lại mật khẩu',
            'email' => $email,
            'token' => $token
        ]);
    }

    public function resetPassword() {
        $email = $_POST['email'];
        $token = $_POST['token'];
        $newPass = $_POST['password'];
        $confirmPass = $_POST['password_confirmation']; // Lấy giá trị ô nhập lại

        if (strlen($newPass) < 8) {
            echo "<script>alert('Mật khẩu phải có ít nhất 8 ký tự!'); window.history.back();</script>";
            return;
        }

        // 2. Kiểm tra trùng khớp
        if ($newPass !== $confirmPass) {
            echo "<script>alert('Mật khẩu nhập lại không khớp!'); window.history.back();</script>";
            return;
        }

        $user = User::where('email', $email)->where('reset_token', $token)->first();

        if ($user) {
            $user->password = password_hash($newPass, PASSWORD_DEFAULT);
            $user->reset_token = null;
            $user->reset_token_expiry = null;
            $user->save();

            echo "<script>alert('Đổi mật khẩu thành công! Vui lòng đăng nhập.'); window.location.href='/bookstore/public/login';</script>";
            exit;
        } else {
            echo "Lỗi xác thực.";
        }
    }
}