<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    // Khai báo tên bảng (Tùy chọn, vì Laravel tự động nhận diện class Article thành bảng articles)
    protected $table = 'articles';

    // Rất quan trọng: Khai báo khóa chính vì bạn dùng 'article_id' thay vì 'id'
    protected $primaryKey = 'article_id';

    // Các trường cho phép thêm/sửa dữ liệu hàng loạt (Mass Assignment)
    protected $fillable = [
        'admin_id',
        'title',
        'summary',
        'content',
        'thumbnail',
        'status',
    ];

    /**
     * Mối quan hệ: Một bài viết thuộc về một người đăng (Admin/User)
     */
    public function admin(): BelongsTo
    {
        // Tham số 1: Model liên kết (User)
        // Tham số 2: Khóa ngoại trên bảng articles (admin_id)
        // Tham số 3: Khóa chính trên bảng users (id)
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }
}