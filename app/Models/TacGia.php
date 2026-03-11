<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TacGia extends Model
{
    protected $table = 'tacgia';
    protected $primaryKey = 'ma_tac_gia';
    public $incrementing = false;
    protected $fillable = ['ma_tac_gia', 'ten_tac_gia'];

}