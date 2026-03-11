<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'sach';
    protected $primaryKey = 'ma_sach';
    
    // 1. SỬA: Tên đúng là $keyType (kiểu dữ liệu của khóa chính)
    protected $keyType = 'string'; 

    public $incrementing = false;
    
    // Nếu bạn muốn tự quản lý created_at trong Controller thì để false là đúng.
    // Nếu muốn Eloquent tự động cập nhật updated_at thì nên để true (nhưng cần cột updated_at trong DB)
    public $timestamps = false; 

    protected $fillable = [
        'ma_sach', 'ten_sach', 'gia_sach', 'khuyen_mai', 'gia_khuyen_mai', 
        'mo_ta', 'so_luong', 'sold', 'hinh_anh', 'anh_1', 'anh_2', 
        'ma_loai_sach', 'ma_tac_gia', 'ma_nxb', 'created_at', 'updated_at'
    ];

    // 2. SỬA: XÓA hàm __construct đi. 
    // Eloquent tự động xử lý việc khởi tạo. Đoạn code cũ của bạn gán $data vào created_at là sai logic.

    public static function validate(array $data)
    {
        $errors = [];

        // 3. SỬA: Dùng empty() để kiểm tra thay vì truy cập trực tiếp index mảng
        // Lý do: Khi Update, controller sẽ unset('hinh_anh') nếu không chọn ảnh.
        // Dùng empty() vừa kiểm tra tồn tại (isset), vừa kiểm tra rỗng, tránh lỗi "Undefined array key".

        // Kiểm tra tên SP
        if (empty($data['ten_sach'])) {
            $errors['ten_sach'] = 'Vui lòng nhập tên sản phẩm.';
        }

        // Kiểm tra giá SP (Cho phép giá 0 nhưng không được rỗng hoặc âm)
        if (!isset($data['gia_sach']) || $data['gia_sach'] === '') {
            $errors['gia_sach'] = 'Vui lòng nhập giá sản phẩm.';
        } elseif ($data['gia_sach'] < 0) {
            $errors['gia_sach'] = 'Giá sản phẩm không được âm.';
        }

        // Kiểm tra khuyến mãi
        // Ở đây cần cẩn thận vì số 0 được coi là empty
        if (!isset($data['khuyen_mai']) || $data['khuyen_mai'] === '') {
             $errors['khuyen_mai'] = 'Vui lòng nhập phần trăm khuyến mãi.';
        } elseif ($data['khuyen_mai'] < 0) {
            $errors['khuyen_mai'] = 'Phần trăm khuyến mãi không được âm.';
        } elseif ($data['khuyen_mai'] > 100) {
            $errors['khuyen_mai'] = 'Khuyến mãi tối đa 100%.';
        }

        // Kiểm tra số lượng SP
        if (!isset($data['so_luong']) || $data['so_luong'] === '') {
             $errors['so_luong'] = 'Vui lòng nhập số lượng sản phẩm.';
        } elseif ($data['so_luong'] <= 0) {
            // Logic cũ của bạn: <= 0 là lỗi. 
            // Nếu bạn muốn cho phép nhập kho số lượng 0 thì sửa thành < 0
            $errors['so_luong'] = 'Số lượng sản phẩm phải lớn hơn 0.';
        }

        // Kiểm tra ảnh SP
        // Logic: Chỉ báo lỗi nếu key 'hinh_anh' tồn tại trong mảng data nhưng lại rỗng.
        // Nếu key không tồn tại (do Controller unset lúc Update), ta bỏ qua (Controller sẽ tự xử lý logic giữ ảnh cũ).
        if (array_key_exists('hinh_anh', $data) && empty($data['hinh_anh'])) {
            $errors['hinh_anh'] = 'Vui lòng chọn ảnh sản phẩm.';
        }
        // Trường hợp Create: Controller truyền 'hinh_anh' => null (nếu ko up ảnh), nên dòng trên sẽ bắt được lỗi.

        // Kiểm tra mô tả SP
        if (empty($data['mo_ta'])) {
            $errors['mo_ta'] = 'Vui lòng nhập mô tả sản phẩm.';
        } elseif (strlen($data['mo_ta']) > 500) {
            $errors['mo_ta'] = 'Mô tả tối đa 500 ký tự.';
        }

        return $errors;
    }
}