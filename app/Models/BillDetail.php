<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
    protected $table = 'chitiethoadon';
    protected $primaryKey = ['ma_hoa_don','ma_sach'];
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
                        'ma_hoa_don', 
                        'ma_sach', 
                        'so_luong_sp'];
    protected $keyType = ['int','string'];
}