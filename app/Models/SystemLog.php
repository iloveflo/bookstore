<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $table = 'system_logs';
    protected $primaryKey = 'id';
    public $timestamps = false; // We only have created_at as TIMESTAMP DEFAULT CURRENT_TIMESTAMP

    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'details',
    ];

    /**
     * Ghi log hệ thống nhanh
     *
     * @param string $action Hành động thực hiện
     * @param string|array|null $details Chi tiết hành động
     */
    public static function write(string $action, $details = null): void
    {
        $userId = null;
        $userName = 'Hệ thống';

        // Lấy thông tin user hiện tại nếu có session đăng nhập
        if (class_exists('\App\SessionGuard') && \App\SessionGuard::isUserLoggedIn()) {
            $user = \App\SessionGuard::user();
            if ($user) {
                $userId = $user->id;
                $userName = $user->name . ' (' . $user->role . ')';
            }
        }

        if (is_array($details) || is_object($details)) {
            $details = json_encode($details, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        try {
            self::create([
                'user_id' => $userId,
                'user_name' => $userName,
                'action' => $action,
                'details' => $details,
            ]);
        } catch (\Exception $e) {
            // Không ngắt mạch ứng dụng nếu DB lỗi khi ghi log
            error_log('SystemLog Error: ' . $e->getMessage());
        }
    }
}
