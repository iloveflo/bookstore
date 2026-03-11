<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'giohang';
    protected $primaryKey = ['id', 'ma_sach'];
    protected $foreignKey = 'id';
    protected $fillable = ['ma_sach', 'id', 'so_luong_sach'];
    public $incrementing = false; 
    public $timestamps = false;
    protected $keyType = ['int','string'];
}


