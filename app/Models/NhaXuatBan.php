<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhaXuatBan extends Model
{
    protected $table = 'nhaxuatban';
    protected $primaryKey = 'ma_nxb';
    public $incrementing = false;
    protected $fillable = ['ma_nxb', 'ten_nxb', 'sdt_nxb', 'dia_chi_nxb'];

}