<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $table = 'loaisach';
    protected $primaryKey = 'ma_loai_sach';
    public $incrementing = false;
    protected $fillable = ['ma_loai_sach', 'ten_loai_sach'];

}
