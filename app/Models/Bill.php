<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $table = 'hoadon';
    protected $primaryKey = 'ma_hoa_don';
    protected $foreignKey = 'id';
    public $timestamps = false;
    protected $fillable = [
                        'ma_hoa_don', 
                        'id',
                        'ngay_lap', 
                        'trang_thai',
                        'trang_thai_thanh_toan',
                        'tong_tien', 
                        'ten_khach_hang',
                        'sdt', 
                        'dia_chi'];
}